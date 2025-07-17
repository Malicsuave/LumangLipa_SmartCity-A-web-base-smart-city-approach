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
            'language' => 'string|in:en,tl'
        ]);

        $userMessage = $request->input('message');
        $language = $request->input('language', 'en');

        // Check if question is barangay-related
        if (!$this->isBarangayRelated($userMessage)) {
            $response = $language === 'en' 
                ? "Thank you for reaching out! 😊
                    Unfortunately, the inquiry you've made is outside the scope of our current services.
                    If there's anything else we can help you with or if you have questions related to our services offerings, feel free to ask!
                    We're here to assist you the best we can."
                : "Makakatulong lang ako sa mga serbisyo ng Barangay Lumanglipa. Magtanong po tungkol sa mga dokumento, oras ng opisina, complaints, o iba pang serbisyo ng barangay.";
            
            return response()->json([
                'success' => true,
                'response' => $response
            ]);
        }

        try {
            // Simple system prompt
            $systemPrompt = $language === 'tl' 
                ? "Ikaw ay AI assistant para sa Barangay Lumanglipa. Sumagot sa barangay services lang."
                : "You are an AI assistant for Barangay Lumanglipa. Answer only barangay services questions.";

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
                    return $this->getFallbackResponse($userMessage, $language);
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
            return $this->getFallbackResponse($userMessage, $language);
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

    private function getFallbackResponse($userMessage, $language)
    {
        $message = strtolower($userMessage);
        
        $fallbackResponses = [
            'en' => [
                'document' => 'For document requests, visit Barangay Hall at Purok 1, Lumanglipa, Mataasnakahoy, Batangas during office hours (8AM-5PM, Monday-Friday). Required: Valid ID and proof of residency.',
                'clearance' => 'Barangay Clearance: ₱50.00 fee, 1-2 days processing. Requirements: Valid ID, proof of residency, application form. Office hours: 8AM-5PM, Mon-Fri.',
                'complaint' => 'To file a complaint: Visit the Barangay Hall, call during office hours, or use our online portal if you have an account.',
                'register' => 'To register: Click "Register" on homepage, fill out the form with your details, verify email, and wait for approval.',
                'contact' => 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Office hours: 8AM-5PM (Mon-Fri), 8AM-12PM (Sat). Email: barangaylumanglipa@gmail.com',
                'address' => 'Barangay Lumanglipa is located at Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Our office is open 8AM-5PM (Mon-Fri) and 8AM-12PM (Sat).',
                'default' => 'I can help with Barangay Lumanglipa services: document requests, office hours, complaints, registration, and contact information. What do you need help with?'
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
