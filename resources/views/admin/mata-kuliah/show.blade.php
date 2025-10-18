@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Detail Mata Kuliah" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('admin.mata-kuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.mata-kuliah.edit', $mataKuliah->id) }}" class="px-4 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#b8941f] transition">
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
                        <i class="fas fa-book-open text-3xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $mataKuliah->nama }}</h2>
                        <p class="text-emerald-50">{{ $mataKuliah->kode }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @if($mataKuliah->jenis == 'Wajib')
                        <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-star mr-1"></i>Wajib
                        </span>
                    @else
                        <span class="px-4 py-2 bg-[#D4AF37] text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-list mr-1"></i>Pilihan
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
                        Informasi Mata Kuliah
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Kurikulum</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $mataKuliah->kurikulum->nama ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $mataKuliah->kurikulum->programStudi->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kode Mata Kuliah</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $mataKuliah->kode }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">SKS</p>
                            <p class="text-lg font-semibold text-indigo-600">
                                <i class="fas fa-book-open mr-1"></i>
                                {{ $mataKuliah->sks }} SKS
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Semester</p>
                            <p class="text-lg font-semibold text-blue-600">Semester {{ $mataKuliah->semester }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis</p>
                            <p class="text-lg font-semibold {{ $mataKuliah->jenis == 'Wajib' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $mataKuliah->jenis }}
                            </p>
                        </div>
                    </div>

                    @if($mataKuliah->deskripsi)
                    <div class="mt-4">
                        <h4 class="text-md font-semibold text-[#2D5F3F] mb-2">Deskripsi</h4>
                        <p class="text-gray-700 text-justify">{{ $mataKuliah->deskripsi }}</p>
                    </div>
                    @endif
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
                                <p class="text-sm text-blue-600">Total Kelas</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $mataKuliah->kelas_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600">Total Mahasiswa</p>
                                <p class="text-3xl font-bold text-green-700">{{ $mataKuliah->students_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-graduate text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Learning Outcomes (if any) -->
    @if(isset($learningOutcomes) && count($learningOutcomes) > 0)
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-bullseye mr-2"></i>
            Capaian Pembelajaran
        </h3>
        <ul class="space-y-2">
            @foreach($learningOutcomes as $outcome)
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                <span class="text-gray-700">{{ $outcome }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Prerequisites (if any) -->
    @if(isset($prerequisites) && count($prerequisites) > 0)
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-link mr-2"></i>
            Mata Kuliah Prasyarat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($prerequisites as $prereq)
            <div class="border border-gray-300 rounded-lg p-3 hover:border-[#D4AF37] transition">
                <p class="font-semibold text-gray-800">{{ $prereq->nama }}</p>
                <p class="text-sm text-gray-600">{{ $prereq->kode }} - {{ $prereq->sks }} SKS</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Schedule List -->
    @if(isset($jadwals) && count($jadwals) > 0)
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-calendar-alt mr-2"></i>
                Jadwal Perkuliahan
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($jadwals as $jadwal)
                <div class="border-2 border-[#D4AF37] rounded-lg p-4 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-[#2D5F3F]">{{ $jadwal->kelas }}</p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user-tie mr-1"></i>{{ $jadwal->dosen->nama ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-door-open mr-1"></i>{{ $jadwal->ruangan->nama ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-700">{{ $jadwal->hari }}</p>
                            <p class="text-sm text-gray-600">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>
                        </div>
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
        <p class="text-white text-lg italic mb-2">"Barangsiapa berjalan di suatu jalan untuk mencari ilmu, maka Allah akan memudahkan baginya jalan menuju surga"</p>
        <p class="text-emerald-50 text-sm">- HR. Muslim</p>
        <i class="fas fa-quote-right text-[#D4AF37] text-2xl mt-2"></i>
    </div>
</div>
@endsection
