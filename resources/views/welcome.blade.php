<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="D8MnsoKB8CGE--qTmPL2wOC87jUVwS7O6lZl9VaZwM8" />

    <title>SIAKAD STAI AL FATIH - Sistem Informasi Akademik</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Tailwind (Tetap dipakai) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; }
        h1, h2, h3, h4, .serif { font-family: 'Playfair Display', serif; }
        
        .section-padding { padding: 100px 20px; }
        .container-custom { max-width: 1200px; margin: 0 auto; width: 100%; }
        
        /* Hero */
        .hero-section {
            background-image: linear-gradient(rgba(18, 28, 24, 0.85), rgba(27, 77, 62, 0.8)), url('https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 80px; /* Offset navbar */
        }

        /* Nav */
        nav {
            background-color: #ffffff;
            height: 90px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #f0f0f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        
        /* Spacing Helpers (Fallback) */
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
        .mb-30 { margin-bottom: 30px; }
        .mb-40 { margin-bottom: 40px; }
        .mb-50 { margin-bottom: 50px; }
        
        .gap-20 { gap: 20px; }
        .gap-30 { gap: 30px; }

        /* Colors */
        .text-gold { color: #D4AF37; }
        .bg-gold { background-color: #D4AF37; }
        .text-green { color: #1B4D3E; }
        .bg-green { background-color: #1B4D3E; }

        /* Custom Grid for Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .section-padding { padding: 60px 20px; }
        }

        /* Buttons */
        .btn-custom {
            display: inline-block;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-gold { background-color: #D4AF37; color: white; border: none; }
        .btn-gold:hover { background-color: #b89628; transform: translateY(-3px); }
        
        .btn-outline-white { border: 1px solid rgba(255,255,255,0.5); color: white; background: rgba(255,255,255,0.1); backdrop-filter: blur(5px); }
        .btn-outline-white:hover { background: rgba(255,255,255,0.2); }

        .btn-green { background-color: #1B4D3E; color: white; }
        .btn-green:hover { background-color: #12352a; }

        .image-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-white">

    <!-- Navbar -->
    <nav>
        <div class="container-custom" style="display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
            <a href="/" style="display: flex; align-items: center; gap: 15px; text-decoration: none;">
                <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo" style="height: 50px; width: auto;">
                <div class="hidden md:block">
                    <div style="font-size: 24px; font-weight: 800; color: #1B4D3E; line-height: 1; font-family: 'Playfair Display', serif;">SIAKAD</div>
                    <div style="font-size: 11px; font-weight: 600; color: #D4AF37; letter-spacing: 2px; text-transform: uppercase;">STAI Al-Fatih</div>
                </div>
            </a>

            <div class="hidden md:flex" style="gap: 30px; align-items: center;">
                <a href="#" style="color: #1B4D3E; font-weight: 700; text-decoration: none;">Beranda</a>
                <a href="#tentang" style="color: #666; font-weight: 500; text-decoration: none;">Tentang</a>
                <a href="#prodi" style="color: #666; font-weight: 500; text-decoration: none;">Program Studi</a>
                <a href="#fasilitas" style="color: #666; font-weight: 500; text-decoration: none;">Fasilitas</a>
            </div>

            <div class="hidden md:flex" style="gap: 15px; align-items: center;">
                <a href="{{ route('public.spmb.index') }}" style="padding: 10px 25px; border-radius: 50px; font-weight: 700; font-size: 14px; color: #1B4D3E; border: 2px solid #1B4D3E; text-decoration: none;">SPMB Online</a>
                <a href="{{ route('login') }}" style="padding: 12px 25px; border-radius: 50px; font-weight: 700; font-size: 14px; color: white; background-color: #1B4D3E; text-decoration: none; box-shadow: 0 10px 20px rgba(27, 77, 62, 0.2);">Login Portal</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section">
        <div class="container-custom" style="padding: 0 20px;">
            <div style="display: inline-block; padding: 8px 20px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; margin-bottom: 30px; backdrop-filter: blur(10px);">
                <span style="color: white; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-star" style="color: #D4AF37; margin-right: 8px;"></i>Platform Akademik Digital
                </span>
            </div>
            
            <h1 style="color: white; font-size: clamp(40px, 5vw, 70px); font-weight: 700; line-height: 1.1; margin-bottom: 30px; text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                Generasi Unggul & <br>
                <span style="color: #D4AF37; font-style: italic; font-family: 'Playfair Display', serif;">Berakhlak Mulia</span>
            </h1>
            
            <p style="color: #e2e8f0; font-size: 18px; line-height: 1.8; max-width: 700px; margin: 0 auto 50px auto;">
                Sistem Informasi Akademik Terintegrasi STAI Al-Fatih Tangerang. Memudahkan akses layanan pendidikan bagi mahasiswa, dosen, dan staf.
            </p>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('login') }}" class="btn-custom btn-gold">Masuk SIAKAD</a>
                <a href="{{ route('public.spmb.index') }}" class="btn-custom btn-outline-white">Daftar Mahasiswa Baru</a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section style="background-color: #1B4D3E; padding: 60px 20px;">
        <div class="container-custom">
            <div class="stats-grid">
                <div style="text-align: center;">
                    <div style="color: #D4AF37; font-size: 48px; font-weight: 700; margin-bottom: 10px; font-family:serif;">{{ $programStudis->count() }}</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 13px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Program Studi</div>
                </div>
                <div style="text-align: center;">
                    <div style="color: #D4AF37; font-size: 48px; font-weight: 700; margin-bottom: 10px; font-family:serif;">A</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 13px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Akreditasi</div>
                </div>
                <div style="text-align: center;">
                    <div style="color: #D4AF37; font-size: 48px; font-weight: 700; margin-bottom: 10px; font-family:serif;">24/7</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 13px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Akses Online</div>
                </div>
                <div style="text-align: center;">
                    <div style="color: #D4AF37; font-size: 48px; font-weight: 700; margin-bottom: 10px; font-family:serif;">100%</div>
                    <div style="color: rgba(255,255,255,0.8); font-size: 13px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Digital</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section id="tentang" class="section-padding">
        <div class="container-custom">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 60px; align-items: center;">
                <div class="image-card">
                    <img src="https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?q=80&w=800&auto=format&fit=crop" style="width: 100%; height: 500px; object-fit: cover; display: block;" alt="Architecture">
                </div>
                <div>
                    <span style="color: #D4AF37; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 15px;">Tentang Kampus</span>
                    <h2 style="font-size: 42px; color: #1f2937; line-height: 1.2; margin-bottom: 30px;">
                        Transformasi Digital <br>
                        <span style="color: #1B4D3E; font-style: italic;">Pendidikan Islam</span>
                    </h2>
                    <p style="color: #4b5563; font-size: 18px; line-height: 1.7; margin-bottom: 30px;">
                        SIAKAD STAI Al-Fatih hadir sebagai solusi modern untuk mendukung proses pembelajaran yang efektif dan efisien. Kami berkomitmen untuk menyediakan layanan akademik terbaik dengan dukungan teknologi informasi terkini.
                    </p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div style="background: #f8fafc; padding: 20px; border-radius: 15px; display: flex; gap: 15px; align-items: start;">
                            <i class="fas fa-check-circle" style="color: #1B4D3E; font-size: 24px; margin-top: 5px;"></i>
                            <div>
                                <h4 style="margin: 0 0 5px 0; font-size: 18px; font-weight: 700;">Integrated System</h4>
                                <p style="margin: 0; font-size: 14px; color: #64748b;">Semua data terpusat</p>
                            </div>
                        </div>
                        <div style="background: #f8fafc; padding: 20px; border-radius: 15px; display: flex; gap: 15px; align-items: start;">
                            <i class="fas fa-shield-alt" style="color: #D4AF37; font-size: 24px; margin-top: 5px;"></i>
                            <div>
                                <h4 style="margin: 0 0 5px 0; font-size: 18px; font-weight: 700;">Secure Data</h4>
                                <p style="margin: 0; font-size: 14px; color: #64748b;">Keamanan terjamin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Prodi -->
    <section id="prodi" class="section-padding" style="background-color: #f8fafc;">
        <div class="container-custom">
            <div style="text-align: center; max-width: 700px; margin: 0 auto 60px auto;">
                <span style="background-color: #dcfce7; color: #166534; padding: 8px 15px; border-radius: 50px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Fakultas Agama Islam</span>
                <h2 style="font-size: 40px; margin-top: 20px; margin-bottom: 20px; color: #1f2937;">Program Studi Pilihan</h2>
                <div style="width: 80px; height: 4px; background: #D4AF37; margin: 0 auto; border-radius: 2px;"></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
                @forelse($programStudis as $prodi)
                <div style="background: white; padding: 35px; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: all 0.3s; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
                    <div style="background: #1B4D3E; width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; margin-bottom: 25px;">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px;">
                        <span style="background: #1B4D3E; color: white; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ $prodi->jenjang }}</span>
                        <span style="border: 1px solid #e2e8f0; color: #94a3b8; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-family: monospace;">{{ $prodi->kode_prodi }}</span>
                    </div>

                    <h3 style="font-size: 22px; font-weight: 700; color: #1f2937; margin-bottom: 10px; line-height: 1.4;">{{ $prodi->nama_prodi }}</h3>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">Status: Aktif</span>
                        <a href="#" style="color: #D4AF37; font-weight: 700; font-size: 13px; text-decoration: none;">Detail <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px; background: white; border-radius: 20px; border: 2px dashed #cbd5e1;">
                    <p style="color: #64748b;">Belum ada data program studi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: #102A23; color: white; padding: 80px 20px 30px 20px;">
        <div class="container-custom">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 60px; margin-bottom: 60px;">
                <div>
                     <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                        <img src="{{ asset('images/logo-alfatih.png') }}" style="height: 50px; filter: brightness(0) invert(1) opacity(0.8);">
                        <div>
                            <div style="font-size: 20px; font-weight: 800; text-transform: uppercase;">SIAKAD</div>
                        </div>
                    </div>
                    <p style="color: #94a3b8; font-size: 14px; line-height: 1.8;">
                        Jalan KH. Hasyim Ashari No. 123,<br>
                        Kota Tangerang, Banten, Indonesia.<br>
                        Email: {{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}
                    </p>
                </div>

                <div>
                    <h4 style="color: #D4AF37; margin-bottom: 30px; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700;">Hubungi Kami</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 15px; display: flex; gap: 15px; color: #cbd5e1;">
                            <i class="fas fa-phone" style="color: #D4AF37; margin-top: 5px;"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</span>
                        </li>
                        <li style="margin-bottom: 15px; display: flex; gap: 15px; color: #cbd5e1;">
                            <i class="fab fa-whatsapp" style="color: #D4AF37; margin-top: 5px;"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px; text-align: center; font-size: 13px; color: #64748b;">
                <p>&copy; {{ date('Y') }} STAI Al-Fatih Tangerang. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WA -->
    <a href="https://wa.me/{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}" target="_blank" style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 30px; box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4); z-index: 1000; text-decoration: none;">
        <i class="fab fa-whatsapp"></i>
    </a>

</body>
</html>
