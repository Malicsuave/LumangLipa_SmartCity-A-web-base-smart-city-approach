<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Lumanglipa - Welcome</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="{{ asset('css/splash.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="splash-container" id="splash-screen">
            <!-- Updated logo to use the correct path -->
            <img src="{{ asset('images/logo.png') }}" alt="Lumanglipa Logo" class="logo">
            
            <div class="app-name">Lumanglipa</div>
            <div class="tagline">Welcome to our application</div>
            
            <div class="loading-bar">
                <div class="loading-progress"></div>
            </div>
        </div>
        
        <script>
            // Wait for 3.5 seconds before redirecting to login page
            setTimeout(function() {
                // First fade out the splash screen
                document.getElementById('splash-screen').classList.add('fade-out');
                
                // Then redirect after the fade out animation completes
                setTimeout(function() {
                    @if (Route::has('login'))
                        window.location.href = "{{ route('login') }}";
                    @else
                        window.location.href = "/login";
                    @endif
                }, 500);
            }, 3500);
        </script>
    </body>
</html>
