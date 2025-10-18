@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Ruangan</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap ruangan {{ $ruangan->nama_ruangan }}</p>
        </div>
        <a href="{{ route('dosen.ruangan.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Main Info Card -->
    <x-islamic-card>
        <div class="bg-gradient-to-r from-green-600 to-green-700 -m-6 mb-6 px-6 py-6 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $ruangan->nama_ruangan }}</h2>
                        <p class="text-green-100">{{ $ruangan->kode_ruangan }}</p>
                    </div>
                </div>
                <div>
                    @if($ruangan->is_available)
                        <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Tersedia
                        </span>
                    @else
                        <span class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-full">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Tidak Tersedia
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Detail Information -->
            <div class="md:col-span-2 space-y-4">
                <h3 class="text-lg font-semibold text-green-800 border-b-2 border-yellow-400 pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Ruangan
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Kode Ruangan</p>
                        <p class="text-lg font-semibold text-green-700">{{ $ruangan->kode_ruangan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kapasitas</p>
                        <p class="text-lg font-semibold text-purple-600">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ $ruangan->kapasitas }} Orang
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Nama Ruangan</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $ruangan->nama_ruangan }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500 mb-2">Fasilitas</p>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $fasilitas = is_string($ruangan->fasilitas)
                                    ? json_decode($ruangan->fasilitas, true) ?? explode(',', $ruangan->fasilitas)
                                    : (is_array($ruangan->fasilitas) ? $ruangan->fasilitas : []);

                                $icons = [
                                    'Proyektor' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
                                    'AC' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
                                    'Papan Tulis' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                    'Wifi' => 'M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0',
                                    'Lab Komputer' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                    'Sound System' => 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z',
                                ];
                            @endphp
                            @forelse($fasilitas as $f)
                                @php
                                    $fasilitasName = trim($f);
                                    $iconPath = $icons[$fasilitasName] ?? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                                @endphp
                                <span class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
                                    </svg>
                                    {{ $fasilitasName }}
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
                <h3 class="text-lg font-semibold text-green-800 border-b-2 border-yellow-400 pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Kapasitas
                </h3>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200 text-center">
                    <div class="w-32 h-32 mx-auto bg-purple-500 rounded-full flex items-center justify-center mb-4">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-white mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <p class="text-4xl font-bold text-white">{{ $ruangan->kapasitas }}</p>
                        </div>
                    </div>
                    <p class="text-purple-700 font-semibold">Maksimal Mahasiswa</p>
                </div>
            </div>
        </div>
    </x-islamic-card>

    <!-- Weekly Schedule -->
    @if(isset($weeklySchedule) && count($weeklySchedule) > 0)
    <x-islamic-card title="Jadwal Penggunaan Ruangan">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-green-200 border border-green-200 rounded-lg">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Mata Kuliah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Dosen</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Kelas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($weeklySchedule as $schedule)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $schedule->hari }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->jam_mulai }} - {{ $schedule->jam_selesai }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $schedule->mataKuliah->nama_mk ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->dosen->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $schedule->kelas ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-islamic-card>
    @else
    <x-islamic-card>
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500">Belum ada jadwal untuk ruangan ini</p>
        </div>
    </x-islamic-card>
    @endif

    <!-- Availability Calendar -->
    <x-islamic-card title="Ketersediaan Minggu Ini">
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
                    <svg class="w-8 h-8 mx-auto mb-2 {{ $isUsed ? 'text-red-500' : 'text-green-500' }}" fill="currentColor" viewBox="0 0 20 20">
                        @if($isUsed)
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        @else
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        @endif
                    </svg>
                    <p class="font-semibold text-gray-700">{{ $day }}</p>
                    <p class="text-xs {{ $isUsed ? 'text-red-600' : 'text-green-600' }}">
                        {{ $isUsed ? 'Terpakai' : 'Tersedia' }}
                    </p>
                </div>
            @endforeach
        </div>
    </x-islamic-card>
</div>
@endsection
