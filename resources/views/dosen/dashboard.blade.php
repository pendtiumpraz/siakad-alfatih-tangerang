@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Dosen</h1>
            <p class="text-gray-600 mt-1">Selamat datang di sistem informasi akademik STAI AL-FATIH</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
        </div>
    </div>

    <!-- Islamic Decorative Divider -->
    <div class="flex items-center justify-center py-2">
        <div class="flex space-x-2">
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card
            title="Total Jadwal Mengajar"
            value="12"
            color="green"
            subtext="Mata kuliah aktif"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path fill-rule=\'evenodd\' d=\'M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z\' clip-rule=\'evenodd\'/></svg>'"
        />

        <x-stat-card
            title="Total Mahasiswa"
            value="245"
            color="blue"
            subtext="Mahasiswa aktif"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z\'/></svg>'"
        />

        <x-stat-card
            title="Pending Nilai"
            value="35"
            color="yellow"
            subtext="Perlu diinput"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path fill-rule=\'evenodd\' d=\'M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z\' clip-rule=\'evenodd\'/></svg>'"
        />

        <x-stat-card
            title="Kelas Aktif"
            value="8"
            color="gold"
            subtext="Semester ini"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z\'/></svg>'"
        />
    </div>

    <!-- Today's Schedule -->
    <x-islamic-card title="Jadwal Mengajar Hari Ini" class="border-t-4 border-yellow-600">
        <div class="space-y-4">
            @forelse(range(1, 3) as $index)
            @php
                $times = [
                    ['08:00', '10:00'],
                    ['10:00', '12:00'],
                    ['13:00', '15:00']
                ];
                $matakuliah = ['Aqidah Akhlak', 'Fiqih Ibadah', 'Tafsir Al-Quran'];
                $kelas = ['3A', '3B', '2A'];
                $ruangan = ['R101', 'R102', 'R201'];
            @endphp
            <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-yellow-50 border-l-4 border-yellow-500 rounded-lg hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-20 text-center mr-4">
                    <p class="text-2xl font-bold text-green-700">{{ $times[$index - 1][0] }}</p>
                    <p class="text-xs text-gray-600">{{ $times[$index - 1][1] }}</p>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-gray-800">{{ $matakuliah[$index - 1] }}</h4>
                    <p class="text-sm text-gray-600 mt-1">Kelas {{ $kelas[$index - 1] }} - {{ $ruangan[$index - 1] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('dosen.jadwal-mengajar.index') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                        Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-600">Tidak ada jadwal mengajar hari ini</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <a href="{{ route('dosen.jadwal-mengajar.index') }}" class="text-green-600 hover:text-green-800 font-medium">
                Lihat Semua Jadwal
            </a>
        </div>
    </x-islamic-card>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Grades -->
        <x-islamic-card title="Input Nilai Terbaru">
            <div class="space-y-3">
                @forelse(range(1, 5) as $index)
                <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-gray-800">{{ ['Ahmad Nur', 'Fatimah', 'Muhammad', 'Khadijah', 'Umar'][$index - 1] }}</span>
                            <span class="text-xs text-gray-500">2301{{ str_pad($index, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Aqidah Akhlak - Kelas 3A</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::now()->subDays($index)->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <x-status-badge :status="['A', 'B', 'A', 'B', 'A'][$index - 1]" type="grade" />
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Belum ada nilai yang diinput</p>
                @endforelse

                <div class="pt-3 border-t border-gray-200">
                    <a href="{{ route('dosen.nilai.index') }}" class="block text-center text-green-600 hover:text-green-800 font-medium text-sm">
                        Input Nilai Mahasiswa
                    </a>
                </div>
            </div>
        </x-islamic-card>

        <!-- Quick Actions -->
        <x-islamic-card title="Aksi Cepat">
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('dosen.nilai.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-300 rounded-lg hover:shadow-md transition-all">
                    <div class="p-3 bg-green-600 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Input Nilai</h4>
                        <p class="text-sm text-gray-600">Input nilai mahasiswa</p>
                    </div>
                </a>

                <a href="{{ route('dosen.jadwal-mengajar.index') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 border-2 border-blue-300 rounded-lg hover:shadow-md transition-all">
                    <div class="p-3 bg-blue-600 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Lihat Jadwal</h4>
                        <p class="text-sm text-gray-600">Jadwal mengajar lengkap</p>
                    </div>
                </a>

                <a href="{{ route('dosen.khs.index') }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 border-2 border-yellow-300 rounded-lg hover:shadow-md transition-all">
                    <div class="p-3 bg-yellow-600 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Lihat KHS</h4>
                        <p class="text-sm text-gray-600">Kartu Hasil Studi mahasiswa</p>
                    </div>
                </a>

                <a href="{{ route('dosen.jadwal-mengajar.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg hover:shadow-md transition-all">
                    <div class="p-3 bg-purple-600 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Tambah Jadwal</h4>
                        <p class="text-sm text-gray-600">Buat jadwal mengajar baru</p>
                    </div>
                </a>
            </div>
        </x-islamic-card>
    </div>

    <!-- Summary Stats -->
    <x-islamic-card title="Ringkasan Semester Ini">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                <p class="text-3xl font-bold text-green-700">187</p>
                <p class="text-sm text-gray-600 mt-1">Nilai A</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                <p class="text-3xl font-bold text-blue-700">142</p>
                <p class="text-sm text-gray-600 mt-1">Nilai B</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                <p class="text-3xl font-bold text-yellow-700">89</p>
                <p class="text-sm text-gray-600 mt-1">Nilai C</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                <p class="text-3xl font-bold text-purple-700">96.5%</p>
                <p class="text-sm text-gray-600 mt-1">Tingkat Kelulusan</p>
            </div>
        </div>
    </x-islamic-card>
</div>
@endsection
