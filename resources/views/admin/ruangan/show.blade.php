@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Detail Ruangan" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('admin.ruangan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.ruangan.edit', $ruangan->id) }}" class="px-4 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#b8941f] transition">
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
                        <i class="fas fa-door-open text-3xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $ruangan->nama }}</h2>
                        <p class="text-emerald-50">{{ $ruangan->kode }}</p>
                    </div>
                </div>
                <div>
                    @if($ruangan->is_available)
                        <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>
                            Tersedia
                        </span>
                    @else
                        <span class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-times-circle mr-1"></i>
                            Tidak Tersedia
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
                        Informasi Ruangan
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Kode Ruangan</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $ruangan->kode }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kapasitas</p>
                            <p class="text-lg font-semibold text-purple-600">
                                <i class="fas fa-users mr-1"></i>
                                {{ $ruangan->kapasitas }} Orang
                            </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Nama Ruangan</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $ruangan->nama }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500 mb-2">Fasilitas</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $fasilitas = is_array($ruangan->fasilitas) ? $ruangan->fasilitas : explode(',', $ruangan->fasilitas ?? '');
                                @endphp
                                @forelse($fasilitas as $f)
                                    @php
                                        $icons = [
                                            'Proyektor' => 'fa-video',
                                            'AC' => 'fa-snowflake',
                                            'Papan Tulis' => 'fa-chalkboard',
                                            'Wifi' => 'fa-wifi',
                                            'Lab Komputer' => 'fa-desktop',
                                            'Sound System' => 'fa-volume-up',
                                        ];
                                        $icon = $icons[trim($f)] ?? 'fa-check';
                                    @endphp
                                    <span class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">
                                        <i class="fas {{ $icon }} mr-1"></i>
                                        {{ trim($f) }}
                                    </span>
                                @empty
                                    <span class="text-gray-500">Tidak ada fasilitas</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Capacity Indicator -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Kapasitas
                    </h3>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200 text-center">
                        <div class="w-32 h-32 mx-auto bg-purple-500 rounded-full flex items-center justify-center mb-4">
                            <div class="text-center">
                                <i class="fas fa-users text-white text-3xl mb-2"></i>
                                <p class="text-4xl font-bold text-white">{{ $ruangan->kapasitas }}</p>
                            </div>
                        </div>
                        <p class="text-purple-700 font-semibold">Maksimal Mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule -->
    @if(isset($weeklySchedule) && count($weeklySchedule) > 0)
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-calendar-week mr-2"></i>
                Jadwal Penggunaan Ruangan
            </h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Hari</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Waktu</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Mata Kuliah</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Dosen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($weeklySchedule as $schedule)
                        <tr class="hover:bg-[#F4E5C3] hover:bg-opacity-30">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $schedule->hari }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->jam_mulai }} - {{ $schedule->jam_selesai }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $schedule->mataKuliah->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->dosen->nama ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6 text-center">
        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">Belum ada jadwal untuk ruangan ini</p>
    </div>
    @endif

    <!-- Availability Calendar -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-calendar-check mr-2"></i>
            Ketersediaan Minggu Ini
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @php
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                $usedDays = isset($weeklySchedule) ? $weeklySchedule->pluck('hari')->toArray() : [];
            @endphp
            @foreach($days as $day)
                @php
                    $isUsed = in_array($day, $usedDays);
                @endphp
                <div class="border-2 {{ $isUsed ? 'border-red-300 bg-red-50' : 'border-green-300 bg-green-50' }} rounded-lg p-4 text-center">
                    <i class="fas {{ $isUsed ? 'fa-times-circle text-red-500' : 'fa-check-circle text-green-500' }} text-2xl mb-2"></i>
                    <p class="font-semibold text-gray-700">{{ $day }}</p>
                    <p class="text-xs {{ $isUsed ? 'text-red-600' : 'text-green-600' }}">
                        {{ $isUsed ? 'Terpakai' : 'Tersedia' }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 text-center islamic-pattern">
        <i class="fas fa-quote-left text-[#D4AF37] text-2xl mb-2"></i>
        <p class="text-white text-lg italic mb-2">"Kebersihan adalah sebagian dari iman"</p>
        <p class="text-emerald-50 text-sm">- HR. Muslim</p>
        <i class="fas fa-quote-right text-[#D4AF37] text-2xl mt-2"></i>
    </div>
</div>
@endsection
