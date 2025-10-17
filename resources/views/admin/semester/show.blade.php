@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Detail Semester" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('admin.semester.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.semester.edit', $semester->id) }}" class="px-4 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#b8941f] transition">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
        </div>
    </div>

    <!-- Main Info Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden islamic-border">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4 islamic-pattern">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-[#D4AF37] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-alt text-3xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $semester->nama_semester }}</h2>
                        <p class="text-[#F4E5C3]">Tahun Akademik {{ $semester->tahun_akademik }}</p>
                    </div>
                </div>
                <div>
                    @if($semester->is_active)
                        <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>
                            Aktif
                        </span>
                    @else
                        <span class="px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-times-circle mr-1"></i>
                            Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Detail Information -->
                <div class="md:col-span-2 space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Semester
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Semester</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $semester->nama_semester }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Semester</p>
                            <p class="text-lg font-semibold {{ $semester->jenis == 'ganjil' ? 'text-blue-600' : ($semester->jenis == 'genap' ? 'text-purple-600' : 'text-orange-600') }}">
                                {{ ucfirst($semester->jenis) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Akademik</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $semester->tahun_akademik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Mulai</p>
                            <p class="text-lg font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt text-[#D4AF37] mr-1"></i>
                                {{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Selesai</p>
                            <p class="text-lg font-semibold text-gray-700">
                                <i class="fas fa-calendar-check text-[#D4AF37] mr-1"></i>
                                {{ \Carbon\Carbon::parse($semester->tanggal_selesai)->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Durasi</p>
                            <p class="text-lg font-semibold text-indigo-600">
                                <i class="fas fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($semester->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($semester->tanggal_selesai)) }} Hari
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-lg font-semibold {{ $semester->is_active ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $semester->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Statistik
                    </h3>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600">Total Jadwal</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $semester->jadwal_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-week text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600">Mahasiswa Aktif</p>
                                <p class="text-3xl font-bold text-green-700">{{ $semester->mahasiswa_aktif_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-graduate text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#F4E5C3] to-[#D4AF37] rounded-lg p-4 border border-[#D4AF37]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#2D5F3F]">Total Kelas</p>
                                <p class="text-3xl font-bold text-[#2D5F3F]">{{ $semester->kelas_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-[#2D5F3F] rounded-full flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline View -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-6">
            <i class="fas fa-stream mr-2"></i>
            Timeline Semester
        </h3>
        <div class="relative">
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-[#D4AF37]"></div>

            <div class="mb-8 flex items-start">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-4 relative z-10">
                    <i class="fas fa-play text-2xl"></i>
                </div>
                <div class="flex-1 bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                    <p class="font-semibold text-green-800">Mulai Semester</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('l, d F Y') }}</p>
                </div>
            </div>

            <div class="mb-8 flex items-start">
                <div class="w-16 h-16 bg-[#D4AF37] rounded-full flex items-center justify-center text-white font-bold mr-4 relative z-10">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
                <div class="flex-1 bg-[#F4E5C3] border-l-4 border-[#D4AF37] rounded-lg p-4">
                    <p class="font-semibold text-[#2D5F3F]">Hari Ini</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
                    @php
                        $today = \Carbon\Carbon::now();
                        $start = \Carbon\Carbon::parse($semester->tanggal_mulai);
                        $end = \Carbon\Carbon::parse($semester->tanggal_selesai);
                        $total = $start->diffInDays($end);
                        $elapsed = $start->diffInDays($today);
                        $percentage = $total > 0 ? min(100, ($elapsed / $total) * 100) : 0;
                    @endphp
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#2D5F3F] h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">{{ number_format($percentage, 1) }}% dari semester telah berlalu</p>
                    </div>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center text-white font-bold mr-4 relative z-10">
                    <i class="fas fa-stop text-2xl"></i>
                </div>
                <div class="flex-1 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                    <p class="font-semibold text-red-800">Selesai Semester</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($semester->tanggal_selesai)->format('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Classes -->
    @if(isset($activeClasses) && count($activeClasses) > 0)
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-chalkboard mr-2"></i>
                Kelas Aktif di Semester Ini
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activeClasses as $class)
                <div class="border-2 border-[#D4AF37] rounded-lg p-4 hover:shadow-lg transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-[#2D5F3F]">{{ $class->mataKuliah->nama ?? '-' }}</p>
                            <p class="text-sm text-gray-600">Kelas {{ $class->nama }}</p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user-tie mr-1"></i>
                                {{ $class->dosen->nama ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>
                                {{ $class->students_count ?? 0 }} Mahasiswa
                            </p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                            {{ $class->mataKuliah->sks ?? 0 }} SKS
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Islamic Quote -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 text-center islamic-pattern">
        <i class="fas fa-quote-left text-[#D4AF37] text-2xl mb-2"></i>
        <p class="text-white text-lg italic mb-2">"Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia"</p>
        <p class="text-[#F4E5C3] text-sm">- HR. Ahmad dan Thabrani</p>
        <i class="fas fa-quote-right text-[#D4AF37] text-2xl mt-2"></i>
    </div>
</div>
@endsection
