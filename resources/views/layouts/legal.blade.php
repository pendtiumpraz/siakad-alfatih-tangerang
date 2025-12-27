<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'STAI AL-FATIH') - Sistem Informasi Akademik</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">
    <script src="{{ asset('js/app.js') }}?v={{ config('app.asset_version', '1.0') }}" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 50%, #2D5F3F 100%);
            min-height: 100vh;
        }

        .legal-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .legal-card {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .legal-header {
            background: linear-gradient(to right, #2D5F3F, #4A7C59);
            padding: 2rem;
            text-align: center;
        }

        .legal-content {
            padding: 2.5rem;
        }

        .legal-content h2 {
            color: #2D5F3F;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #D4AF37;
        }

        .legal-content h3 {
            color: #1a1a1a;
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .legal-content p {
            color: #333333;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .legal-content ul, .legal-content ol {
            color: #333333;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .legal-content li {
            margin-bottom: 0.5rem;
            line-height: 1.7;
        }

        .logo-ornament {
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, transparent, #D4AF37, transparent);
            margin: 0.5rem auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #FFFFFF;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .back-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-links a.active {
            color: #D4AF37;
            font-weight: 600;
        }

        .update-date {
            color: #777777;
            font-size: 0.85rem;
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="min-h-screen py-8 px-4">
        <!-- Header Navigation -->
        <div class="legal-container mb-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ url('/') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Beranda
                </a>
                <div class="nav-links">
                    <a href="{{ route('privacy-policy') }}" class="{{ request()->routeIs('privacy-policy') ? 'active' : '' }}">
                        <i class="fas fa-shield-alt mr-1"></i> Privacy Policy
                    </a>
                    <a href="{{ route('terms') }}" class="{{ request()->routeIs('terms') ? 'active' : '' }}">
                        <i class="fas fa-file-contract mr-1"></i> Terms of Service
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="legal-container">
            <div class="legal-card">
                <!-- Header -->
                <div class="legal-header">
                    <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo STAI AL-FATIH" style="height: 80px; width: 80px; object-fit: contain; margin: 0 auto;">
                    <h1 style="color: #FFFFFF; font-size: 1.75rem; font-weight: 700; margin-top: 1rem;">
                        @yield('page-title', 'Legal')
                    </h1>
                    <div class="logo-ornament"></div>
                    <p style="color: #d4f5dc; font-size: 0.95rem;">STAI AL-FATIH - Sistem Informasi Akademik</p>
                </div>

                <!-- Content -->
                <div class="legal-content">
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.85rem;">
                &copy; {{ date('Y') }} STAI AL-FATIH. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
