@extends('layouts.mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-mahasiswa.page-header title="Detail Kartu Hasil Studi" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('mahasiswa.khs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-print mr-2"></i>
            Print KHS
        </button>
    </div>

    <!-- Main KHS Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-8">
            <div class="text-center text-white">
                <h1 class="text-3xl font-bold mb-2">KARTU HASIL STUDI (KHS)</h1>
                <p class="text-emerald-100">STAI Al-Fatih Tangerang</p>
                <p class="text-emerald-100 text-sm">Semester {{ $khs->semester->tahun_akademik }}</p>
            </div>
        </div>

        <div class="p-6">
            <!-- Mahasiswa Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2 mb-4">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Data Mahasiswa
                    </h3>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">NIM</span>
                            <span class="text-sm font-semibold">: {{ $khs->mahasiswa->nim }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">Nama</span>
                            <span class="text-sm font-semibold">: {{ $khs->mahasiswa->nama_lengkap }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">Program Studi</span>
                            <span class="text-sm font-semibold">: {{ $khs->mahasiswa->programStudi->nama_prodi }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">Angkatan</span>
                            <span class="text-sm font-semibold">: {{ $khs->mahasiswa->angkatan }}</span>
                        </div>
                        @if($khs->mahasiswa->dosenPa)
                        <div class="flex">
                            <span class="w-32 text-sm text-gray-600">Dosen PA</span>
                            <span class="text-sm font-semibold">: {{ $khs->mahasiswa->dosenPa->nama_lengkap }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Prestasi -->
                <div>
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2 mb-4">
                        <i class="fas fa-chart-line mr-2"></i>
                        Prestasi Akademik
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <p class="text-sm text-blue-600 mb-1">Indeks Prestasi (IP)</p>
                            @php
                                $ipColor = $khs->ip >= 3.5 ? 'text-green-600' : ($khs->ip >= 3.0 ? 'text-blue-600' : ($khs->ip >= 2.5 ? 'text-yellow-600' : 'text-red-600'));
                            @endphp
                            <p class="text-4xl font-bold {{ $ipColor }}">{{ number_format($khs->ip, 2) }}</p>
                            <p class="text-xs text-blue-600 mt-1">Semester ini</p>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <p class="text-sm text-green-600 mb-1">Indeks Prestasi Kumulatif (IPK)</p>
                            @php
                                $ipkColor = $khs->ipk >= 3.5 ? 'text-green-600' : ($khs->ipk >= 3.0 ? 'text-blue-600' : ($khs->ipk >= 2.5 ? 'text-yellow-600' : 'text-red-600'));
                            @endphp
                            <p class="text-4xl font-bold {{ $ipkColor }}">{{ number_format($khs->ipk, 2) }}</p>
                            <p class="text-xs text-green-600 mt-1">Kumulatif</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-purple-50 rounded-lg p-3 border border-purple-200">
                                <p class="text-xs text-purple-600">SKS Semester</p>
                                <p class="text-2xl font-bold text-purple-700">{{ $khs->total_sks_semester }}</p>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-3 border border-indigo-200">
                                <p class="text-xs text-indigo-600">SKS Kumulatif</p>
                                <p class="text-2xl font-bold text-indigo-700">{{ $khs->total_sks_kumulatif }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="islamic-divider my-6"></div>

            <!-- Nilai Table -->
            <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2 mb-4">
                <i class="fas fa-list-alt mr-2"></i>
                Detail Nilai Semester {{ $khs->semester->tahun_akademik }}
            </h3>

            @if($khs->mahasiswa->nilais->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Kode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Mata Kuliah</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">SKS</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Nilai</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Bobot</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Mutu (B×K)</th>
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
                                    $nilaiColor = match($nilai->nilai_huruf) {
                                        'A', 'A-' => 'bg-green-100 text-green-800',
                                        'B+', 'B', 'B-' => 'bg-blue-100 text-blue-800',
                                        'C+', 'C' => 'bg-yellow-100 text-yellow-800',
                                        'D' => 'bg-orange-100 text-orange-800',
                                        'E' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-[#2D5F3F]">{{ $nilai->mataKuliah->kode_mk }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $nilai->mataKuliah->nama_mk }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold">{{ $nilai->mataKuliah->sks }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-3 py-1 {{ $nilaiColor }} text-sm font-bold rounded-full">
                                            {{ $nilai->nilai_huruf ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold">{{ number_format($bobot, 2) }}</td>
                                    <td class="px-4 py-3 text-center text-sm font-semibold">{{ number_format($bobotXSks, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm">Total:</td>
                                <td class="px-4 py-3 text-center text-sm">{{ $khs->total_sks_semester }}</td>
                                <td colspan="2"></td>
                                <td class="px-4 py-3 text-center text-sm">{{ number_format($totalBobotXSks, 2) }}</td>
                            </tr>
                            <tr class="bg-[#2D5F3F] text-white">
                                <td colspan="6" class="px-4 py-3 text-right text-sm">Indeks Prestasi (IP):</td>
                                <td class="px-4 py-3 text-center text-xl font-bold">{{ number_format($khs->ip, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Tidak ada data nilai untuk semester ini</p>
                </div>
            @endif

            <!-- Signatures Section (if enabled) -->
            @if($khs->semester->khs_show_ketua_prodi_signature || $khs->semester->khs_show_dosen_pa_signature)
            <div class="mt-8 grid grid-cols-2 gap-8">
                @if($khs->semester->khs_show_dosen_pa_signature && $khs->mahasiswa->dosenPa)
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-16">Dosen Pembimbing Akademik</p>
                    <p class="font-semibold border-t-2 border-gray-800 inline-block px-8 pt-2">
                        {{ $khs->mahasiswa->dosenPa->nama_lengkap }}
                    </p>
                    @if($khs->mahasiswa->dosenPa->nidn)
                    <p class="text-xs text-gray-500">NIDN: {{ $khs->mahasiswa->dosenPa->nidn }}</p>
                    @endif
                </div>
                @endif

                @if($khs->semester->khs_show_ketua_prodi_signature)
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-16">Ketua Program Studi</p>
                    <p class="font-semibold border-t-2 border-gray-800 inline-block px-8 pt-2">
                        ___________________________
                    </p>
                    <p class="text-xs text-gray-500">NIDN: _______________</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    body {
        background: white;
    }
}
</style>
@endsection
