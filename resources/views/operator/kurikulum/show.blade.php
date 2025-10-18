@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Detail Kurikulum" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('admin.kurikulum.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.kurikulum.edit', $kurikulum->id) }}" class="px-4 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#b8941f] transition">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-pdf mr-2"></i>
                Export PDF
            </button>
        </div>
    </div>

    <!-- Main Info Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden islamic-border">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4 islamic-pattern">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-[#D4AF37] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-book text-3xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $kurikulum->nama }}</h2>
                        <p class="text-emerald-50">{{ $kurikulum->programStudi->nama ?? '-' }}</p>
                    </div>
                </div>
                <div>
                    @if($kurikulum->is_active)
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

        <!-- Card Body -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Detail Information -->
                <div class="md:col-span-2 space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Kurikulum
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Program Studi</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">
                                {{ $kurikulum->programStudi->nama ?? '-' }}
                                <span class="text-sm text-gray-600">({{ $kurikulum->programStudi->kode ?? '-' }})</span>
                            </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Nama Kurikulum</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $kurikulum->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Mulai</p>
                            <p class="text-lg font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt text-[#D4AF37] mr-1"></i>
                                {{ $kurikulum->tahun_mulai }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tahun Selesai</p>
                            <p class="text-lg font-semibold text-gray-700">
                                <i class="fas fa-calendar-check text-[#D4AF37] mr-1"></i>
                                {{ $kurikulum->tahun_selesai }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total SKS</p>
                            <p class="text-lg font-semibold text-indigo-600">
                                <i class="fas fa-book-open mr-1"></i>
                                {{ $kurikulum->total_sks }} SKS
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-lg font-semibold {{ $kurikulum->is_active ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $kurikulum->is_active ? 'Aktif' : 'Tidak Aktif' }}
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
                                <p class="text-sm text-blue-600">Total Mata Kuliah</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $kurikulum->mata_kuliah_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-book-open text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600">Total SKS Tercatat</p>
                                <p class="text-3xl font-bold text-green-700">{{ $kurikulum->total_sks }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-calculator text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#F4E5C3] to-[#D4AF37] rounded-lg p-4 border border-[#D4AF37]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#2D5F3F]">Periode Berlaku</p>
                                <p class="text-xl font-bold text-[#2D5F3F]">{{ $kurikulum->tahun_selesai - $kurikulum->tahun_mulai }} Tahun</p>
                            </div>
                            <div class="w-12 h-12 bg-[#2D5F3F] rounded-full flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mata Kuliah by Semester -->
    @if(isset($mataKuliahsBySemester) && count($mataKuliahsBySemester) > 0)
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-list mr-2"></i>
                Daftar Mata Kuliah per Semester
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4" x-data="{ openSemester: 1 }">
                @foreach($mataKuliahsBySemester as $semester => $mataKuliahs)
                <div class="border-2 border-[#D4AF37] rounded-lg overflow-hidden">
                    <!-- Semester Header -->
                    <button
                        @click="openSemester = openSemester === {{ $semester }} ? null : {{ $semester }}"
                        class="w-full bg-gradient-to-r from-[#F4E5C3] to-white px-4 py-3 flex items-center justify-between hover:from-[#D4AF37] hover:to-[#F4E5C3] transition"
                    >
                        <div class="flex items-center">
                            <span class="w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3">
                                {{ $semester }}
                            </span>
                            <div class="text-left">
                                <h4 class="font-semibold text-[#2D5F3F]">Semester {{ $semester }}</h4>
                                <p class="text-sm text-gray-600">{{ count($mataKuliahs) }} Mata Kuliah - Total SKS: {{ collect($mataKuliahs)->sum('sks') }}</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="openSemester === {{ $semester }} ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Mata Kuliah List -->
                    <div
                        x-show="openSemester === {{ $semester }}"
                        x-transition
                        class="bg-white"
                        style="display: none;"
                    >
                        <div class="p-4 space-y-2">
                            @foreach($mataKuliahs as $mk)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-[#F4E5C3] hover:bg-opacity-30 transition border border-gray-200">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $mk->nama }}</p>
                                    <p class="text-sm text-gray-600">{{ $mk->kode }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="px-3 py-1 {{ $mk->jenis == 'Wajib' ? 'bg-green-100 text-green-800' : 'bg-[#F4E5C3] text-[#2D5F3F]' }} text-xs font-semibold rounded-full">
                                        {{ $mk->jenis }}
                                    </span>
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                        {{ $mk->sks }} SKS
                                    </span>
                                    <a href="{{ route('admin.mata-kuliah.show', $mk->id) }}" class="text-[#D4AF37] hover:text-[#b8941f]">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
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
        <p class="text-white text-lg italic mb-2">"Menuntut ilmu adalah kewajiban bagi setiap Muslim"</p>
        <p class="text-emerald-50 text-sm">- HR. Ibnu Majah</p>
        <i class="fas fa-quote-right text-[#D4AF37] text-2xl mt-2"></i>
    </div>
</div>
@endsection
