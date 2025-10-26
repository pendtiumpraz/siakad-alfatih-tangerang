<x-layouts.public>
    <x-slot name="title">Status Pendaftaran</x-slot>

    @push('styles')
    <style>
        @media print {
            /* Hide non-essential elements */
            .no-print {
                display: none !important;
            }

            /* Hide sections that aren't essential for print */
            .print-hide {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                font-size: 14px;
                line-height: 1.6;
            }

            .print-card {
                box-shadow: none !important;
            }

            /* Normal readable heading sizes */
            h1 {
                font-size: 20px;
                margin-bottom: 0.5rem;
            }

            h2 {
                font-size: 16px;
                margin-bottom: 0.5rem;
            }

            .text-3xl {
                font-size: 24px;
            }

            .text-2xl {
                font-size: 18px;
            }

            .text-xl {
                font-size: 16px;
            }

            /* Photo size for print */
            .w-32 {
                width: 100px;
            }

            .h-48 {
                height: 130px;
            }

            img {
                max-width: 100px;
                max-height: 130px;
            }

            /* Show print-only elements */
            .print-only {
                display: block !important;
            }
        }

        /* Hide print-only elements on screen */
        .print-only {
            display: none;
        }
    </style>
    @endpush

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 no-print rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-semibold">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 no-print rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-semibold">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Registration Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden print-card">
            <!-- Header -->
            <div class="bg-islamic-green text-white px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <x-application-logo class="h-16 w-16 mr-4" />
                        <div>
                            <h1 class="text-2xl font-bold">STAI AL-FATIH</h1>
                            <p class="text-islamic-gold text-sm">Kartu Pendaftaran Mahasiswa Baru</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-islamic-gold">Tahun Akademik</div>
                        <div class="text-xl font-bold">{{ date('Y') }}/{{ date('Y') + 1 }}</div>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="bg-gray-50 px-8 py-4 border-b no-print">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-600">Status Pendaftaran:</span>
                        @php
                            $statusConfig = [
                                'draft' => ['bg' => 'bg-gray-500', 'text' => 'Draft'],
                                'pending' => ['bg' => 'bg-yellow-500', 'text' => 'Menunggu Verifikasi'],
                                'verified' => ['bg' => 'bg-blue-500', 'text' => 'Terverifikasi'],
                                'accepted' => ['bg' => 'bg-green-500', 'text' => 'Diterima'],
                                'rejected' => ['bg' => 'bg-red-500', 'text' => 'Tidak Diterima'],
                            ];
                            $status = $statusConfig[$pendaftar->status] ?? ['bg' => 'bg-gray-500', 'text' => ucfirst($pendaftar->status)];
                        @endphp
                        <span class="ml-2 inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold text-white {{ $status['bg'] }}">
                            {{ $status['text'] }}
                        </span>
                    </div>
                    <button onclick="window.print()" class="bg-islamic-green hover:bg-islamic-green-light text-white font-semibold py-2 px-6 rounded-lg transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak Kartu
                    </button>
                </div>
            </div>

            <div class="p-8">
                <!-- Registration Number & Photo -->
                <div class="flex flex-col md:flex-row gap-6 mb-8 pb-8 border-b">
                    <div class="flex-1">
                        <div class="bg-islamic-cream border-2 border-islamic-gold rounded-lg p-6">
                            <div class="text-sm text-gray-600 mb-1">Nomor Pendaftaran</div>
                            <div class="text-3xl font-bold text-islamic-green">{{ $pendaftar->nomor_pendaftaran }}</div>
                            <div class="text-xs text-gray-500 mt-2">Simpan nomor ini untuk keperluan administrasi</div>
                        </div>
                    </div>
                    @if($pendaftar->foto_url)
                        <div class="flex-shrink-0">
                            <div class="text-center">
                                <div class="relative">
                                    <img id="pendaftar-foto"
                                         src="{{ $pendaftar->foto_url }}"
                                         alt="Foto {{ $pendaftar->nama }}"
                                         class="w-32 h-48 object-cover border-4 border-islamic-green rounded-lg shadow-lg mx-auto">
                                    <!-- Fallback: Link to view in Google Drive if image fails -->
                                    <div id="foto-fallback" style="display:none;" class="w-32 h-48 flex flex-col items-center justify-center border-4 border-islamic-green rounded-lg bg-gray-50 mx-auto">
                                        <svg class="w-12 h-12 text-islamic-green mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="{{ $pendaftar->google_drive_link ?? '#' }}"
                                           target="_blank"
                                           class="text-xs text-islamic-green hover:underline px-2 text-center no-print">
                                            Lihat Foto di Google Drive
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1 print-only">Lihat di sistem</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-2">Pas Foto 4x6</div>
                            </div>
                        </div>

                        <script>
                            // Handle image load error with detailed logging
                            document.getElementById('pendaftar-foto').onerror = function() {
                                console.error('Failed to load image from:', this.src);
                                console.log('Original Google Drive link:', '{{ $pendaftar->google_drive_link ?? "N/A" }}');
                                this.style.display = 'none';
                                document.getElementById('foto-fallback').style.display = 'flex';
                            };

                            document.getElementById('pendaftar-foto').onload = function() {
                                console.log('✅ Image loaded successfully from:', this.src);
                            };
                        </script>
                    @endif
                </div>

                <!-- Data Pribadi -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-islamic-green mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Data Pribadi
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 font-medium">Nama Lengkap:</span>
                            <p class="text-gray-900 font-semibold">{{ $pendaftar->nama }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">NIK:</span>
                            <p class="text-gray-900">{{ $pendaftar->nik }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Jenis Kelamin:</span>
                            <p class="text-gray-900">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Tempat, Tanggal Lahir:</span>
                            <p class="text-gray-900">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir->format('d F Y') }}</p>
                        </div>
                        <div class="print-hide">
                            <span class="text-gray-600 font-medium">Agama:</span>
                            <p class="text-gray-900">{{ $pendaftar->agama }}</p>
                        </div>
                        <div class="print-hide">
                            <span class="text-gray-600 font-medium">Email:</span>
                            <p class="text-gray-900">{{ $pendaftar->email }}</p>
                        </div>
                        <div class="print-hide">
                            <span class="text-gray-600 font-medium">No. Telepon:</span>
                            <p class="text-gray-900">{{ $pendaftar->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Data Alamat -->
                <div class="mb-8 print-hide">
                    <h2 class="text-xl font-bold text-islamic-green mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Alamat
                    </h2>
                    <div class="text-sm">
                        <p class="text-gray-900 mb-2">{{ $pendaftar->alamat }}</p>
                        <p class="text-gray-600">
                            Kel/Desa {{ $pendaftar->kelurahan }}, Kec. {{ $pendaftar->kecamatan }},
                            {{ $pendaftar->kota_kabupaten }}, {{ $pendaftar->provinsi }}
                            @if($pendaftar->kode_pos) - {{ $pendaftar->kode_pos }}@endif
                        </p>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div class="mb-8 print-hide">
                    <h2 class="text-xl font-bold text-islamic-green mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Data Orang Tua
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 font-medium">Nama Ayah:</span>
                            <p class="text-gray-900">{{ $pendaftar->nama_ayah }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Pekerjaan Ayah:</span>
                            <p class="text-gray-900">{{ $pendaftar->pekerjaan_ayah }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Nama Ibu:</span>
                            <p class="text-gray-900">{{ $pendaftar->nama_ibu }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Pekerjaan Ibu:</span>
                            <p class="text-gray-900">{{ $pendaftar->pekerjaan_ibu }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">No. Telepon Orang Tua:</span>
                            <p class="text-gray-900">{{ $pendaftar->phone_orangtua }}</p>
                        </div>
                    </div>
                </div>

                <!-- Data Pendidikan -->
                <div class="mb-8 print-hide">
                    <h2 class="text-xl font-bold text-islamic-green mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Data Pendidikan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 font-medium">Asal Sekolah:</span>
                            <p class="text-gray-900">{{ $pendaftar->asal_sekolah }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Tahun Lulus:</span>
                            <p class="text-gray-900">{{ $pendaftar->tahun_lulus }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Nilai Rata-rata:</span>
                            <p class="text-gray-900">{{ number_format($pendaftar->nilai_rata_rata, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jalur & Program Studi -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-islamic-green mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Jalur Seleksi & Program Studi
                    </h2>
                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="text-gray-600 font-medium">Jalur Seleksi:</span>
                            <p class="text-gray-900 font-semibold">{{ $pendaftar->jalurSeleksi->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 font-medium">Program Studi Pilihan 1:</span>
                            <p class="text-gray-900 font-semibold">{{ $pendaftar->programStudiPilihan1->nama_prodi ?? '-' }} ({{ $pendaftar->programStudiPilihan1->jenjang ?? '-' }})</p>
                        </div>
                        @if($pendaftar->program_studi_pilihan_2)
                            <div>
                                <span class="text-gray-600 font-medium">Program Studi Pilihan 2:</span>
                                <p class="text-gray-900">{{ $pendaftar->programStudiPilihan2->nama_prodi ?? '-' }} ({{ $pendaftar->programStudiPilihan2->jenjang ?? '-' }})</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Keterangan (if any) -->
                @if($pendaftar->keterangan)
                    <div class="mb-8 print-hide">
                        <h2 class="text-xl font-bold text-islamic-green mb-4">Keterangan</h2>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                            <p class="text-gray-700 text-sm">{{ $pendaftar->keterangan }}</p>
                        </div>
                    </div>
                @endif

                <!-- Footer Info -->
                <div class="mt-8 pt-6 border-t border-gray-200 text-center text-xs text-gray-500 print-hide">
                    <p>Kartu pendaftaran ini dicetak pada {{ now()->format('d F Y, H:i') }} WIB</p>
                    <p class="mt-1">Untuk informasi lebih lanjut hubungi: (021) 1234-5678 atau email ke info@staialfatih.ac.id</p>
                </div>
            </div>
        </div>

        <!-- Upload Bukti Pembayaran Section -->
        @if($pendaftar->status === 'pending')
            @php
                $hasPendingPayment = $pendaftar->pembayaranPendaftarans()->where('status', 'pending')->exists();
                $hasVerifiedPayment = $pendaftar->pembayaranPendaftarans()->where('status', 'verified')->exists();
            @endphp

            @if(!$hasVerifiedPayment)
                <div class="mt-8 bg-white rounded-2xl shadow-2xl overflow-hidden no-print" x-data="{ uploading: false }">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-islamic-green to-green-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Upload Bukti Pembayaran
                        </h3>
                        <p class="text-islamic-gold text-sm mt-1">Biaya Pendaftaran: <span class="font-bold">Rp {{ number_format($pendaftar->jalurSeleksi->biaya_pendaftaran ?? 0, 0, ',', '.') }}</span></p>
                    </div>

                    <div class="p-6">
                        @if($hasPendingPayment)
                            <!-- Already Uploaded -->
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-yellow-800">Bukti Pembayaran Sedang Diverifikasi</h4>
                                        <p class="text-sm text-yellow-700 mt-1">Bukti pembayaran Anda sudah diterima dan sedang dalam proses verifikasi oleh admin. Mohon tunggu maksimal 2x24 jam.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Upload Form -->
                            <form action="{{ route('public.spmb.upload-payment', $pendaftar->id) }}" method="POST" enctype="multipart/form-data" @submit="uploading = true">
                                @csrf

                                <!-- Informasi Rekening -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        Informasi Transfer
                                    </h4>
                                    <div class="space-y-2 text-sm text-blue-900">
                                        <div class="flex justify-between">
                                            <span class="font-medium">Bank:</span>
                                            <span class="font-bold">BCA</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">No. Rekening:</span>
                                            <span class="font-bold">1234567890</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">Atas Nama:</span>
                                            <span class="font-bold">STAI AL-FATIH</span>
                                        </div>
                                        <div class="flex justify-between border-t border-blue-300 pt-2 mt-2">
                                            <span class="font-medium">Jumlah Transfer:</span>
                                            <span class="font-bold text-lg text-islamic-green">Rp {{ number_format($pendaftar->jalurSeleksi->biaya_pendaftaran ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Bukti -->
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Bukti Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file"
                                           name="bukti_pembayaran"
                                           accept="image/*,.pdf"
                                           required
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-islamic-green">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau PDF (Maks. 2MB)</p>
                                </div>

                                <!-- Metode Pembayaran -->
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Metode Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select name="metode_pembayaran" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-islamic-green">
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="va">Virtual Account</option>
                                        <option value="tunai">Tunai</option>
                                    </select>
                                </div>

                                <!-- Nomor Referensi (Optional) -->
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Referensi / Transaksi (Opsional)
                                    </label>
                                    <input type="text"
                                           name="nomor_referensi"
                                           placeholder="Contoh: TRX123456789"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-islamic-green">
                                    <p class="text-xs text-gray-500 mt-1">Nomor referensi dari bank (jika ada)</p>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit"
                                        :disabled="uploading"
                                        :class="uploading ? 'bg-gray-400 cursor-not-allowed' : 'bg-islamic-green hover:bg-green-700'"
                                        class="w-full text-white font-bold py-3 px-6 rounded-lg transition flex items-center justify-center">
                                    <span x-show="!uploading">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Upload Bukti Pembayaran
                                    </span>
                                    <span x-show="uploading">
                                        <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sedang Upload...
                                    </span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        @endif

        <!-- Action Buttons (No Print) -->
        <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center no-print">
            <a href="{{ route('public.spmb.index') }}"
               class="bg-islamic-green hover:bg-islamic-green-light text-white font-semibold py-3 px-6 rounded-lg transition text-center">
                Kembali ke Beranda
            </a>
            <a href="{{ route('public.spmb.check') }}"
               class="bg-white hover:bg-gray-100 text-islamic-green border-2 border-islamic-green font-semibold py-3 px-6 rounded-lg transition text-center">
                Cek Status Lain
            </a>
        </div>

        <!-- Next Steps Info -->
        @if($pendaftar->status === 'pending')
            <div class="mt-8 bg-white rounded-2xl shadow-2xl p-8 no-print">
                <h3 class="text-2xl font-bold text-islamic-green mb-4">Langkah Selanjutnya</h3>
                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex items-start">
                        <div class="bg-islamic-green text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">1</div>
                        <p>Lakukan pembayaran biaya pendaftaran sebesar <strong>Rp {{ number_format($pendaftar->jalurSeleksi->biaya_pendaftaran ?? 0, 0, ',', '.') }}</strong></p>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-islamic-green text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">2</div>
                        <p>Upload bukti pembayaran melalui sistem atau kirim ke WhatsApp admin</p>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-islamic-green text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">3</div>
                        <p>Tunggu verifikasi dari admin (maksimal 2x24 jam)</p>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-islamic-green text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">4</div>
                        <p>Pantau terus status pendaftaran Anda melalui nomor pendaftaran</p>
                    </div>
                </div>
            </div>
        @elseif($pendaftar->status === 'verified')
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg no-print">
                <h3 class="text-lg font-bold text-blue-800 mb-2">Pendaftaran Terverifikasi</h3>
                <p class="text-blue-700 text-sm">Pendaftaran Anda telah diverifikasi. Silakan tunggu jadwal ujian/seleksi yang akan diumumkan melalui email atau website resmi.</p>
            </div>
        @elseif($pendaftar->status === 'accepted')
            <div class="mt-8 bg-green-50 border-l-4 border-green-500 p-6 rounded-lg no-print">
                <h3 class="text-lg font-bold text-green-800 mb-2">Selamat! Anda Diterima</h3>
                <p class="text-green-700 text-sm">Anda diterima di STAI AL-FATIH. Silakan lakukan daftar ulang sesuai jadwal yang telah ditentukan. Informasi lebih lanjut akan dikirimkan melalui email.</p>
            </div>
        @elseif($pendaftar->status === 'rejected')
            <div class="mt-8 bg-red-50 border-l-4 border-red-500 p-6 rounded-lg no-print">
                <h3 class="text-lg font-bold text-red-800 mb-2">Informasi Seleksi</h3>
                <p class="text-red-700 text-sm">Mohon maaf, Anda belum dapat diterima pada seleksi ini. Tetap semangat dan jangan menyerah untuk menggapai impian Anda!</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Clear localStorage SPMB draft on successful registration
        // This page only loads after successful form submission
        try {
            const savedDraft = localStorage.getItem('spmb_draft');
            if (savedDraft) {
                localStorage.removeItem('spmb_draft');
                console.log('✅ Cleared localStorage SPMB draft after successful registration');
            }
        } catch (e) {
            console.error('Failed to clear localStorage:', e);
        }
    </script>
    @endpush
</x-layouts.public>
