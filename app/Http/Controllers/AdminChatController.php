<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_history' => 'array',
            'user_escalation' => 'boolean' // New field to indicate user escalation
        ]);

        $userMessage = $request->input('message');
        $conversationHistory = $request->input('conversation_history', []);
        $isUserEscalation = $request->input('user_escalation', false);
        
        // Get current user
        $user = Auth::user();
        
        // If this is a user escalation (user wants to talk to admin)
        if ($isUserEscalation || !$user || !$this->isAdmin($user)) {
            // Handle as user escalation - simulate admin response
            $response = $this->generateUserEscalationResponse($userMessage, $conversationHistory);
            
            // Log user escalation activity
            Log::info('User escalation to admin chat', [
                'user_id' => $user ? $user->getAuthIdentifier() : 'guest',
                'user_name' => $user ? $user->name : 'Guest User',
                'user_message' => $userMessage,
                'admin_response' => $response
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
                'admin_name' => 'Barangay Admin',
                'admin_role' => 'Customer Support'
            ]);
        }
        
        // If this is an actual admin user
        if ($this->isAdmin($user)) {
            // Simulate admin response based on message content and context
            $response = $this->generateAdminResponse($userMessage, $conversationHistory, $user);
            
            // Log admin chat activity
            Log::info('Admin chat interaction', [
                'admin_id' => $user->getAuthIdentifier(),
                'admin_name' => $user->name,
                'user_message' => $userMessage,
                'admin_response' => $response
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
                'admin_name' => $user->name,
                'admin_role' => $user->role->name
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to process request'
        ], 403);
    }
    
    private function isAdmin($user)
    {
        return $user && $user->role && in_array($user->role->name, ['Barangay Captain', 'Barangay Secretary', 'Health Worker', 'Complaint Manager']);
    }

    private function generateAdminResponse($message, $conversationHistory, $admin)
    {
        $lowerMessage = strtolower($message);
        
        // Personal greeting with admin info
        if (str_contains($lowerMessage, 'hello') || str_contains($lowerMessage, 'hi')) {
            return "Hello! I'm {$admin->name}, {$admin->role->name} of Barangay Lumanglipa. 👋<br><br>I'm personally here to assist you with your concerns. I've reviewed our conversation and I'm ready to help. What specific assistance do you need?";
        }
        
        // Document-related inquiries
        if (str_contains($lowerMessage, 'document') || str_contains($lowerMessage, 'certificate') || str_contains($lowerMessage, 'clearance')) {
            return "I understand you need help with document requests. 📋<br><br>Let me personally assist you with this:<br><br>• **Barangay Clearance**: Processing time is 1-2 days, fee is ₱50<br>• **Certificate of Indigency**: Free of charge, 1 day processing<br>• **Certificate of Residency**: ₱30 fee, same day processing<br><br>Would you like me to check the status of any existing request or help you start a new one?";
        }
        
        // Complaint-related inquiries
        if (str_contains($lowerMessage, 'complaint') || str_contains($lowerMessage, 'problem') || str_contains($lowerMessage, 'issue')) {
            return "I'm here to personally address your complaint or concern. 🔍<br><br>As {$admin->role->name}, I take all community issues seriously. Please provide me with:<br><br>• **Details of the issue**<br>• **When it occurred**<br>• **Who was involved (if applicable)**<br>• **What resolution you're seeking**<br><br>I will personally ensure this gets proper attention and follow-up.";
        }
        
        // ID/Registration inquiries
        if (str_contains($lowerMessage, 'id') || str_contains($lowerMessage, 'register') || str_contains($lowerMessage, 'barangay id')) {
            return "I'll personally help you with your Barangay ID or registration needs. 🪪<br><br>For **Barangay ID applications**:<br>• Bring valid government ID<br>• Proof of residency (utility bill, etc.)<br>• ₱100 fee<br>• Photo will be taken at our office<br><br>I can check if your application is ready for pickup or help expedite processing if there are urgent needs. What's your current situation?";
        }
        
        // Health services
        if (str_contains($lowerMessage, 'health') || str_contains($lowerMessage, 'medical') || str_contains($lowerMessage, 'vaccine')) {
            return "I'm personally coordinating with our health services for you. 🏥<br><br>Our current health programs include:<br>• **Weekly health consultations** (Tuesdays & Thursdays)<br>• **Vaccination programs**<br>• **Senior citizen health monitoring**<br>• **Maternal care assistance**<br><br>What specific health service do you need? I can personally arrange priority scheduling if it's urgent.";
        }
        
        // General administrative concerns
        if (str_contains($lowerMessage, 'schedule') || str_contains($lowerMessage, 'appointment') || str_contains($lowerMessage, 'meeting')) {
            return "I can personally arrange a meeting or appointment for you. 📅<br><br>As {$admin->role->name}, I'm available for:<br>• **Personal consultations**<br>• **Document assistance**<br>• **Complaint resolution meetings**<br>• **Community concerns discussion**<br><br>When would be convenient for you? I can accommodate urgent matters outside regular office hours if necessary.";
        }
        
        // Emergency or urgent matters
        if (str_contains($lowerMessage, 'urgent') || str_contains($lowerMessage, 'emergency') || str_contains($lowerMessage, 'asap')) {
            return "I understand this is urgent. ⚡<br><br>As {$admin->role->name}, I'm giving this my immediate personal attention. Please provide:<br><br>• **Nature of the urgency**<br>• **Your contact number**<br>• **Best time to reach you**<br><br>I will personally follow up within the next few hours. For immediate emergencies, you can also call our office directly at (043) 456-7890.";
        }
        
        // Office hours and contact
        if (str_contains($lowerMessage, 'office') || str_contains($lowerMessage, 'hours') || str_contains($lowerMessage, 'time')) {
            return "Here are our current office details: 🏢<br><br>**Office Hours:**<br>• Monday-Friday: 8:00 AM - 5:00 PM<br>• Saturday: 8:00 AM - 12:00 PM<br>• Closed Sundays & Holidays<br><br>**Contact Information:**<br>• Phone: (043) 456-7890<br>• Mobile: 0917-123-4567<br>• Email: barangay.lumanglipa@gmail.com<br><br>However, as {$admin->role->name}, I can arrange to meet you outside these hours if you have an urgent concern.";
        }
        
        // Thank you responses
        if (str_contains($lowerMessage, 'thank') || str_contains($lowerMessage, 'salamat')) {
            return "You're very welcome! 😊<br><br>I'm glad I could personally assist you. As {$admin->role->name}, it's my responsibility to ensure our community members get the help they need.<br><br>Please don't hesitate to reach out again if you need anything else. I'm always here to help our barangay residents.";
        }
        
        // Default personalized response
        return "Thank you for reaching out to me personally. 👨‍💼<br><br>As {$admin->role->name} of Barangay Lumanglipa, I'm here to provide you with direct assistance. I've carefully read your message and want to ensure I address your specific needs.<br><br>Could you please provide a bit more detail about what you need help with? This will allow me to give you the most accurate and helpful response.<br><br>I'm committed to resolving your concern promptly and effectively.";
    }
    
    private function generateUserEscalationResponse($message, $conversationHistory)
    {
        $lowerMessage = strtolower($message);
        
        // Greeting responses
        if (str_contains($lowerMessage, 'hello') || str_contains($lowerMessage, 'hi') || str_contains($lowerMessage, 'kumusta')) {
            return "Hello! 👋 I'm a live admin representative from Barangay Lumanglipa.<br><br>I can see that our chatbot wasn't able to fully address your concerns, so I'm here to provide you with personalized assistance.<br><br>I have access to your previous conversation and I'm ready to help you with any barangay-related matters. How can I assist you today?";
        }
        
        // Document requests with personal touch
        if (str_contains($lowerMessage, 'document') || str_contains($lowerMessage, 'certificate') || str_contains($lowerMessage, 'clearance')) {
            return "I can personally assist you with your document request. 📄<br><br>Looking at our conversation, I see you need help with document processing. Here's what I can do for you:<br><br>• **Expedite your request** if it's urgent<br>• **Check the status** of any pending applications<br>• **Guide you through requirements** you might be missing<br>• **Schedule a priority appointment** for document pickup<br><br>What specific document do you need, and by when do you need it?";
        }
        
        // Complaint handling with empathy
        if (str_contains($lowerMessage, 'complaint') || str_contains($lowerMessage, 'problem') || str_contains($lowerMessage, 'issue')) {
            return "I understand you have a concern that needs immediate attention. 🛡️<br><br>As a live admin representative, I want to assure you that your complaint will be handled with the utmost priority and confidentiality.<br><br>I can help you with:<br>• **Immediate escalation** to the appropriate barangay official<br>• **Personal case follow-up** and status updates<br>• **Mediation assistance** if needed<br>• **Direct contact** with relevant authorities<br><br>Please share the details of your concern, and I'll ensure it gets the attention it deserves.";
        }
        
        // Barangay ID and registration with personal service
        if (str_contains($lowerMessage, 'barangay id') || str_contains($lowerMessage, 'registration') || str_contains($lowerMessage, 'resident id')) {
            return "I'll personally help you with your Barangay ID or registration needs. 🪪<br><br>Here's what I can do for you right now:<br>• **Check your application status** in our system<br>• **Schedule a priority appointment** for document submission<br>• **Waive certain requirements** if you qualify for exemptions<br>• **Arrange same-day processing** for urgent cases<br><br>Do you have an existing application, or do you need to start a new one? I can guide you through the fastest process.";
        }
        
        // Emergency or urgent matters
        if (str_contains($lowerMessage, 'urgent') || str_contains($lowerMessage, 'emergency') || str_contains($lowerMessage, 'asap')) {
            return "I understand this is urgent and requires immediate attention. ⚡<br><br>As a live admin representative, I'm prioritizing your concern. Here's what I can do:<br><br>• **Immediate escalation** to barangay officials<br>• **Emergency contact** arrangements<br>• **After-hours assistance** if necessary<br>• **Direct mobile contact** for follow-up<br><br>Please provide the details of your urgent matter, and I'll ensure immediate action is taken.";
        }
        
        // Thank you responses
        if (str_contains($lowerMessage, 'thank') || str_contains($lowerMessage, 'salamat')) {
            return "You're very welcome! 😊<br><br>I'm glad I could personally assist you where our chatbot couldn't. It's important to us that every resident gets the help they need.<br><br>Please remember that you can always request to speak with a live admin whenever our automated system isn't sufficient. We're here to serve you personally when you need it most.";
        }
        
        // General personal assistance
        return "Thank you for choosing to speak with a live admin representative. 👨‍💼<br><br>I can see our chatbot wasn't able to fully address your needs, so I'm here to provide personalized assistance for your barangay concerns.<br><br>I have access to:<br>• **Real-time application status**<br>• **Direct contact with barangay officials**<br>• **Priority processing capabilities**<br>• **Personalized solutions** for your specific situation<br><br>Please let me know exactly what you need help with, and I'll make sure you get the assistance you deserve.";
    }
}
