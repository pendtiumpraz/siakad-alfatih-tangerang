@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kartu Hasil Studi (KHS)</h1>
            <p class="text-gray-600 mt-1">Lihat KHS mahasiswa</p>
        </div>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter Pencarian">
        <form method="GET" action="{{ route('dosen.khs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                    <select name="mahasiswa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Mahasiswa</option>
                        @foreach($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}" {{ request('mahasiswa_id') == $mahasiswa->id ? 'selected' : '' }}>
                                {{ $mahasiswa->nim }} - {{ $mahasiswa->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->nama }} - {{ $semester->tahun_akademik }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('dosen.khs.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    <i class="fas fa-filter mr-2"></i>Cari
                </button>
            </div>
        </form>
    </x-islamic-card>

    <!-- KHS Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($khs as $item)
        <x-islamic-card>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">{{ $item->mahasiswa->nama_lengkap }}</h3>
                    @if($item->status_semester == 'naik')
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Naik
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-semibold">
                            <i class="fas fa-times-circle mr-1"></i>Mengulang
                        </span>
                    @endif
                </div>

                <div class="text-sm text-gray-600">
                    <p><strong>NIM:</strong> {{ $item->mahasiswa->nim }}</p>
                    <p><strong>Semester:</strong> {{ $item->semester->nama }} - {{ $item->semester->tahun_akademik }}</p>
                </div>

                <div class="space-y-2 py-4 border-y border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total SKS</span>
                        <span class="text-sm font-bold text-gray-800">{{ $item->total_sks }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">SKS Lulus</span>
                        <span class="text-sm font-bold text-green-600">{{ $item->total_sks_lulus }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IPS</span>
                        <span class="text-sm font-bold text-green-700">{{ number_format($item->ip_semester, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IPK</span>
                        <span class="text-lg font-bold text-green-800">{{ number_format($item->ip_kumulatif, 2) }}</span>
                    </div>
                </div>

                <div>
                    <a href="{{ route('dosen.khs.show', $item->id) }}" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-center text-sm inline-block">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail & Print
                    </a>
                </div>
            </div>
        </x-islamic-card>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada data KHS</p>
            <p class="text-gray-400 text-sm mt-2">Silakan gunakan filter untuk mencari KHS mahasiswa</p>
        </div>
        @endforelse
    </div>

    @if($khs->hasPages())
    <div class="mt-6">
        {{ $khs->links() }}
    </div>
    @endif
</div>
@endsection
