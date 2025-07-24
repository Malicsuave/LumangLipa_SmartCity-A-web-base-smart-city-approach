<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'language' => 'string|in:en,tl',
            'context' => 'string|in:public,admin'
        ]);

        $userMessage = $request->input('message');
        $language = $request->input('language', 'en');
        $context = $request->input('context', 'public');

        // Check if question is barangay-related
        if (!$this->isBarangayRelated($userMessage)) {
            $response = $language === 'en' 
                ? ($context === 'admin' 
                    ? "Thank you for reaching out through the admin portal! 😊<br><br>Unfortunately, the inquiry you've made is outside the scope of our current services. However, as you're using the admin support chat, you can escalate this to speak directly with an admin if needed.<br><br>If there's anything else we can help you with or if you have questions related to our barangay services, feel free to ask!"
                    : "Thank you for reaching out! 😊
                        Unfortunately, the inquiry you've made is outside the scope of our current services.
                        If there's anything else we can help you with or if you have questions related to our services offerings, feel free to ask!
                        We're here to assist you the best we can.")
                : "Makakatulong lang ako sa mga serbisyo ng Barangay Lumanglipa. Magtanong po tungkol sa mga dokumento, oras ng opisina, complaints, o iba pang serbisyo ng barangay.";
            
            return response()->json([
                'success' => true,
                'response' => $response
            ]);
        }

        try {
            // Adjust system prompt based on context
            $systemPrompt = $language === 'tl' 
                ? "Ikaw ay AI assistant para sa Barangay Lumanglipa. Sumagot sa barangay services lang."
                : ($context === 'admin' 
                    ? "You are an AI assistant for Barangay Lumanglipa admin support. Provide helpful, detailed responses about barangay services and offer escalation to admin if needed."
                    : "You are an AI assistant for Barangay Lumanglipa. Answer only barangay services questions.");

            $response = Http::timeout(15)->withHeaders([
                'Authorization' => 'Bearer ' . config('services.huggingface.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api-inference.huggingface.co/models/microsoft/DialoGPT-large', [
                'inputs' => $systemPrompt . "\n\nUser: " . $userMessage . "\nAssistant:",
                'parameters' => [
                    'max_length' => 300,
                    'temperature' => 0.7,
                    'return_full_text' => false
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data[0]['generated_text'] ?? '';
                
                // Use fallback if AI response is empty
                if (empty($aiResponse)) {
                    return $this->getFallbackResponse($userMessage, $language, $context);
                }
                
                return response()->json([
                    'success' => true,
                    'response' => trim($aiResponse)
                ]);
            } else {
                throw new \Exception('Hugging Face API request failed');
            }

        } catch (\Exception $e) {
            // Fallback to rule-based responses for barangay services
            return $this->getFallbackResponse($userMessage, $language, $context);
        }
    }

    private function isBarangayRelated($message)
    {
        $message = strtolower($message);
        $barangayKeywords = [
            'document', 'clearance', 'certificate', 'residency', 'indigency', 'barangay',
            'office', 'hours', 'contact', 'complaint', 'register', 'services',
            'dokumento', 'serbisyo', 'opisina', 'oras', 'reklamo',
            'lumanglipa', 'batangas', 'hall', 'address', 'location', 'saan', 'nasaan'
        ];
        
        foreach ($barangayKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    private function getFallbackResponse($userMessage, $language, $context = 'public')
    {
        $message = strtolower($userMessage);
        
        $fallbackResponses = [
            'en' => [
                'document' => $context === 'admin' 
                    ? 'For document requests, visit Barangay Hall at Purok 1, Lumanglipa, Mataasnakahoy, Batangas during office hours (8AM-5PM, Monday-Friday). Required: Valid ID and proof of residency.<br><br>💡 <strong>Admin Tip:</strong> If you need expedited processing or have special circumstances, you can request to speak with an admin for priority assistance.'
                    : 'For document requests, visit Barangay Hall at Purok 1, Lumanglipa, Mataasnakahoy, Batangas during office hours (8AM-5PM, Monday-Friday). Required: Valid ID and proof of residency.',
                'clearance' => $context === 'admin' 
                    ? 'Barangay Clearance: ₱50.00 fee, 1-2 days processing. Requirements: Valid ID, proof of residency, application form. Office hours: 8AM-5PM, Mon-Fri.<br><br>💡 <strong>Admin Support Available:</strong> If you need this urgently or have questions about requirements, I can connect you with an admin for immediate assistance.'
                    : 'Barangay Clearance: ₱50.00 fee, 1-2 days processing. Requirements: Valid ID, proof of residency, application form. Office hours: 8AM-5PM, Mon-Fri.',
                'complaint' => $context === 'admin' 
                    ? 'To file a complaint: Visit the Barangay Hall, call during office hours, or use our online portal if you have an account.<br><br>🔍 <strong>Admin Escalation:</strong> For serious complaints or if you need immediate attention, I can arrange for you to speak directly with an admin who can personally handle your case.'
                    : 'To file a complaint: Visit the Barangay Hall, call during office hours, or use our online portal if you have an account.',
                'register' => $context === 'admin' 
                    ? 'To register: Click "Register" on homepage, fill out the form with your details, verify email, and wait for approval.<br><br>⚡ <strong>Admin Fast Track:</strong> Having trouble with registration? An admin can help verify your application faster and resolve any issues you\'re experiencing.'
                    : 'To register: Click "Register" on homepage, fill out the form with your details, verify email, and wait for approval.',
                'contact' => $context === 'admin' 
                    ? 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Office hours: 8AM-5PM (Mon-Fri), 8AM-12PM (Sat). Email: barangaylumanglipa@gmail.com<br><br>📞 <strong>Direct Admin Line:</strong> For urgent matters, you can request to speak with an admin who can assist you outside regular hours if necessary.'
                    : 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Office hours: 8AM-5PM (Mon-Fri), 8AM-12PM (Sat). Email: barangaylumanglipa@gmail.com',
                'address' => $context === 'admin' 
                    ? 'Barangay Lumanglipa is located at Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Our office is open 8AM-5PM (Mon-Fri) and 8AM-12PM (Sat).<br><br>🗺️ <strong>Need Directions?</strong> If you\'re having trouble finding us or need special arrangements for visiting, an admin can provide detailed directions and schedule assistance.'
                    : 'Barangay Lumanglipa is located at Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Our office is open 8AM-5PM (Mon-Fri) and 8AM-12PM (Sat).',
                'default' => $context === 'admin' 
                    ? 'I can help with Barangay Lumanglipa services: document requests, office hours, complaints, registration, and contact information.<br><br>💬 <strong>Enhanced Support:</strong> You\'re using our admin support chat! If my responses aren\'t sufficient, you can always request to speak directly with an admin for personalized assistance. What do you need help with?'
                    : 'I can help with Barangay Lumanglipa services: document requests, office hours, complaints, registration, and contact information. What do you need help with?'
            ],
            'tl' => [
                'document' => 'Para sa mga dokumento, bisitahin ang Barangay Hall sa Purok 1, Lumanglipa, Mataasnakahoy, Batangas sa oras ng opisina (8AM-5PM, Lunes-Biyernes). Kailangan: Valid ID at proof of residency.',
                'clearance' => 'Barangay Clearance: ₱50.00 bayad, 1-2 araw processing. Kailangan: Valid ID, proof of residency, application form. Oras ng opisina: 8AM-5PM, Lun-Biy.',
                'complaint' => 'Para mag-file ng complaint: Bisitahin ang Barangay Hall, tumawag sa oras ng opisina, o gamitin ang online portal kung may account.',
                'register' => 'Para mag-register: I-click ang "Register" sa homepage, punuin ang form, i-verify ang email, at maghintay ng approval.',
                'contact' => 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Oras ng opisina: 8AM-5PM (Lun-Biy), 8AM-12PM (Sab). Email: barangaylumanglipa@gmail.com',
                'address' => 'Ang Barangay Lumanglipa ay matatagpuan sa Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Bukas ang aming opisina 8AM-5PM (Lun-Biy) at 8AM-12PM (Sab).',
                'default' => 'Makakatulong ako sa mga serbisyo ng Barangay Lumanglipa: document requests, oras ng opisina, complaints, registration, at contact information. Ano ang kailangan mo?'
            ]
        ];

        $responses = $fallbackResponses[$language];
        
        if (strpos($message, 'document') !== false || strpos($message, 'dokumento') !== false) {
            return response()->json(['success' => true, 'response' => $responses['document']]);
        } elseif (strpos($message, 'clearance') !== false) {
            return response()->json(['success' => true, 'response' => $responses['clearance']]);
        } elseif (strpos($message, 'complaint') !== false || strpos($message, 'reklamo') !== false) {
            return response()->json(['success' => true, 'response' => $responses['complaint']]);
        } elseif (strpos($message, 'register') !== false) {
            return response()->json(['success' => true, 'response' => $responses['register']]);
        } elseif (strpos($message, 'contact') !== false || strpos($message, 'hours') !== false) {
            return response()->json(['success' => true, 'response' => $responses['contact']]);
        } elseif (strpos($message, 'address') !== false || strpos($message, 'location') !== false || strpos($message, 'saan') !== false || strpos($message, 'nasaan') !== false) {
            return response()->json(['success' => true, 'response' => $responses['address']]);
        } else {
            return response()->json(['success' => true, 'response' => $responses['default']]);
        }
    }
}
