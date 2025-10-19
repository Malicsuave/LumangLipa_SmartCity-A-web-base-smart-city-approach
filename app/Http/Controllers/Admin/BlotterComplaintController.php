<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlotterComplaint;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BlotterComplaintStatusNotification;

class BlotterComplaintController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = BlotterComplaint::with('resident');

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('case_number', 'like', "%{$search}%")
                      ->orWhere('complainants', 'like', "%{$search}%")
                      ->orWhere('respondents', 'like', "%{$search}%")
                      ->orWhere('complaint_details', 'like', "%{$search}%");
                });
            }

            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $blotterComplaints = $query->orderBy('created_at', 'desc')->paginate(10);

            // Statistics for dashboard
            $stats = [
                'total' => BlotterComplaint::count(),
                'pending' => BlotterComplaint::where('status', 'pending')->count(),
                'waiting_for_meeting' => BlotterComplaint::where('status', 'waiting_for_meeting')->count(),
                'meeting_scheduled' => BlotterComplaint::where('status', 'meeting_scheduled')->count(),
                'rejected' => BlotterComplaint::where('status', 'rejected')->count(),
                'resolved' => BlotterComplaint::where('status', 'resolved')->count(),
            ];

            return view('admin.blotter-complaints.index', compact('blotterComplaints', 'stats'));

        } catch (\Exception $e) {
            Log::error('Error loading blotter complaints: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load blotter complaints.');
        }
    }

    public function show($id)
    {
        try {
            $blotterComplaint = BlotterComplaint::with('resident')->findOrFail($id);
            
            // If it's an AJAX request, return JSON for modal
            if (request()->ajax()) {
                return response()->json([
                    'case_number' => $blotterComplaint->case_number,
                    'status' => $blotterComplaint->status,
                    'complainants' => $blotterComplaint->complainants,
                    'respondents' => $blotterComplaint->respondents,
                    'complaint_details' => $blotterComplaint->complaint_details,
                    'resolution_sought' => $blotterComplaint->resolution_sought,
                    'verification_method' => $blotterComplaint->verification_method,
                    'barangay_id' => $blotterComplaint->barangay_id,
                    'created_at' => $blotterComplaint->created_at->toISOString(),
                    'updated_at' => $blotterComplaint->updated_at->toISOString(),
                    'resident' => $blotterComplaint->resident ? [
                        'first_name' => $blotterComplaint->resident->first_name,
                        'last_name' => $blotterComplaint->resident->last_name,
                        'contact_number' => $blotterComplaint->resident->contact_number,
                        'address' => $blotterComplaint->resident->address,
                    ] : null
                ]);
            }
            
            // Otherwise return the view as before
            return view('admin.blotter-complaints.show', compact('blotterComplaint'));
        } catch (\Exception $e) {
            Log::error('Error showing blotter complaint: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json(['error' => 'Blotter complaint not found.'], 404);
            }
            
            return redirect()->route('admin.blotter-complaints.index')
                           ->with('error', 'Blotter complaint not found.');
        }
    }



    public function acceptComplaint(Request $request, $id)
    {
        try {
            $blotterComplaint = BlotterComplaint::with('resident')->findOrFail($id);
            
            if ($blotterComplaint->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending complaints can be accepted.'
                ], 400);
            }

            $blotterComplaint->update([
                'status' => 'waiting_for_meeting',
                'accepted_at' => now(),
                'accepted_by' => auth()->user()->name
            ]);

            // Send email notification
            $this->sendStatusNotification($blotterComplaint, 'accepted');

            Log::info('Blotter complaint accepted', [
                'case_number' => $blotterComplaint->case_number,
                'accepted_by' => auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Complaint accepted successfully. Please schedule a meeting. Email notification sent.',
                'new_status' => 'waiting_for_meeting'
            ]);

        } catch (\Exception $e) {
            Log::error('Error accepting complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept complaint.'
            ], 500);
        }
    }

    public function rejectComplaint(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:1000'
            ]);

            $blotterComplaint = BlotterComplaint::with('resident')->findOrFail($id);
            
            if ($blotterComplaint->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending complaints can be rejected.'
                ], 400);
            }

            $blotterComplaint->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->user()->name,
                'rejection_reason' => $request->rejection_reason
            ]);

            // Send email notification
            $this->sendStatusNotification($blotterComplaint, 'rejected', $request->rejection_reason);

            Log::info('Blotter complaint rejected', [
                'case_number' => $blotterComplaint->case_number,
                'rejected_by' => auth()->user()->name,
                'reason' => $request->rejection_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Complaint rejected successfully. Email notification sent.',
                'new_status' => 'rejected'
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting complaint: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject complaint.'
            ], 500);
        }
    }

    private function sendStatusNotification($blotterComplaint, $status, $rejectionReason = null)
    {
        try {
            if ($blotterComplaint->resident && $blotterComplaint->resident->email_address) {
                Log::info('Sending email notification', [
                    'case_number' => $blotterComplaint->case_number,
                    'recipient_email' => $blotterComplaint->resident->email_address,
                    'status' => $status
                ]);
                
                Mail::to($blotterComplaint->resident->email_address)->send(
                    new BlotterComplaintStatusNotification($blotterComplaint, $status, $rejectionReason)
                );
                
                Log::info('Email notification sent successfully', [
                    'case_number' => $blotterComplaint->case_number,
                    'status' => $status
                ]);
            } else {
                Log::warning('No email address found for resident', [
                    'case_number' => $blotterComplaint->case_number,
                    'resident_id' => $blotterComplaint->resident ? $blotterComplaint->resident->id : 'null'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage(), [
                'case_number' => $blotterComplaint->case_number,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

    private function sendMeetingNotification($blotterComplaint)
    {
        try {
            if ($blotterComplaint->resident && $blotterComplaint->resident->email_address) {
                Log::info('Sending meeting notification email', [
                    'case_number' => $blotterComplaint->case_number,
                    'recipient_email' => $blotterComplaint->resident->email_address,
                    'meeting_date' => $blotterComplaint->meeting_date,
                    'meeting_time' => $blotterComplaint->meeting_time
                ]);
                
                Mail::to($blotterComplaint->resident->email_address)->send(
                    new \App\Mail\BlotterComplaintMeetingNotification($blotterComplaint)
                );
                
                Log::info('Meeting notification email sent successfully', [
                    'case_number' => $blotterComplaint->case_number
                ]);
            } else {
                Log::warning('No email address found for resident - meeting notification not sent', [
                    'case_number' => $blotterComplaint->case_number,
                    'resident_id' => $blotterComplaint->resident ? $blotterComplaint->resident->id : 'null'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send meeting notification email: ' . $e->getMessage(), [
                'case_number' => $blotterComplaint->case_number,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,accepted,rejected,under_investigation,resolved,dismissed',
            ]);

            $blotterComplaint = BlotterComplaint::findOrFail($id);
            $oldStatus = $blotterComplaint->status;
            
            $blotterComplaint->update([
                'status' => $request->status,
            ]);

            Log::info('Blotter complaint status updated', [
                'case_number' => $blotterComplaint->case_number,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'updated_by' => auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'new_status' => $request->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $blotterComplaint = BlotterComplaint::findOrFail($id);
            $caseNumber = $blotterComplaint->case_number;
            
            $blotterComplaint->delete();

            Log::info('Blotter complaint deleted', [
                'case_number' => $caseNumber,
                'deleted_by' => auth()->user()->name
            ]);

            return redirect()->route('admin.blotter-complaints.index')
                           ->with('success', 'Blotter complaint deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting blotter complaint: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Failed to delete blotter complaint.');
        }
    }

    public function scheduleMeeting(Request $request, $id)
    {
        try {
            $request->validate([
                'meeting_date' => 'required|date|after_or_equal:today',
                'meeting_time' => 'required',
                'meeting_location' => 'required|string|max:255',
                'meeting_notes' => 'nullable|string|max:1000',
            ]);

            $blotterComplaint = BlotterComplaint::with('resident')->findOrFail($id);
            
            // Update complaint with meeting details
            $blotterComplaint->update([
                'status' => 'meeting_scheduled',
                'meeting_date' => $request->meeting_date,
                'meeting_time' => $request->meeting_time,
                'meeting_location' => $request->meeting_location,
                'meeting_notes' => $request->meeting_notes,
                'meeting_scheduled_at' => now(),
                'meeting_scheduled_by' => auth()->user()->name
            ]);

            // Send meeting notification email
            $this->sendMeetingNotification($blotterComplaint);

            Log::info('Meeting scheduled for blotter complaint', [
                'case_number' => $blotterComplaint->case_number,
                'meeting_date' => $request->meeting_date,
                'meeting_time' => $request->meeting_time,
                'meeting_location' => $request->meeting_location,
                'scheduled_by' => auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Meeting scheduled successfully. Email notification sent to complainant.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error scheduling meeting: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule meeting.'
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $query = BlotterComplaint::with('resident');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $blotterComplaints = $query->orderBy('created_at', 'desc')->get();

            $csvContent = "Case Number,Complainants,Respondents,Complaint Details,Resolution Sought,Status,Date Filed,Reporter Name\n";
            
            foreach ($blotterComplaints as $complaint) {
                $csvContent .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                    $complaint->case_number,
                    str_replace('"', '""', $complaint->complainants),
                    str_replace('"', '""', $complaint->respondents),
                    str_replace('"', '""', $complaint->complaint_details),
                    str_replace('"', '""', $complaint->resolution_sought),
                    ucfirst($complaint->status),
                    $complaint->created_at->format('Y-m-d H:i:s'),
                    $complaint->resident ? $complaint->resident->first_name . ' ' . $complaint->resident->last_name : 'N/A'
                );
            }

            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="blotter_complaints_' . date('Y-m-d_H-i-s') . '.csv"',
            ]);

        } catch (\Exception $e) {
            Log::error('Error exporting blotter complaints: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export data.');
        }
    }
}
