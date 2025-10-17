<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIAKAD STAI AL-FATIH') }} - Sistem Informasi Akademik</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Additional inline styles for Islamic geometric patterns */
            .geometric-pattern {
                position: absolute;
                width: 100px;
                height: 100px;
                opacity: 0.1;
            }

            .geometric-pattern-1 {
                top: 10%;
                left: 5%;
                transform: rotate(45deg);
                border: 3px solid #F4E5C3;
            }

            .geometric-pattern-2 {
                top: 20%;
                right: 8%;
                transform: rotate(-30deg);
                border: 3px solid #D4AF37;
                border-radius: 50%;
            }

            .geometric-pattern-3 {
                bottom: 15%;
                left: 10%;
                transform: rotate(15deg);
                border: 3px solid #F4E5C3;
                clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
            }

            .geometric-pattern-4 {
                bottom: 25%;
                right: 5%;
                transform: rotate(-45deg);
                border: 3px solid #D4AF37;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-8 sm:px-6 lg:px-8 islamic-pattern-bg">
            <!-- Islamic Geometric Decorations -->
            <div class="geometric-pattern geometric-pattern-1"></div>
            <div class="geometric-pattern geometric-pattern-2"></div>
            <div class="geometric-pattern geometric-pattern-3"></div>
            <div class="geometric-pattern geometric-pattern-4"></div>

            <!-- Logo and Branding -->
            <div class="stai-logo relative z-10 mb-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 mx-auto mb-4" />
                </a>
                <h1 class="text-3xl md:text-4xl font-bold text-white drop-shadow-lg">
                    STAI AL-FATIH
                </h1>
                <div class="logo-ornament mx-auto my-3"></div>
                <h2 class="text-sm md:text-base font-light text-yellow-100 tracking-wider">
                    SISTEM INFORMASI AKADEMIK
                </h2>
            </div>

            <!-- Main Content Card -->
            <div class="w-full sm:max-w-md lg:max-w-lg relative z-10">
                <div class="islamic-card px-8 py-8 sm:px-10 sm:py-10">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-white/80">
                        &copy; {{ date('Y') }} STAI AL-FATIH. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
