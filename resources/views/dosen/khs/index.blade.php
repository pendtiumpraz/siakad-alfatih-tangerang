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
        <form method="GET" action="{{ route('dosen.khs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                <select name="mahasiswa" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Pilih Mahasiswa</option>
                    <option value="1">2301001 - Ahmad Nur Rahman</option>
                    <option value="2">2301002 - Fatimah Azzahra</option>
                    <option value="3">2301003 - Muhammad Ali</option>
                    <option value="4">2301004 - Khadijah Binti Ahmad</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Pilih Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                    <option value="3">Semester 3</option>
                    <option value="4">Semester 4</option>
                    <option value="5">Semester 5</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Cari
                </button>
            </div>
        </form>
    </x-islamic-card>

    <!-- KHS Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach(range(1, 5) as $semester)
        <x-islamic-card>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Semester {{ $semester }}</h3>
                    <x-status-badge status="lulus" type="status" />
                </div>

                <div class="space-y-2 py-4 border-y border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total SKS</span>
                        <span class="text-sm font-bold text-gray-800">{{ 18 + ($semester % 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IPS</span>
                        <span class="text-sm font-bold text-green-700">{{ number_format(3.50 + ($semester * 0.05), 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IPK</span>
                        <span class="text-lg font-bold text-green-800">{{ number_format(3.55 + ($semester * 0.02), 2) }}</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('dosen.khs.show', $semester) }}" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-center text-sm">
                        Lihat Detail
                    </a>
                    <button onclick="window.print()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </x-islamic-card>
        @endforeach
    </div>

    <!-- Summary Stats -->
    <x-islamic-card title="Ringkasan Akademik">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border-2 border-green-300">
                <p class="text-4xl font-bold text-green-700">95</p>
                <p class="text-sm text-gray-600 mt-2">Total SKS</p>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-300">
                <p class="text-4xl font-bold text-blue-700">3.67</p>
                <p class="text-sm text-gray-600 mt-2">IPK Kumulatif</p>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border-2 border-yellow-300">
                <p class="text-4xl font-bold text-yellow-700">5</p>
                <p class="text-sm text-gray-600 mt-2">Semester Aktif</p>
            </div>
            <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border-2 border-purple-300">
                <p class="text-4xl font-bold text-purple-700">Sangat Baik</p>
                <p class="text-sm text-gray-600 mt-2">Predikat</p>
            </div>
        </div>
    </x-islamic-card>
</div>
@endsection
