<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        // Check API key availability
        $apiKey = trim((string) config('services.huggingface.api_key'));
        $isStrictMode = config('services.huggingface.strict');
        
        if (empty($apiKey)) {
            Log::warning('Hugging Face API key is missing');
            if ($isStrictMode) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI service unavailable',
                    'message' => 'The AI service is currently unavailable. Please try again later or contact the barangay.',
                    'strict' => true
                ], 503);
            }
            return $this->getFallbackResponse($userMessage, $language, $context);
        }

        // Check if question is barangay-related
        if (!$this->isBarangayRelated($userMessage)) {
            if ($isStrictMode) {
                return response()->json([
                    'success' => false,
                    'error' => 'Out of scope',
                    'message' => 'I can only help with barangay-related questions.',
                    'strict' => true
                ], 400);
            }

            $response = $language === 'en' 
                ? ($context === 'admin' 
                    ? "Thank you for reaching out through the admin portal! 😊<br><br>Unfortunately, the inquiry you've made is outside the scope of our current services. However, as you're using the admin support chat, you can escalate this to speak directly with an admin if needed.<br><br>If there's anything else we can help you with or if you have questions related to our barangay services, feel free to ask!"
                    : "Thank you for reaching out! 😊<br><br>Unfortunately, the inquiry you've made is outside the scope of our current services.<br><br>If there's anything else we can help you with or if you have questions related to our services offerings, feel free to ask!<br><br>We're here to assist you the best we can.")
                : "Makakatulong lang ako sa mga serbisyo ng Barangay Lumanglipa. Magtanong po tungkol sa mga dokumento, oras ng opisina, complaints, o iba pang serbisyo ng barangay.";
            
            return response()->json([
                'success' => true,
                'response' => $response
            ]);
        }

        // Check for service option triggers BEFORE AI processing
        $message = strtolower($userMessage);
        
        // For document-related queries, trigger frontend service options
        if (strpos($message, 'document') !== false || strpos($message, 'dokumento') !== false || 
            strpos($message, 'clearance') !== false || strpos($message, 'certificate') !== false ||
            strpos($message, 'filing') !== false || strpos($message, 'services') !== false ||
            strpos($message, 'service') !== false) {
            return response()->json([
                'success' => true, 
                'response' => 'TRIGGER_SERVICE_OPTIONS:Document Services',
                'trigger_frontend' => true
            ]);
        }
        
        // For health-related queries, trigger frontend health service options
        if (strpos($message, 'health') !== false || strpos($message, 'medical') !== false || 
            strpos($message, 'clinic') !== false || strpos($message, 'medicine') !== false ||
            strpos($message, 'doctor') !== false || strpos($message, 'kalusugan') !== false ||
            strpos($message, 'medisina') !== false || strpos($message, 'doktor') !== false) {
            return response()->json([
                'success' => true, 
                'response' => 'TRIGGER_SERVICE_OPTIONS:Health Services',
                'trigger_frontend' => true
            ]);
        }
        
        // For complaint-related queries, trigger frontend complaint service options
        if (strpos($message, 'complaint') !== false || strpos($message, 'reklamo') !== false || 
            strpos($message, 'problem') !== false || strpos($message, 'issue') !== false ||
            strpos($message, 'concern') !== false || strpos($message, 'report') !== false ||
            strpos($message, 'problema') !== false || strpos($message, 'hinaing') !== false ||
            strpos($message, 'file complaint') !== false) {
            return response()->json([
                'success' => true, 
                'response' => 'TRIGGER_SERVICE_OPTIONS:Complaint Filing',
                'trigger_frontend' => true
            ]);
        }
        
        // For barangay id queries, trigger frontend barangay id options
        if (strpos($message, 'barangay id') !== false || strpos($message, 'id card') !== false || 
            strpos($message, 'resident id') !== false || strpos($message, 'identification') !== false) {
            return response()->json([
                'success' => true, 
                'response' => 'TRIGGER_BARANGAY_ID_OPTIONS',
                'trigger_frontend' => true
            ]);
        }

        // Try AI with retry logic
        return $this->tryAIWithRetry($userMessage, $language, $context, $apiKey, $isStrictMode);
    }

    private function tryAIWithRetry($userMessage, $language, $context, $apiKey, $isStrictMode, $attempt = 1)
    {
        $maxAttempts = 3;
        
        try {
            // Determine category and provide strict, approved context
            $category = $this->determineCategory($userMessage);
            $approvedContext = $this->getFallbackText($language, $context, $category);

            // Guardrail system prompt: rephrase only; do not add new facts
            $systemPrompt = $language === 'tl'
                ? "Ikaw si Dexter AI, AI assistant ng Barangay Lumanglipa. Gumamit LAMANG ng ibinigay na Context. HUWAG magdagdag ng bagong detalye, numero, bayad, iskedyul, o polisiya na wala sa Context. Kung wala sa Context ang hinihingi, sabihin: 'Wala po akong dagdag na impormasyon dito.' at i-suggest ang Contact."
                : ($context === 'admin'
                    ? "You are Dexter AI, the Barangay Lumanglipa assistant. STRICTLY use ONLY the provided Context. Do NOT add any new facts, fees, timelines, or policies not in the Context. If the user asks beyond the Context, say: 'I don't have that information.' and suggest Contact. Keep it concise and friendly."
                    : "You are Dexter AI, the Barangay Lumanglipa assistant. STRICTLY use ONLY the provided Context. Do NOT add new facts. If not covered, say you don't have that information and suggest Contact. Keep it concise.");

            $prompt = $systemPrompt . "\n\nContext:\n" . $approvedContext . "\n\nUser: " . $userMessage . "\nAssistant:";

            $headers = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ];

            // Add wait header to handle model loading
            if ($attempt === 1) {
                $headers['x-wait-for-model'] = 'true';
            }

            $response = Http::timeout(20)->withHeaders($headers)
                ->post('https://api-inference.huggingface.co/models/microsoft/DialoGPT-large', [
                    'inputs' => $prompt,
                    'parameters' => [
                        'max_length' => 200,
                        'temperature' => 0.3,
                        'repetition_penalty' => 1.1,
                        'return_full_text' => false,
                        'pad_token_id' => 50256
                    ]
                ]);

            $statusCode = $response->status();
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Handle different response formats
                $aiResponse = '';
                if (is_array($data) && isset($data[0]['generated_text'])) {
                    $aiResponse = trim($data[0]['generated_text']);
                } elseif (is_array($data) && isset($data[0]['text'])) {
                    $aiResponse = trim($data[0]['text']);
                }
                
                if (!empty($aiResponse)) {
                    // Clean up the response
                    $aiResponse = $this->cleanAIResponse($aiResponse);
                    
                    Log::info('AI response successful', [
                        'attempt' => $attempt,
                        'length' => strlen($aiResponse)
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'response' => $aiResponse,
                        'source' => 'ai'
                    ]);
                } else {
                    Log::warning('AI returned empty response', ['attempt' => $attempt]);
                    throw new \Exception('Empty AI response');
                }
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['error'] ?? 'Unknown error';
                
                Log::warning('AI API error', [
                    'status' => $statusCode,
                    'error' => $errorMessage,
                    'attempt' => $attempt
                ]);

                // Handle specific error cases
                if ($statusCode === 503 && str_contains($errorMessage, 'loading')) {
                    if ($attempt < $maxAttempts) {
                        Log::info('Model loading, retrying...', ['attempt' => $attempt + 1]);
                        sleep(2); // Wait before retry
                        return $this->tryAIWithRetry($userMessage, $language, $context, $apiKey, $isStrictMode, $attempt + 1);
                    }
                }

                if ($statusCode === 401 || $statusCode === 403) {
                    Log::error('AI API authentication failed', ['status' => $statusCode]);
                    throw new \Exception('Authentication failed');
                }

                throw new \Exception("API error: {$errorMessage} (Status: {$statusCode})");
            }

        } catch (\Exception $e) {
            Log::error('AI processing error', [
                'error' => $e->getMessage(),
                'attempt' => $attempt,
                'user_message' => substr($userMessage, 0, 50)
            ]);

            // Retry logic for transient errors
            if ($attempt < $maxAttempts && !str_contains($e->getMessage(), 'Authentication')) {
                Log::info('Retrying AI request', ['attempt' => $attempt + 1]);
                sleep(1);
                return $this->tryAIWithRetry($userMessage, $language, $context, $apiKey, $isStrictMode, $attempt + 1);
            }

            // Handle strict mode
            if ($isStrictMode) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI service error',
                    'message' => 'The AI service is temporarily unavailable. Please try again later or contact the barangay.',
                    'strict' => true
                ], 503);
            }

            // Fallback to rule-based responses
            Log::info('Falling back to rule-based response');
            return $this->getFallbackResponse($userMessage, $language, $context);
        }
    }

    private function cleanAIResponse($response)
    {
        // Remove any potential prompt leakage or unwanted text
        $cleaned = trim($response);
        
        // Remove common AI artifacts
        $cleaned = preg_replace('/^(User:|Assistant:|Human:|AI:)/i', '', $cleaned);
        $cleaned = preg_replace('/\n\s*\n\s*\n/', "\n\n", $cleaned); // Remove excessive newlines
        $cleaned = trim($cleaned);
        
        return $cleaned;
    }

    /**
     * Determine which approved category the message maps to
     */
    private function determineCategory($message)
    {
        $m = strtolower($message);
        if (strpos($m, 'document') !== false || strpos($m, 'dokumento') !== false) return 'document';
        if (strpos($m, 'clearance') !== false) return 'clearance';
        if (strpos($m, 'complaint') !== false || strpos($m, 'reklamo') !== false) return 'complaint';
        if (strpos($m, 'register') !== false) return 'register';
        if (strpos($m, 'contact') !== false || strpos($m, 'hours') !== false) return 'contact';
        if (strpos($m, 'address') !== false || strpos($m, 'location') !== false || strpos($m, 'saan') !== false || strpos($m, 'nasaan') !== false) return 'address';
        return 'default';
    }

    /**
     * Return the exact approved fallback text for the given category
     * so the model can only rephrase existing content.
     */
    private function getFallbackText($language, $context, $category)
    {
        $fallbackResponses = [
            'en' => [
                'document' => $context === 'admin' 
                    ? 'For document requests, visit Barangay Hall at Purok 1, Lumanglipa, Mataasnakahoy, Batangas during office hours (8AM-5PM, Monday-Friday). Required: Valid ID and proof of residency.  Admin Tip: If you need expedited processing, request to speak with an admin.'
                    : 'For document requests, visit Barangay Hall at Purok 1, Lumanglipa, Mataasnakahoy, Batangas during office hours (8AM-5PM, Monday-Friday). Required: Valid ID and proof of residency.',
                'clearance' => $context === 'admin' 
                    ? 'Barangay Clearance: ₱50.00 fee, 1-2 days processing. Requirements: Valid ID, proof of residency, application form. Office hours: 8AM-5PM, Mon-Fri. Admin support available if needed.'
                    : 'Barangay Clearance: ₱50.00 fee, 1-2 days processing. Requirements: Valid ID, proof of residency, application form. Office hours: 8AM-5PM, Mon-Fri.',
                'complaint' => $context === 'admin' 
                    ? 'To file a complaint: Visit the Barangay Hall, call during office hours, or use our online portal if you have an account. For serious matters, admin escalation is available.'
                    : 'To file a complaint: Visit the Barangay Hall, call during office hours, or use our online portal if you have an account.',
                'register' => $context === 'admin' 
                    ? 'To register: Click "Register" on homepage, fill out the form, verify email, and wait for approval. Admin can assist if you have issues.'
                    : 'To register: Click "Register" on homepage, fill out the form, verify email, and wait for approval.',
                'contact' => $context === 'admin' 
                    ? 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Office hours: 8AM-5PM (Mon-Fri), 8AM-12PM (Sat). Email: barangaylumanglipa@gmail.com'
                    : 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Office hours: 8AM-5PM (Mon-Fri), 8AM-12PM (Sat). Email: barangaylumanglipa@gmail.com',
                'address' => $context === 'admin' 
                    ? 'Barangay Lumanglipa is at Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Office hours: 8AM-5PM (Mon-Fri) and 8AM-12PM (Sat).'
                    : 'Barangay Lumanglipa is at Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Office hours: 8AM-5PM (Mon-Fri) and 8AM-12PM (Sat).',
                'default' => $context === 'admin' 
                    ? 'I can help with Barangay Lumanglipa services: document requests, office hours, complaints, registration, and contact information.'
                    : 'I can help with Barangay Lumanglipa services: document requests, office hours, complaints, registration, and contact information.'
            ],
            'tl' => [
                'document' => 'Para sa mga dokumento, bisitahin ang Barangay Hall sa Purok 1, Lumanglipa, Mataasnakahoy, Batangas sa oras ng opisina (8AM-5PM, Lunes-Biyernes). Kailangan: Valid ID at proof of residency.',
                'clearance' => 'Barangay Clearance: ₱50.00 bayad, 1-2 araw processing. Kailangan: Valid ID, proof of residency, application form. Oras ng opisina: 8AM-5PM, Lun-Biy.',
                'complaint' => 'Para mag-file ng complaint: Bisitahin ang Barangay Hall, tumawag sa oras ng opisina, o gamitin ang online portal kung may account.',
                'register' => 'Para mag-register: I-click ang "Register" sa homepage, punuin ang form, i-verify ang email, at maghintay ng approval.',
                'contact' => 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Oras ng opisina: 8AM-5PM (Lun-Biy), 8AM-12PM (Sab). Email: barangaylumanglipa@gmail.com',
                'address' => 'Ang Barangay Lumanglipa ay matatagpuan sa Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Bukas ang opisina 8AM-5PM (Lun-Biy) at 8AM-12PM (Sab).',
                'default' => 'Makakatulong ako sa: document requests, oras ng opisina, complaints, registration, at contact information.'
            ]
        ];

        $lang = in_array($language, ['en','tl']) ? $language : 'en';
        $responses = $fallbackResponses[$lang];
        return $responses[$category] ?? $responses['default'];
    }

    private function isBarangayRelated($message)
    {
        $message = strtolower($message);
        $barangayKeywords = [
            'document', 'clearance', 'certificate', 'residency', 'indigency', 'barangay',
            'office', 'hours', 'contact', 'complaint', 'register', 'services', 'service',
            'health', 'medical', 'clinic', 'medicine', 'doctor', 'kalusugan', 'medisina', 'doktor',
            'filing', 'file', 'id', 'identification', 'problem', 'issue', 'concern', 'report',
            'dokumento', 'serbisyo', 'opisina', 'oras', 'reklamo', 'problema', 'hinaing',
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
                'register' => 'Para mag-register: I-click ang "Register" sa homepage, punuin ang form, i-verify ang email, at maghintay ng approval.',
                'contact' => 'Contact: Barangay Hall, Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Oras ng opisina: 8AM-5PM (Lun-Biy), 8AM-12PM (Sab). Email: barangaylumanglipa@gmail.com',
                'address' => 'Ang Barangay Lumanglipa ay matatagpuan sa Purok 1, Lumanglipa, Mataasnakahoy, Batangas. Bukas ang aming opisina 8AM-5PM (Lun-Biy) at 8AM-12PM (Sab).',
                'default' => 'Makakatulong ako sa mga serbisyo ng Barangay Lumanglipa: document requests, oras ng opisina, complaints, registration, at contact information. Ano ang kailangan mo?'
            ]
        ];

        $responses = $fallbackResponses[$language];
        
        if (strpos($message, 'register') !== false) {
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
