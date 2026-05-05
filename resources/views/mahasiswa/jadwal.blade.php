@extends('layouts.mahasiswa')

@section('title', 'Jadwal Kuliah')

@section('content')
<div class="space-y-6" x-data="{ tab: 'saya' }">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Kuliah</h1>
        @if(isset($semester))
            <div class="text-right">
                <p class="text-sm text-gray-600">Semester Aktif</p>
                <p class="text-lg font-semibold text-[#D4AF37]">{{ $semester->tahun_akademik }} - {{ ucfirst($semester->jenis) }}</p>
            </div>
        @endif
    </div>

    <div class="islamic-divider"></div>

    {{-- Card: Mata Kuliah yang Sedang Berlangsung --}}
    @if(!empty($jadwalSekarang) || !empty($jadwalSelanjutnya))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if(!empty($jadwalSekarang))
                <div class="rounded-lg shadow-md p-5 text-white" style="background: linear-gradient(135deg, #15803d, #22c55e);">
                    <div class="flex items-center mb-3">
                        <span class="relative flex h-3 w-3 mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                        </span>
                        <p class="text-sm font-semibold uppercase tracking-wide">Sedang Berlangsung</p>
                    </div>
                    <h3 class="text-xl font-bold">{{ $jadwalSekarang->mataKuliah->nama_mk ?? '-' }}</h3>
                    <p class="text-sm opacity-90 mt-1">{{ $jadwalSekarang->mataKuliah->kode_mk ?? '-' }} · {{ $jadwalSekarang->kelas ? 'Kelas ' . $jadwalSekarang->kelas : '' }}</p>
                    <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                        <div>
                            <p class="opacity-75">Jam</p>
                            <p class="font-semibold">{{ \Illuminate\Support\Str::substr($jadwalSekarang->jam_mulai, 0, 5) }} - {{ \Illuminate\Support\Str::substr($jadwalSekarang->jam_selesai, 0, 5) }}</p>
                        </div>
                        <div>
                            <p class="opacity-75">Dosen</p>
                            <p class="font-semibold">{{ $jadwalSekarang->dosen->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="opacity-75">Ruangan</p>
                            @if($jadwalSekarang->ruangan)
                                @if(($jadwalSekarang->ruangan->tipe ?? 'luring') === 'daring')
                                    <p class="font-semibold">{{ $jadwalSekarang->ruangan->nama_ruangan }} (Daring)</p>
                                    @if($jadwalSekarang->ruangan->url)
                                        <a href="{{ $jadwalSekarang->ruangan->url }}" target="_blank" class="inline-flex items-center mt-1 px-3 py-1 bg-white/20 hover:bg-white/30 rounded text-xs font-semibold">
                                            <i class="fas fa-external-link-alt mr-1"></i> Buka Link Meeting
                                        </a>
                                    @endif
                                @else
                                    <p class="font-semibold">{{ $jadwalSekarang->ruangan->nama_ruangan }}</p>
                                @endif
                            @else
                                <p class="font-semibold">-</p>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-lg shadow-md p-5 bg-white border-l-4 border-gray-300">
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Sedang Berlangsung</p>
                    <p class="text-gray-700 mt-2">Tidak ada kelas yang sedang berlangsung saat ini.</p>
                </div>
            @endif

            @if(!empty($jadwalSelanjutnya))
                <div class="rounded-lg shadow-md p-5 bg-white border-l-4" style="border-color: #D4AF37;">
                    <p class="text-sm font-semibold uppercase tracking-wide" style="color: #D4AF37;">Kelas Selanjutnya Hari Ini</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-2">{{ $jadwalSelanjutnya->mataKuliah->nama_mk ?? '-' }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $jadwalSelanjutnya->mataKuliah->kode_mk ?? '-' }} · {{ $jadwalSelanjutnya->kelas ? 'Kelas ' . $jadwalSelanjutnya->kelas : '' }}</p>
                    <div class="grid grid-cols-2 gap-3 mt-4 text-sm text-gray-700">
                        <div>
                            <p class="text-gray-500">Mulai</p>
                            <p class="font-semibold">{{ \Illuminate\Support\Str::substr($jadwalSelanjutnya->jam_mulai, 0, 5) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Dosen</p>
                            <p class="font-semibold">{{ $jadwalSelanjutnya->dosen->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-500">Ruangan</p>
                            <p class="font-semibold">{{ $jadwalSelanjutnya->ruangan->nama_ruangan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Tab Switcher --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex border-b border-gray-200">
            <button
                type="button"
                @click="tab = 'saya'"
                :class="tab === 'saya' ? 'border-b-2 border-[#4A7C59] text-[#4A7C59]' : 'text-gray-500 hover:text-gray-700'"
                class="px-6 py-3 text-sm font-semibold transition-colors"
            >
                Jadwal Saya (KRS)
                <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ isset($jadwals) ? $jadwals->count() : 0 }}</span>
            </button>
            <button
                type="button"
                @click="tab = 'kelas'"
                :class="tab === 'kelas' ? 'border-b-2 border-[#4A7C59] text-[#4A7C59]' : 'text-gray-500 hover:text-gray-700'"
                class="px-6 py-3 text-sm font-semibold transition-colors"
            >
                Jadwal Kelas Keseluruhan
                <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ isset($jadwalKelas) ? $jadwalKelas->count() : 0 }}</span>
            </button>
        </div>

        {{-- Tab: Jadwal Saya --}}
        <div x-show="tab === 'saya'" class="p-4">
            @if(isset($mataKuliahKrs) && $mataKuliahKrs->count() > 0)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-4">
                    <p class="text-sm text-blue-700">
                        Menampilkan jadwal untuk <strong>{{ $mataKuliahKrs->count() }} mata kuliah</strong> yang sudah disetujui di KRS Anda.
                    </p>
                </div>
            @endif

            @if(isset($jadwals) && $jadwals->count() > 0)
                @include('mahasiswa.jadwal._table', ['rows' => $jadwals])
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <p class="text-sm text-yellow-700">
                        @if(session('info'))
                            {{ session('info') }}
                        @elseif(isset($semester))
                            Belum ada jadwal pribadi. Pastikan KRS Anda sudah disetujui.
                        @else
                            Tidak ada semester aktif saat ini.
                        @endif
                    </p>
                    @if(!session('info') && isset($semester))
                        <p class="text-sm text-yellow-700 mt-2">
                            <a href="{{ route('mahasiswa.krs.index') }}" class="font-medium underline hover:text-yellow-800">
                                Klik di sini untuk mengisi KRS
                            </a>
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Tab: Jadwal Kelas Keseluruhan --}}
        <div x-show="tab === 'kelas'" class="p-4" x-cloak>
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded mb-4">
                <p class="text-sm text-amber-800">
                    Menampilkan <strong>seluruh mata kuliah</strong> di program studi
                    <strong>{{ optional(auth()->user()->mahasiswa->programStudi)->nama_prodi ?? '-' }}</strong>
                    untuk tingkat <strong>Semester {{ auth()->user()->mahasiswa->semester_aktif ?? '-' }}</strong> pada semester {{ ucfirst($semester->jenis ?? '') }} ini, terlepas dari mata kuliah yang Anda ambil di KRS.
                </p>
            </div>

            @if(isset($jadwalKelas) && $jadwalKelas->count() > 0)
                @include('mahasiswa.jadwal._table', ['rows' => $jadwalKelas])
            @else
                <div class="bg-gray-50 border-l-4 border-gray-300 p-4 rounded">
                    <p class="text-sm text-gray-700">
                        Belum ada jadwal kelas keseluruhan untuk tingkat semester Anda di periode aktif ini.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
