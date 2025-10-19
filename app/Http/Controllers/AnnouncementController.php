<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of active announcements.
     */
    public function index()
    {
        $announcements = Announcement::currentlyActive()
                                   ->with('registrations')
                                   ->latest()
                                   ->paginate(12);

        return view('public.announcements.index', compact('announcements'));
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        // Check if announcement is active and not expired
        if (!$announcement->is_active || $announcement->is_expired) {
            abort(404);
        }

        $announcement->load('registrations');
        
        return view('public.announcements.show', compact('announcement'));
    }

    /**
     * Register for an announcement.
     */
    public function register(Request $request, Announcement $announcement)
    {
        // Check if announcement is active and accepts registrations
        if (!$announcement->is_active || $announcement->is_expired) {
            return redirect()->back()->with('error', 'This announcement is no longer accepting registrations.');
        }

        // Check if announcement has available slots
        if (!$announcement->canRegister()) {
            return redirect()->back()->with('error', 'This announcement is full. No more slots available.');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'age' => 'required|integer|min:1|max:120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            // Register the user
            $registration = $announcement->register($request->all());
            
            return redirect()->back()->with('success', 'Registration successful! Your slot has been reserved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }

    /**
     * Check if a user is already registered.
     */
    public function checkRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'announcement_id' => 'required|exists:announcements,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        $registration = AnnouncementRegistration::where('announcement_id', $request->announcement_id)
                                              ->where('email', $request->email)
                                              ->first();

        return response()->json([
            'registered' => $registration ? true : false,
            'registration' => $registration ? [
                'name' => $registration->first_name . ' ' . $registration->last_name,
                'registered_at' => $registration->created_at->format('M j, Y g:i A')
            ] : null
        ]);
    }
}
