@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Semester</h1>
            <p class="text-gray-600 mt-1">Lihat informasi semester akademik</p>
        </div>
    </div>

    <!-- Active Semester Alert -->
    @if(isset($activeSemester))
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg p-6 border-2 border-yellow-600 shadow-lg">
        <div class="flex items-center">
            <svg class="w-10 h-10 text-green-800 mr-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <div>
                <p class="font-bold text-green-900 text-lg">Semester Aktif Saat Ini</p>
                <p class="text-green-800 mt-1">{{ $activeSemester->tahun_akademik }} - {{ $activeSemester->periode }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <x-islamic-card title="Filter">
        <form method="GET" action="{{ route('dosen.semester.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Bar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Semester</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari tahun akademik..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
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

    <!-- Semester Table -->
    <x-islamic-card title="Daftar Semester">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-green-200 border border-green-200 rounded-lg">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase w-16">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Semester</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Tahun Akademik</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Periode</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($semesters ?? [] as $index => $semester)
                    <tr class="hover:bg-green-50 transition-colors {{ $semester->is_active ? 'bg-yellow-50 border-l-4 border-yellow-400' : '' }}">
                        <td class="px-4 py-3 text-sm text-gray-700 text-center">
                            {{ isset($semesters) && method_exists($semesters, 'firstItem') ? $semesters->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $semester->nama_semester ?? ($semester->tahun_akademik . ' ' . $semester->periode) }}</p>
                                @if(isset($semester->jenis))
                                <span class="inline-flex px-2 py-1 mt-1 text-xs font-semibold rounded-full {{ $semester->jenis == 'ganjil' ? 'bg-blue-100 text-blue-800' : ($semester->jenis == 'genap' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800') }}">
                                    {{ ucfirst($semester->jenis) }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-green-700">{{ $semester->tahun_akademik }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            {{ isset($semester->tanggal_mulai) && isset($semester->tanggal_selesai)
                                ? \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($semester->tanggal_selesai)->format('d M Y')
                                : $semester->periode }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($semester->is_active)
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('dosen.semester.show', $semester->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors">
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
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data semester</p>
                                <p class="text-sm">Data semester belum tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($semesters) && method_exists($semesters, 'hasPages') && $semesters->hasPages())
        <div class="mt-6">
            {{ $semesters->links() }}
        </div>
        @endif
    </x-islamic-card>
</div>
@endsection
