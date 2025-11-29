@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Input Nilai</h1>
            <p class="text-gray-600 mt-1">Kelola nilai mahasiswa per mata kuliah</p>
        </div>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter Mata Kuliah">
        <form method="GET" action="{{ route('dosen.nilai.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->tahun_akademik }} - {{ ucfirst($semester->jenis) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    <select name="program_studi_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Program Studi</option>
                        @foreach($programStudis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-filter mr-2"></i>Terapkan
                    </button>
                    <a href="{{ route('dosen.nilai.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </x-islamic-card>

    <!-- Mata Kuliah Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mataKuliahs as $item)
        <x-islamic-card>
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $item['mata_kuliah']->nama_mk }}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Kode:</strong> {{ $item['mata_kuliah']->kode_mk }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>SKS:</strong> {{ $item['mata_kuliah']->sks }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Semester:</strong> {{ $item['semester']->tahun_akademik }} - {{ ucfirst($item['semester']->jenis) }}
                    </p>
                </div>

                <div class="py-3 border-y border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Nilai Terinput</span>
                        <span class="text-2xl font-bold text-green-700">{{ $item['mahasiswa_count'] }}</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('dosen.nilai.mahasiswa', $item['mata_kuliah']->id) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-center text-sm">
                        <i class="fas fa-list mr-2"></i>Lihat Nilai
                    </a>
                    <a href="{{ route('dosen.nilai.create', $item['mata_kuliah']->id) }}" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-center text-sm">
                        <i class="fas fa-plus mr-2"></i>Input Nilai
                    </a>
                </div>
            </div>
        </x-islamic-card>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada mata kuliah</p>
            <p class="text-gray-400 text-sm mt-2">Anda belum memiliki jadwal mengajar</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
