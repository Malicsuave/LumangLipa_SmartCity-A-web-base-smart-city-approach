<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
                @vite(['resources/css/app.css'])

        <!-- Styles -->
        @livewireStyles

        <!-- Custom styles -->
        <link rel="stylesheet" href="{{ asset('css/app-layout.css') }}">
        
        @auth
            <!-- Admin Chatbot Styles -->
            <link rel="stylesheet" href="{{ asset('css/admin-chatbot.css?v=' . time()) }}">
        @endauth
    </head>
    <body class="font-sans antialiased admin-page">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        
        @auth
            <!-- Admin Floating Chatbot -->
            <div class="admin-chatbot-container">
                <div class="admin-chatbot-window" id="adminChatbotWindow">
                    <div class="admin-chatbot-header">
                        <h4>💬 User Conversations</h4>
                        <button class="admin-chatbot-close" id="adminChatbotClose">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Conversations List View (Inbox) -->
                    <div id="conversationsList" class="conversations-list">
                        <div style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 10px; display: block; color: #ccc;"></i>
                            Loading conversations...
                        </div>
                    </div>
                    
                    <!-- Chat View -->
                    <div id="chatView" class="chat-view" style="display: none; flex-direction: column; height: 100%;">
                        <div class="chat-header" style="padding: 10px; border-bottom: 1px solid #eee; display: flex; align-items: center;">
                            <button id="backToInbox" style="background: none; border: none; font-size: 16px; margin-right: 10px; cursor: pointer; color: #666;">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <div style="flex: 1;">
                                <div id="chatUserName" style="font-weight: bold; font-size: 14px;">User</div>
                                <div id="chatUserStatus" style="font-size: 12px; color: #666;">Online</div>
                            </div>
                        </div>
                        
                        <div id="chatMessages" class="admin-chatbot-messages" style="flex: 1; overflow-y: auto;">
                            <div style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                                Start the conversation by typing a message
                            </div>
                        </div>
                        
                        <div class="admin-chatbot-input-area">
                            <input type="text" class="admin-chatbot-input" id="adminChatbotInput" 
                                   placeholder="Type your message..." maxlength="500">
                            <button class="admin-chatbot-send" id="adminChatbotSend">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button class="admin-chatbot-toggle" id="adminChatbotToggle">
                    <div class="admin-chatbot-pulse"></div>
                    <i class="fas fa-comments"></i>
                </button>
            </div>
            
            <script src="{{ asset('js/admin-chatbot.js?v=' . time()) }}"></script>
        @endauth
    </body>
</html>
