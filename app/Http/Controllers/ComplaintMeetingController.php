<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplaintMeeting;
use App\Models\Complaint;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ComplaintMeetingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_id' => 'required|exists:complaints,id',
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|date|after:now',
            'meeting_location' => 'required|string|max:255',
            'meeting_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the meeting
        $meeting = ComplaintMeeting::create([
            'complaint_id' => $request->complaint_id,
            'meeting_title' => $request->meeting_title,
            'meeting_date' => $request->meeting_date,
            'meeting_location' => $request->meeting_location,
            'meeting_notes' => $request->meeting_notes,
            'status' => 'scheduled',
            'created_by' => Auth::id(),
        ]);

        // Update the complaint status to scheduled
        $complaint = Complaint::findOrFail($request->complaint_id);
        $complaint->update([
            'status' => 'scheduled',
            'scheduled_at' => $request->meeting_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting scheduled successfully!',
            'meeting' => $meeting
        ]);
    }

    public function complete($id)
    {
        $meeting = ComplaintMeeting::findOrFail($id);
        $meeting->update(['status' => 'completed']);

        // Also update the complaint
        $meeting->complaint->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting marked as completed!'
        ]);
    }

    public function cancel($id)
    {
        $meeting = ComplaintMeeting::findOrFail($id);
        $meeting->update(['status' => 'cancelled']);

        // Update complaint status back to approved
        $meeting->complaint->update([
            'status' => 'approved',
            'scheduled_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting cancelled successfully!'
        ]);
    }
}
