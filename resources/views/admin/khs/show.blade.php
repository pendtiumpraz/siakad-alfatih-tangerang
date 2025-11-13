@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Detail KHS" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('admin.khs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-print mr-2"></i>
            Print KHS
        </button>
    </div>

    <!-- Main Info Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-[#D4AF37] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-graduation-cap text-3xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $khs->mahasiswa->nama_lengkap }}</h2>
                        <p class="text-emerald-50">NIM: {{ $khs->mahasiswa->nim }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Mahasiswa Info -->
                <div class="md:col-span-2 space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Informasi Mahasiswa
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">NIM</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $khs->mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Program Studi</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $khs->mahasiswa->programStudi->nama_prodi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Semester</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $khs->semester->tahun_akademik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Angkatan</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $khs->mahasiswa->angkatan }}</p>
                        </div>
                        @if($khs->mahasiswa->dosenPa)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Dosen PA</p>
                            <p class="text-lg font-semibold text-gray-700">
                                {{ $khs->mahasiswa->dosenPa->nama_lengkap }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Prestasi Cards -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-chart-line mr-2"></i>
                        Prestasi Akademik
                    </h3>

                    <!-- IP Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-blue-600 mb-2">Indeks Prestasi (IP)</p>
                        <p class="text-4xl font-bold text-blue-700">{{ number_format($khs->ip, 2) }}</p>
                        <p class="text-xs text-blue-600 mt-1">Semester ini</p>
                    </div>

                    <!-- IPK Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <p class="text-sm text-green-600 mb-2">Indeks Prestasi Kumulatif (IPK)</p>
                        <p class="text-4xl font-bold text-green-700">{{ number_format($khs->ipk, 2) }}</p>
                        <p class="text-xs text-green-600 mt-1">Kumulatif</p>
                    </div>

                    <!-- SKS Card -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                        <p class="text-sm text-purple-600 mb-2">Total SKS</p>
                        <p class="text-2xl font-bold text-purple-700">{{ $khs->total_sks_semester }} SKS</p>
                        <p class="text-xs text-purple-600 mt-1">Semester ini</p>
                        <p class="text-2xl font-bold text-purple-700 mt-2">{{ $khs->total_sks_kumulatif }} SKS</p>
                        <p class="text-xs text-purple-600">Kumulatif</p>
                    </div>
                </div>
            </div>

            <div class="islamic-divider my-6"></div>

            <!-- Nilai Section -->
            <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2 mb-4">
                <i class="fas fa-list-alt mr-2"></i>
                Detail Nilai Semester {{ $khs->semester->tahun_akademik }}
            </h3>

            @if($khs->mahasiswa->nilais->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kode MK</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">SKS</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Nilai Angka</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Nilai Huruf</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Bobot</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Bobot × SKS</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $khsService = app(\App\Services\KhsGeneratorService::class);
                                $totalBobotXSks = 0;
                            @endphp
                            @foreach($khs->mahasiswa->nilais as $index => $nilai)
                                @php
                                    $bobot = $khsService->getBobot($nilai->nilai_huruf ?? 'E');
                                    $bobotXSks = $bobot * ($nilai->mataKuliah->sks ?? 0);
                                    $totalBobotXSks += $bobotXSks;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#2D5F3F]">
                                        {{ $nilai->mataKuliah->kode_mk }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $nilai->mataKuliah->nama_mk }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">
                                        {{ $nilai->mataKuliah->sks }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        {{ $nilai->nilai_angka ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $nilaiColor = match($nilai->nilai_huruf) {
                                                'A', 'A-' => 'bg-green-100 text-green-800',
                                                'B+', 'B', 'B-' => 'bg-blue-100 text-blue-800',
                                                'C+', 'C' => 'bg-yellow-100 text-yellow-800',
                                                'D' => 'bg-orange-100 text-orange-800',
                                                'E' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 {{ $nilaiColor }} text-sm font-bold rounded-full">
                                            {{ $nilai->nilai_huruf ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">
                                        {{ number_format($bobot, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold">
                                        {{ number_format($bobotXSks, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm text-gray-700">Total:</td>
                                <td class="px-6 py-4 text-center text-sm">{{ $khs->total_sks_semester }}</td>
                                <td colspan="3"></td>
                                <td class="px-6 py-4 text-center text-sm">{{ number_format($totalBobotXSks, 2) }}</td>
                            </tr>
                            <tr class="bg-[#2D5F3F] text-white">
                                <td colspan="7" class="px-6 py-4 text-right text-sm">Indeks Prestasi (IP):</td>
                                <td class="px-6 py-4 text-center text-xl font-bold">{{ number_format($khs->ip, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada data nilai untuk semester ini</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
