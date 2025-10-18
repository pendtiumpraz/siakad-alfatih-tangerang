@extends('layouts.mahasiswa')

@section('title', 'Pembayaran')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Pembayaran</span>
            </h1>
            <p class="text-gray-600 mt-1">Riwayat dan status pembayaran mahasiswa</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card-islamic p-6 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <p class="text-sm opacity-90 mb-1">Total Tagihan</p>
            <p class="text-3xl font-bold mb-2">Rp 8.5 Jt</p>
            <p class="text-xs opacity-90">Semester 5</p>
        </div>
        <div class="card-islamic p-6 bg-gradient-to-br from-green-500 to-green-600 text-white">
            <p class="text-sm opacity-90 mb-1">Sudah Dibayar</p>
            <p class="text-3xl font-bold mb-2">Rp 6.5 Jt</p>
            <p class="text-xs opacity-90">76.5%</p>
        </div>
        <div class="card-islamic p-6 bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
            <p class="text-sm opacity-90 mb-1">Menunggu</p>
            <p class="text-3xl font-bold mb-2">Rp 500 Rb</p>
            <p class="text-xs opacity-90">1 Tagihan</p>
        </div>
        <div class="card-islamic p-6 bg-gradient-to-br from-red-500 to-red-600 text-white">
            <p class="text-sm opacity-90 mb-1">Jatuh Tempo</p>
            <p class="text-3xl font-bold mb-2">Rp 1.5 Jt</p>
            <p class="text-xs opacity-90">1 Tagihan</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-islamic p-6">
        <form method="GET" action="{{ route('mahasiswa.pembayaran.index') }}" class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-semibold text-gray-700">Filter Status:</label>
                <select name="status" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="flex items-center space-x-4">
                <label class="text-sm font-semibold text-gray-700">Semester:</label>
                <select name="semester_id" class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition" onchange="this.form.submit()">
                    <option value="">Semua Semester</option>
                    @foreach($semesters ?? [] as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->tahun_akademik }} - {{ ucfirst($semester->jenis) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="px-4 py-2 bg-[#4A7C59] text-white rounded-lg hover:bg-[#3d6849] transition font-semibold">
                    Filter
                </button>
                <a href="{{ route('mahasiswa.pembayaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Overdue Payment Alert -->
    <div class="card-islamic p-6 bg-red-50 border-l-4 border-red-500">
        <div class="flex items-start space-x-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-red-800 mb-2">Peringatan: Tagihan Jatuh Tempo!</h3>
                <p class="text-sm text-red-700">Anda memiliki 1 tagihan yang telah melewati batas waktu pembayaran. Segera lakukan pembayaran untuk menghindari sanksi akademik.</p>
            </div>
        </div>
    </div>

    <!-- Payment Cards -->
    <div class="space-y-4">
        <!-- Overdue Payment -->
        <div class="card-islamic p-6 border-l-4 border-red-500 hover:shadow-xl transition">
            <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-start space-x-4 flex-1">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800">Pembayaran UTS Semester Genap</h3>
                            <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">
                                JATUH TEMPO
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-red-600 mb-2">Rp 1.500.000</p>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Jatuh Tempo</p>
                                <p class="font-semibold text-red-600">30 Oktober 2025</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Keterangan</p>
                                <p class="font-semibold text-gray-800">UTS Semester 5</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('mahasiswa.pembayaran.show', 1) }}"
                       class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition text-center">
                        Bayar Sekarang
                    </a>
                    <a href="{{ route('mahasiswa.pembayaran.show', 1) }}"
                       class="border-2 border-gray-300 hover:border-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold transition text-center">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Payment -->
        <div class="card-islamic p-6 border-l-4 border-yellow-500 hover:shadow-xl transition">
            <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-start space-x-4 flex-1">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800">SPP Bulan November</h3>
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">
                                MENUNGGU
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-yellow-600 mb-2">Rp 500.000</p>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500">Jatuh Tempo</p>
                                <p class="font-semibold text-gray-800">10 November 2025</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Keterangan</p>
                                <p class="font-semibold text-gray-800">SPP Reguler</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('mahasiswa.pembayaran.show', 2) }}"
                       class="bg-[#4A7C59] hover:bg-[#3d6849] text-white px-6 py-3 rounded-lg font-semibold transition text-center">
                        Bayar Sekarang
                    </a>
                    <a href="{{ route('mahasiswa.pembayaran.show', 2) }}"
                       class="border-2 border-gray-300 hover:border-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold transition text-center">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Paid Payments -->
        <div class="card-islamic p-6 border-l-4 border-green-500">
            <div class="flex items-start space-x-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-800">SPP Bulan Oktober</h3>
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                            LUNAS
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-green-600 mb-2">Rp 500.000</p>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Tanggal Bayar</p>
                            <p class="font-semibold text-gray-800">5 Oktober 2025</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Metode</p>
                            <p class="font-semibold text-gray-800">Transfer Bank</p>
                        </div>
                        <div>
                            <a href="{{ route('mahasiswa.pembayaran.show', 3) }}"
                               class="text-[#4A7C59] hover:text-[#D4AF37] font-semibold text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-islamic p-6 border-l-4 border-green-500">
            <div class="flex items-start space-x-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-800">Registrasi Ulang Semester 5</h3>
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                            LUNAS
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-green-600 mb-2">Rp 1.000.000</p>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Tanggal Bayar</p>
                            <p class="font-semibold text-gray-800">20 September 2025</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Metode</p>
                            <p class="font-semibold text-gray-800">Transfer Bank</p>
                        </div>
                        <div>
                            <a href="{{ route('mahasiswa.pembayaran.show', 4) }}"
                               class="text-[#4A7C59] hover:text-[#D4AF37] font-semibold text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Instructions -->
    <div class="card-islamic p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
            <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Informasi Pembayaran</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-bold text-gray-800 mb-3">Rekening Transfer:</h4>
                <div class="space-y-3">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Bank BNI</p>
                        <p class="text-lg font-bold text-gray-800">1234567890</p>
                        <p class="text-sm text-gray-600">a.n. STAI AL-FATIH</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Bank Mandiri</p>
                        <p class="text-lg font-bold text-gray-800">0987654321</p>
                        <p class="text-sm text-gray-600">a.n. STAI AL-FATIH</p>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 mb-3">Cara Pembayaran:</h4>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                    <li>Transfer ke salah satu rekening yang tersedia</li>
                    <li>Simpan bukti transfer</li>
                    <li>Upload bukti transfer melalui sistem</li>
                    <li>Tunggu verifikasi dari admin (maks. 2x24 jam)</li>
                    <li>Status pembayaran akan diupdate setelah verifikasi</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            وَأَوْفُوا بِالْعَهْدِ إِنَّ الْعَهْدَ كَانَ مَسْئُولًا
        </p>
        <p class="text-gray-600 italic text-sm">
            Dan penuhilah janji, sesungguhnya janji itu pasti diminta pertanggungjawaban
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. Al-Isra: 34)</p>
    </div>
</div>
@endsection
