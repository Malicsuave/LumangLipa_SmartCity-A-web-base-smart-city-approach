<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,600" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased bg-[#1E293B]">
    <x-guest-layout>
        <x-authentication-card>
            <x-slot name="logo">
                <!-- Empty div to satisfy the required slot without showing anything -->
                <div class="invisible h-0"></div>
            </x-slot>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Logo inside the form -->
                <div class="flex justify-center mb-6">
                    <x-authentication-card-logo />
                </div>

                <!-- Description area for future use -->
                <div class="text-center mb-6 text-gray-600">
                    {{ $description ?? 'Welcome back! Please enter your credentials to access your account.' }}
                </div>

                <x-validation-errors class="mb-4" />

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Email Field -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                        autofocus autocomplete="username" />
                </div>

                <!-- Password Field -->
                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Full Width Login Button -->
                <div class="mt-4">
                    <x-button class="w-full justify-center py-3">
                        {{ __('Log in') }}
                    </x-button>
                </div>
                
                <!-- Or divider -->
                <div class="relative flex items-center justify-center mt-6 mb-6">
                    <div class="absolute border-t border-gray-300 w-full"></div>
                    <div class="relative bg-white px-4 text-sm text-gray-500">{{ __('Or') }}</div>
                </div>
                
                <!-- Google Sign In Button -->
                <div class="mt-2 mb-4">
                    <a href="{{ route('login') }}" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        {{ __('Sign in with Google') }}
                    </a>
                </div>

                <!-- Links Section -->
                <div class="flex flex-col items-center justify-center mt-4 space-y-2 text-center">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                    
                    @if (Route::has('register'))
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                            {{ __('Need an account? Register') }}
                        </a>
                    @endif
                </div>
            </form>
        </x-authentication-card>
    </x-guest-layout>
</body>

</html>
