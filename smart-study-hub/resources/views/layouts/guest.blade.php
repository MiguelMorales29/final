<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Smart Study Hub</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/sshlogo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/sshlogo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Custom Styles for Auth Pages -->
        <style>
            .auth-gradient {
                position: relative;
                min-height: 100vh;
                height: 100vh;
                background:
                    radial-gradient(1200px 800px at -10% 20%, rgba(20,184,166,.18), transparent 60%),
                    radial-gradient(900px 700px at 110% 0%, rgba(99,102,241,.22), transparent 60%),
                    radial-gradient(900px 600px at 20% 100%, rgba(56,189,248,.25), transparent 55%),
                    linear-gradient(120deg, #E6F6FF 0%, #EEF2FF 50%, #F8FAFF 100%);
                overflow-y: auto;
                overflow-x: hidden;
            }
            
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
            }
            
            .auth-gradient::before,
            .auth-gradient::after {
                content: "";
                position: absolute;
                width: 520px;
                height: 520px;
                filter: blur(48px);
                opacity: .65;
                z-index: 0;
                pointer-events: none;
                border-radius: 9999px;
                animation: float 14s ease-in-out infinite;
            }
            
            .auth-gradient::before {
                top: -120px;
                left: -120px;
                background: radial-gradient(circle at 30% 30%, rgba(20,184,166,.55), transparent 60%),
                            radial-gradient(circle at 70% 70%, rgba(99,102,241,.55), transparent 60%);
            }
            
            .auth-gradient::after {
                right: -140px;
                bottom: -140px;
                animation-delay: -4s;
                background: radial-gradient(circle at 40% 40%, rgba(56,189,248,.55), transparent 60%),
                            radial-gradient(circle at 70% 30%, rgba(167,139,250,.45), transparent 60%);
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0) translateX(0) scale(1); }
                50% { transform: translateY(16px) translateX(-10px) scale(1.02); }
            }
            
            .auth-content {
                position: relative;
                z-index: 10;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center auth-gradient pt-12">
            <div class="mb-4 relative z-10">
                <a href="/">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-300 dark:border-gray-600 shadow-lg">
                        <img src="{{ asset('images/sshlogo.png') }}" alt="Smart Study Hub" class="w-full h-full object-cover">
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-sm px-5 py-5 bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl relative z-10 backdrop-blur-sm bg-opacity-95">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
