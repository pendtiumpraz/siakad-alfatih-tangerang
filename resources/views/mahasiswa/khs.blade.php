@extends('layouts.mahasiswa')

@section('title', 'Kartu Hasil Studi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Kartu Hasil Studi (KHS)</h1>
    </div>

    <div class="islamic-divider"></div>

    @if($khsList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($khsList as $khs)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $khs->semester->nama_semester ?? 'Semester' }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ $khs->semester->tahun_akademik ?? '-' }}</p>

                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">IPS:</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($khs->ips ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">IPK:</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($khs->ipk ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">SKS:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $khs->total_sks ?? 0 }}</span>
                        </div>
                    </div>

                    <a href="{{ route('mahasiswa.khs.index', ['semester_id' => $khs->semester_id]) }}"
                       class="block w-full text-center px-4 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#C4A137] transition-colors">
                        Lihat Detail
                    </a>
                </div>
            @endforeach
        </div>

        @if($selectedKhs)
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail KHS - {{ $selectedKhs->semester->nama_semester }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">IPS</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($selectedKhs->ips ?? 0, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600">IPK</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($selectedKhs->ipk ?? 0, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <p class="text-sm text-gray-600">Total SKS</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $selectedKhs->total_sks ?? 0 }}</p>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-yellow-700">Belum ada data KHS.</p>
        </div>
    @endif
</div>
@endsection
