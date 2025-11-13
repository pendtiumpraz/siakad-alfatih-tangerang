@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-dosen.page-header title="KHS Mahasiswa Bimbingan" />

    <!-- Info Banner -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-400 text-xl mr-3 mt-1"></i>
            <div>
                <p class="font-semibold text-blue-800">Informasi</p>
                <p class="text-sm text-blue-700">Halaman ini menampilkan KHS mahasiswa yang menjadi bimbingan Anda sebagai Dosen Pembimbing Akademik (PA).</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-filter mr-2"></i>
            Filter KHS
        </h3>
        
        <form action="{{ route('dosen.khs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Semester Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                <select name="semester_id" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->tahun_akademik }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cari NIM/Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]" placeholder="Masukkan NIM atau Nama">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('dosen.khs.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Total KHS</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $khsList->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Rata-rata IP</p>
                    <p class="text-3xl font-bold text-green-600">
                        {{ $khsList->count() > 0 ? number_format($khsList->avg('ip'), 2) : '0.00' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Rata-rata IPK</p>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ $khsList->count() > 0 ? number_format($khsList->avg('ipk'), 2) : '0.00' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trophy text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- KHS Table -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-list mr-2"></i>
                Daftar KHS Mahasiswa Bimbingan
            </h2>
        </div>

        <div class="overflow-x-auto">
            @if($khsList->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Program Studi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">IP</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">IPK</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">SKS</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($khsList as $index => $khs)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $khsList->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-[#2D5F3F]">{{ $khs->mahasiswa->nim }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $khs->mahasiswa->nama_lengkap }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $khs->mahasiswa->programStudi->kode_prodi }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $khs->semester->tahun_akademik }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $ipColor = $khs->ip >= 3.5 ? 'text-green-600' : ($khs->ip >= 3.0 ? 'text-blue-600' : ($khs->ip >= 2.5 ? 'text-yellow-600' : 'text-red-600'));
                                    @endphp
                                    <span class="text-lg font-bold {{ $ipColor }}">{{ number_format($khs->ip, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $ipkColor = $khs->ipk >= 3.5 ? 'text-green-600' : ($khs->ipk >= 3.0 ? 'text-blue-600' : ($khs->ipk >= 2.5 ? 'text-yellow-600' : 'text-red-600'));
                                    @endphp
                                    <span class="text-lg font-bold {{ $ipkColor }}">{{ number_format($khs->ipk, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-700">{{ $khs->total_sks_semester }} / {{ $khs->total_sks_kumulatif }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('dosen.khs.show', $khs->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700 transition">
                                        <i class="fas fa-eye mr-1"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $khsList->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Tidak ada data KHS</p>
                    <p class="text-gray-400 text-sm mt-2">Belum ada mahasiswa bimbingan atau KHS belum digenerate</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
