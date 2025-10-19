@extends('layouts.mahasiswa')

@section('title', 'Kartu Hasil Studi (KHS)')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>Kartu Hasil Studi (KHS)</span>
            </h1>
            <p class="text-gray-600 mt-1">Transkrip nilai per semester</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    @php
        // Calculate IPK and total SKS from actual data
        $totalSksKumulatif = 0;
        $totalNilaiKumulatif = 0;
        $ipkFinal = 0;

        if ($khsList && count($khsList) > 0) {
            foreach ($khsList as $khs) {
                $totalSksKumulatif += $khs->total_sks;
            }
            // Get latest IPK from most recent KHS
            $ipkFinal = $khsList->first()->ip_kumulatif ?? 0;
        }
    @endphp

    <!-- IPK Summary Card -->
    <div class="card-islamic p-6 islamic-pattern">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center space-x-6 mb-4 md:mb-0">
                <div class="w-24 h-24 rounded-full islamic-border overflow-hidden bg-white p-2">
                    @if($mahasiswa && $mahasiswa->foto)
                        <img src="{{ Storage::url($mahasiswa->foto) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ $mahasiswa->nama_lengkap ?? 'Mahasiswa' }}&size=200&background=4A7C59&color=fff"
                             alt="Profile"
                             class="w-full h-full rounded-full object-cover">
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $mahasiswa->nama_lengkap ?? 'Mahasiswa' }}</h2>
                    <p class="text-gray-600">NIM: {{ $mahasiswa->nim ?? '-' }}</p>
                    <p class="text-gray-600">Program Studi: {{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] rounded-lg">
                    <p class="text-sm text-gray-700 mb-1">IPK</p>
                    <p class="text-4xl font-bold text-white">{{ number_format($ipkFinal, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-[#4A7C59] to-[#5a9c6f] text-white rounded-lg">
                    <p class="text-sm opacity-90 mb-1">Total SKS</p>
                    <p class="text-4xl font-bold">{{ $totalSksKumulatif }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KHS Cards per Semester -->
    @if($khsList && count($khsList) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($khsList as $khs)
                @php
                    $isActive = $khs->semester->is_active ?? false;
                    $semesterNumber = $khs->semester->id ?? 0;
                    $nilaisCount = \App\Models\Nilai::where('mahasiswa_id', $mahasiswa->id)
                        ->where('semester_id', $khs->semester_id)
                        ->count();
                @endphp
                <div class="card-islamic p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-[#F4E5C3]">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                                @if($isActive)
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                @endif
                                <span>{{ $khs->semester->nama_semester ?? 'Semester ' . $semesterNumber }}</span>
                            </h3>
                            <p class="text-sm text-gray-600">{{ $khs->semester->tahun_akademik ?? '-' }} ({{ ucfirst($khs->semester->jenis ?? '-') }})</p>
                        </div>
                        @if($isActive)
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                Aktif
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                Selesai
                            </span>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Mata Kuliah</span>
                            <span class="font-bold text-gray-800">{{ $nilaisCount }} MK</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total SKS</span>
                            <span class="font-bold text-gray-800">{{ $khs->total_sks }} SKS</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">IP Semester</span>
                            <span class="font-bold text-[#D4AF37] text-xl">{{ number_format($khs->ip_semester, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">IPK</span>
                            <span class="font-bold text-[#4A7C59] text-xl">{{ number_format($khs->ip_kumulatif, 2) }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <a href="{{ route('mahasiswa.khs.detail', $khs->semester_id) }}"
                           class="block w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white text-center py-3 rounded-lg font-semibold transition">
                            Lihat Detail KHS
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Progress Chart -->
        <div class="card-islamic p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                <span>Perkembangan IP Per Semester</span>
            </h3>
            <div class="grid gap-4" style="grid-template-columns: repeat({{ count($khsList) }}, minmax(0, 1fr));">
                @foreach($khsList->reverse() as $index => $khs)
                    @php
                        $heightPercent = ($khs->ip_semester / 4.0) * 100;
                        $isLatest = $loop->last;
                    @endphp
                    <div class="text-center">
                        <div class="bg-blue-100 rounded-t-lg flex items-end justify-center" style="height: 148px;">
                            <div class="w-full rounded-t-lg text-white font-bold py-2 {{ $isLatest ? 'bg-[#D4AF37]' : 'bg-[#4A7C59]' }}"
                                 style="height: {{ $heightPercent }}%;">
                                {{ number_format($khs->ip_semester, 2) }}
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Sem {{ $loop->iteration }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card-islamic p-12 text-center">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada KHS</h3>
            <p class="text-gray-600 mb-4">
                KHS akan muncul setelah dosen menginput nilai dan meng-generate KHS untuk semester yang bersangkutan.
            </p>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-left max-w-2xl mx-auto">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-700 font-semibold">Informasi:</p>
                        <p class="text-sm text-blue-600 mt-1">
                            Proses pembuatan KHS:<br>
                            1. Dosen menginput nilai mata kuliah<br>
                            2. Dosen meng-generate KHS melalui menu Dosen > KHS<br>
                            3. KHS akan otomatis muncul di halaman ini
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            اِنَّ اللّٰهَ لَا يُضِيْعُ اَجْرَ الْمُحْسِنِيْنَ
        </p>
        <p class="text-gray-600 italic text-sm">
            Sesungguhnya Allah tidak menyia-nyiakan pahala orang-orang yang berbuat baik
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. At-Taubah: 120)</p>
    </div>
</div>
@endsection
