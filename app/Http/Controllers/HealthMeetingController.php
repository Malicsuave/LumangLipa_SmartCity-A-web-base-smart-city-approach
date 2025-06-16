<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthMeeting;
use App\Models\HealthServiceRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HealthMeetingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'health_service_request_id' => 'required|exists:health_service_requests,id',
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
        $meeting = HealthMeeting::create([
            'health_service_request_id' => $request->health_service_request_id,
            'meeting_title' => $request->meeting_title,
            'meeting_date' => $request->meeting_date,
            'meeting_location' => $request->meeting_location,
            'meeting_notes' => $request->meeting_notes,
            'status' => 'scheduled',
            'created_by' => Auth::id(),
        ]);

        // Update the health service request status to scheduled
        $healthRequest = HealthServiceRequest::findOrFail($request->health_service_request_id);
        $healthRequest->update([
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
        $meeting = HealthMeeting::findOrFail($id);
        
        $meeting->update(['status' => 'completed']);
        
        // Also update the health service request
        $meeting->healthServiceRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting marked as completed!'
        ]);
    }

    public function cancel($id)
    {
        $meeting = HealthMeeting::findOrFail($id);
        
        $meeting->update(['status' => 'cancelled']);
        
        // Revert health service request status back to approved
        $meeting->healthServiceRequest->update([
            'status' => 'approved',
            'scheduled_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting cancelled successfully!'
        ]);
    }
}
