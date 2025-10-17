@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Jadwal Mengajar</h1>
            <p class="text-gray-600 mt-1">Kelola jadwal mengajar Anda</p>
        </div>
        <a href="{{ route('dosen.jadwal.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Jadwal</span>
        </a>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter">
        <form method="GET" action="{{ route('dosen.jadwal.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                    <option value="3" selected>Semester 3</option>
                    <option value="4">Semester 4</option>
                    <option value="5">Semester 5</option>
                    <option value="6">Semester 6</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                <select name="hari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Hari</option>
                    <option value="senin">Senin</option>
                    <option value="selasa">Selasa</option>
                    <option value="rabu">Rabu</option>
                    <option value="kamis">Kamis</option>
                    <option value="jumat">Jumat</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </x-islamic-card>

    <!-- Weekly Schedule View -->
    <x-islamic-card title="Jadwal Mingguan">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Jam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Mata Kuliah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Ruangan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                        $colors = [
                            'Senin' => 'bg-blue-50 border-l-4 border-blue-400',
                            'Selasa' => 'bg-green-50 border-l-4 border-green-400',
                            'Rabu' => 'bg-yellow-50 border-l-4 border-yellow-400',
                            'Kamis' => 'bg-purple-50 border-l-4 border-purple-400',
                            'Jumat' => 'bg-pink-50 border-l-4 border-pink-400',
                        ];
                        $jadwalData = [
                            'Senin' => [
                                ['08:00 - 10:00', 'Aqidah Akhlak', '3A', 'R101'],
                                ['10:00 - 12:00', 'Fiqih Ibadah', '3B', 'R102'],
                            ],
                            'Selasa' => [
                                ['13:00 - 15:00', 'Tafsir Al-Quran', '2A', 'R201'],
                            ],
                            'Rabu' => [
                                ['08:00 - 10:00', 'Hadist', '3A', 'R101'],
                                ['13:00 - 15:00', 'Ushul Fiqh', '4A', 'R103'],
                            ],
                            'Kamis' => [
                                ['10:00 - 12:00', 'Sejarah Islam', '2B', 'R202'],
                            ],
                            'Jumat' => [
                                ['08:00 - 10:00', 'Akhlak Tasawuf', '3B', 'R104'],
                            ],
                        ];
                    @endphp

                    @foreach($days as $day)
                        @if(isset($jadwalData[$day]))
                            @foreach($jadwalData[$day] as $index => $jadwal)
                            <tr class="hover:bg-gray-50 transition-colors {{ $colors[$day] }}">
                                @if($index === 0)
                                <td rowspan="{{ count($jadwalData[$day]) }}" class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-block px-3 py-1 text-sm font-bold text-gray-700 bg-white rounded-lg shadow-sm">
                                        {{ $day }}
                                    </span>
                                </td>
                                @endif
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $jadwal[0] }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-gray-800">{{ $jadwal[1] }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $jadwal[2] }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $jadwal[3] }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('dosen.jadwal.edit', 1) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button class="text-red-600 hover:text-red-800" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr class="hover:bg-gray-50 transition-colors {{ $colors[$day] }}">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 text-sm font-bold text-gray-700 bg-white rounded-lg shadow-sm">
                                    {{ $day }}
                                </span>
                            </td>
                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                Tidak ada jadwal
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-islamic-card>

    <!-- Summary -->
    <x-islamic-card title="Ringkasan Jadwal">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($days as $day)
            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                <p class="text-2xl font-bold text-green-700">{{ isset($jadwalData[$day]) ? count($jadwalData[$day]) : 0 }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ $day }}</p>
            </div>
            @endforeach
        </div>
    </x-islamic-card>
</div>
@endsection
