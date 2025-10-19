<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Announcement::with('registrations')->latest();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $announcements = $query->paginate(10);

        // Calculate metrics for display
        $metrics = [
            'total' => Announcement::count(),
            'active' => Announcement::where('is_active', true)->count(),
            'expired' => Announcement::where('end_date', '<', now())->count(),
            'total_registrations' => Announcement::sum('current_slots')
        ];

        return view('admin.announcements.index', compact('announcements', 'metrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Convert checkbox values before validation
        $requestData = $request->all();
        $requestData['is_active'] = $request->has('is_active') ? true : false;

        $validator = Validator::make($requestData, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,limited_slots,event,service,program',
            'max_slots' => 'required_if:type,limited_slots|nullable|integer|min:1',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $requestData;
        $data['current_slots'] = 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('announcements', 'public');
        }

        // Set max_slots to null if not limited_slots type
        if ($data['type'] !== 'limited_slots') {
            $data['max_slots'] = null;
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')
                        ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Announcement $announcement)
    {
        $announcement->load('registrations');
        
        // If it's an AJAX request, return JSON data
        if ($request->ajax()) {
            return response()->json([
                'id' => $announcement->id,
                'title' => $announcement->title,
                'content' => $announcement->content,
                'type' => $announcement->type,
                'type_display' => $announcement->type_display,
                'max_slots' => $announcement->max_slots,
                'current_slots' => $announcement->current_slots,
                'start_date' => $announcement->start_date,
                'end_date' => $announcement->end_date,
                'is_active' => $announcement->is_active,
                'status' => $announcement->status,
                'image' => $announcement->image,
                'created_at' => $announcement->created_at,
                'updated_at' => $announcement->updated_at,
                'registrations_count' => $announcement->registrations->count(),
                'progress_percentage' => $announcement->progress_percentage,
                'progress_color' => $announcement->progress_color,
            ]);
        }
        
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Add debugging to see what's happening
        Log::info('Update request received', [
            'announcement_id' => $announcement->id,
            'request_data' => $request->all()
        ]);

        // Convert checkbox values before validation
        $requestData = $request->all();
        $requestData['is_active'] = $request->has('is_active') ? true : false;

        $validator = Validator::make($requestData, [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,limited_slots,event,service,program',
            'max_slots' => 'required_if:type,limited_slots|nullable|integer|min:' . $announcement->current_slots,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $data = $requestData;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($announcement->image) {
                    Storage::disk('public')->delete($announcement->image);
                }
                $data['image'] = $request->file('image')->store('announcements', 'public');
            }

            // Set max_slots to null if not limited_slots type
            if ($data['type'] !== 'limited_slots') {
                $data['max_slots'] = null;
            }

            $announcement->update($data);

            Log::info('Announcement updated successfully', ['announcement_id' => $announcement->id]);

            return redirect()->route('admin.announcements.show', $announcement)
                            ->with('success', 'Announcement updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating announcement', [
                'error' => $e->getMessage(),
                'announcement_id' => $announcement->id
            ]);
            return redirect()->back()
                           ->with('error', 'Failed to update announcement: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Delete image if exists
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        // Delete all registrations
        $announcement->registrations()->delete();
        
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
                        ->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Toggle announcement status.
     */
    public function toggle(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
                        ->with('success', "Announcement {$status} successfully.");
    }

    /**
     * Show registrations for an announcement.
     */
    public function registrations(Announcement $announcement)
    {
        $registrations = $announcement->registrations()
                                   ->latest()
                                   ->paginate(15);

        return view('admin.announcements.registrations', compact('announcement', 'registrations'));
    }

    /**
     * Export registrations to CSV.
     */
    public function exportRegistrations(Announcement $announcement)
    {
        $registrations = $announcement->registrations()->get();

        $filename = 'registrations_' . Str::slug($announcement->title) . '_' . now()->format('Y-m-d') . '.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Name', 'Email', 'Phone', 'Address', 'Age', 'Registered At');

        $callback = function() use($registrations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($registrations as $registration) {
                $row = array(
                    $registration->first_name . ' ' . $registration->last_name,
                    $registration->email,
                    $registration->phone,
                    $registration->address,
                    $registration->age,
                    $registration->created_at->format('Y-m-d H:i:s')
                );
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove a specific registration.
     */
    public function destroyRegistration(AnnouncementRegistration $registration)
    {
        // Get announcement by ID to ensure we have the correct relationship
        $announcement = Announcement::find($registration->announcement_id);
        
        if (!$announcement) {
            return redirect()->back()->with('error', 'Announcement not found for this registration.');
        }
        
        try {
            // Delete the registration first
            $registration->delete();
            
            // Update the announcement's current_slots count safely
            if ($announcement->current_slots > 0) {
                $announcement->decrement('current_slots');
            }
            
            return redirect()->route('admin.announcements.registrations', $announcement)
                            ->with('success', 'Registration removed successfully.');
        } catch (\Exception $e) {
            Log::error('Error removing registration: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to remove registration. Please try again.');
        }
    }
}
