@extends('layouts.mahasiswa')

@section('title', 'Kurikulum')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Kurikulum</h1>
    </div>

    <div class="islamic-divider"></div>

    @if($kurikulum)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $kurikulum->nama_kurikulum }}</h2>
            <p class="text-gray-600">{{ $mahasiswa->programStudi->nama_prodi }}</p>
        </div>

        @if($kurikulum->mataKuliahs && $kurikulum->mataKuliahs->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($kurikulum->mataKuliahs->sortBy('semester') as $mk)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mk->kode_mk }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $mk->nama_mk }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mk->sks }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mk->semester }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $mk->jenis == 'Wajib' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $mk->jenis }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <p class="text-yellow-700">Belum ada mata kuliah dalam kurikulum ini.</p>
            </div>
        @endif
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-yellow-700">Tidak ada kurikulum aktif untuk program studi Anda.</p>
        </div>
    @endif
</div>
@endsection
