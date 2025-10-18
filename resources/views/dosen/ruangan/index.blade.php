@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Ruangan</h1>
            <p class="text-gray-600 mt-1">Lihat informasi ruangan yang tersedia</p>
        </div>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter">
        <form method="GET" action="{{ route('dosen.ruangan.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Bar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Ruangan</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kode atau nama ruangan..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
            </div>

            <!-- Availability Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="availability" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </x-islamic-card>

    <!-- Ruangan Table -->
    <x-islamic-card title="Daftar Ruangan">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-green-200 border border-green-200 rounded-lg">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase w-16">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Nama Ruangan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">Kapasitas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Fasilitas</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ruangans ?? [] as $index => $ruangan)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700 text-center">
                            {{ isset($ruangans) && method_exists($ruangans, 'firstItem') ? $ruangans->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $ruangan->kode_ruangan }}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $ruangan->nama_ruangan }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ $ruangan->kapasitas }} Orang
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $fasilitas = is_string($ruangan->fasilitas)
                                        ? json_decode($ruangan->fasilitas, true) ?? explode(',', $ruangan->fasilitas)
                                        : (is_array($ruangan->fasilitas) ? $ruangan->fasilitas : []);
                                @endphp
                                @if(count($fasilitas) > 0)
                                    @foreach(array_slice($fasilitas, 0, 3) as $f)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ trim($f) }}</span>
                                    @endforeach
                                    @if(count($fasilitas) > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">+{{ count($fasilitas) - 3 }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($ruangan->is_available)
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tersedia
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tidak Tersedia
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('dosen.ruangan.show', $ruangan->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data ruangan</p>
                                <p class="text-sm">Data ruangan belum tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($ruangans) && method_exists($ruangans, 'hasPages') && $ruangans->hasPages())
        <div class="mt-6">
            {{ $ruangans->links() }}
        </div>
        @endif
    </x-islamic-card>
</div>
@endsection
