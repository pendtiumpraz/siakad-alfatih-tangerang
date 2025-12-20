@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Calendar View - Jadwal Perkuliahan</h1>
            <p class="text-gray-600 mt-1">Tampilan kalender pekanan jadwal perkuliahan</p>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali ke List</span>
        </a>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter">
        <form method="GET" action="{{ route('admin.jadwal.calendar') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Semester</label>
                    <select name="jenis_semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="ganjil" {{ $jenis_semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="genap" {{ $jenis_semester == 'genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    <select name="program_studi_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Program Studi</option>
                        @foreach($programStudis as $prodi)
                            <option value="{{ $prodi->id }}" {{ $program_studi_id == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.jadwal.calendar') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </x-islamic-card>

    <!-- Calendar Grid -->
    <x-islamic-card title="Kalender Pekanan">
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700">
                        <th class="border border-gray-300 px-2 py-3 text-white font-semibold text-sm w-20">Jam</th>
                        @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            <th class="border border-gray-300 px-2 py-3 text-white font-semibold text-sm">{{ $hari }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $timeSlot)
                        <tr>
                            <td class="border border-gray-300 px-2 py-2 bg-gray-50 font-semibold text-xs text-center align-top">
                                {{ $timeSlot }}
                            </td>
                            @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <td class="border border-gray-300 px-2 py-2 align-top relative min-h-[80px]">
                                    @php
                                        // Find jadwal that overlaps with this time slot
                                        $timeSlotHour = (int) substr($timeSlot, 0, 2);
                                        $jadwalsInSlot = collect($calendar[$hari] ?? [])->filter(function($jadwal) use ($timeSlotHour) {
                                            $jamMulaiHour = (int) \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H');
                                            $jamSelesaiHour = (int) \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H');
                                            
                                            // Check if this time slot falls within jadwal time range
                                            return $timeSlotHour >= $jamMulaiHour && $timeSlotHour < $jamSelesaiHour;
                                        });
                                    @endphp

                                    @foreach($jadwalsInSlot as $jadwal)
                                        @php
                                            $jamMulaiHour = (int) \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H');
                                            $jamSelesaiHour = (int) \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H');
                                            
                                            // Only render if this is the starting time slot
                                            $isStartSlot = $timeSlotHour == $jamMulaiHour;
                                        @endphp

                                        @if($isStartSlot)
                                            <div class="mb-2 p-2 bg-blue-50 border-l-4 border-blue-500 rounded text-xs hover:bg-blue-100 transition-colors">
                                                <div class="font-bold text-blue-900 mb-1">
                                                    {{ $jadwal->mataKuliah->nama_mk ?? '-' }}
                                                </div>
                                                <div class="text-gray-700 mb-1">
                                                    <i class="fas fa-user-tie mr-1"></i>{{ $jadwal->dosen->nama_lengkap ?? '-' }}
                                                </div>
                                                <div class="text-gray-600 mb-1">
                                                    <i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                                </div>
                                                <div class="text-gray-600">
                                                    @if($jadwal->ruangan && ($jadwal->ruangan->tipe ?? 'luring') === 'daring')
                                                        <i class="fas fa-globe mr-1 text-blue-600"></i>{{ $jadwal->ruangan->nama_ruangan ?? '-' }}
                                                        @if($jadwal->ruangan->url)
                                                            <a href="{{ $jadwal->ruangan->url }}" target="_blank" class="text-blue-600 hover:underline ml-1" title="Buka Link Meeting">
                                                                <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-door-open mr-1"></i>{{ $jadwal->ruangan->nama_ruangan ?? '-' }}
                                                    @endif
                                                    | Kelas {{ $jadwal->kelas }}
                                                </div>
                                                @if($jadwal->mataKuliah && $jadwal->mataKuliah->kurikulum && $jadwal->mataKuliah->kurikulum->programStudi)
                                                    <div class="text-green-600 mt-1 font-semibold">
                                                        <i class="fas fa-graduation-cap mr-1"></i>{{ $jadwal->mataKuliah->kurikulum->programStudi->nama_prodi }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-gray-800 mb-3">Keterangan:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-50 border-l-4 border-blue-500 rounded"></div>
                    <span class="text-gray-700">Jadwal Perkuliahan</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user-tie text-gray-600"></i>
                    <span class="text-gray-700">Nama Dosen Pengampu</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-clock text-gray-600"></i>
                    <span class="text-gray-700">Waktu Perkuliahan</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-door-open text-gray-600"></i>
                    <span class="text-gray-700">Ruangan & Kelas</span>
                </div>
            </div>
        </div>
    </x-islamic-card>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Informasi:</strong> Kalender menampilkan jadwal dari jam 06:00 hingga 22:00. Gunakan filter untuk melihat jadwal berdasarkan semester dan program studi tertentu.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
