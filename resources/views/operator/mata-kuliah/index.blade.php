@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-operator.page-header title="Master Data Mata Kuliah" />

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border-2 border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600">Total Mata Kuliah</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $totalMataKuliah ?? 0 }}</p>
                </div>
                <i class="fas fa-book-open text-3xl text-blue-400"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border-2 border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600">Total SKS</p>
                    <p class="text-2xl font-bold text-green-700">{{ $totalSKS ?? 0 }}</p>
                </div>
                <i class="fas fa-calculator text-3xl text-green-400"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#F4E5C3] to-[#D4AF37] rounded-lg p-4 border-2 border-[#D4AF37]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#2D5F3F]">Mata Kuliah Wajib</p>
                    <p class="text-2xl font-bold text-[#2D5F3F]">{{ $totalWajib ?? 0 }}</p>
                </div>
                <i class="fas fa-star text-3xl text-[#2D5F3F] opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border-2 border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600">Mata Kuliah Pilihan</p>
                    <p class="text-2xl font-bold text-yellow-700">{{ $totalPilihan ?? 0 }}</p>
                </div>
                <i class="fas fa-list-ul text-3xl text-yellow-400"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('operator.mata-kuliah.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search Bar -->
                <div class="lg:col-span-2">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari kode atau nama mata kuliah..."
                            class="w-full pl-10 pr-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Kurikulum Filter -->
                <div>
                    <select name="kurikulum_id" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                        <option value="">Semua Kurikulum</option>
                        @foreach($kurikulums ?? [] as $kurikulum)
                            <option value="{{ $kurikulum->id }}" {{ request('kurikulum_id') == $kurikulum->id ? 'selected' : '' }}>
                                {{ $kurikulum->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester Filter -->
                <div>
                    <select name="semester" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                        <option value="">Semua Semester</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Jenis Filter -->
                <div>
                    <select name="jenis" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                        <option value="">Semua Jenis</option>
                        <option value="wajib" {{ request('jenis') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                        <option value="pilihan" {{ request('jenis') == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] text-[#2D5F3F] font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('admin.mata-kuliah.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-redo mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-end">
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-excel mr-2"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Mata Kuliah Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kode MK</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kurikulum</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">SKS</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Jenis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($mataKuliahs ?? [] as $index => $mk)
                        <tr class="hover:bg-[#F4E5C3] hover:bg-opacity-30 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($mataKuliahs->currentPage() - 1) * $mataKuliahs->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#2D5F3F]">
                                {{ $mk->kode_mk }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $mk->nama_mk }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>
                                    <p class="font-medium">{{ $mk->kurikulum->nama_kurikulum ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $mk->kurikulum->programStudi->nama_prodi ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    Semester {{ $mk->semester }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($mk->jenis == 'Wajib')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        <i class="fas fa-star mr-1"></i>
                                        Wajib
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-[#F4E5C3] text-[#2D5F3F] text-xs font-semibold rounded-full">
                                        <i class="fas fa-list mr-1"></i>
                                        Pilihan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-book-open text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data mata kuliah</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($mataKuliahs) && $mataKuliahs->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $mataKuliahs->firstItem() }} - {{ $mataKuliahs->lastItem() }} dari {{ $mataKuliahs->total() }} data
                    </div>
                    <div class="flex space-x-1">
                        @if ($mataKuliahs->onFirstPage())
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $mataKuliahs->previousPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @php
                            $current = $mataKuliahs->currentPage();
                            $last = $mataKuliahs->lastPage();
                            $delta = 2;
                            $left = $current - $delta;
                            $right = $current + $delta + 1;
                            $range = [];
                            $rangeWithDots = [];
                            $l = null;

                            for ($i = 1; $i <= $last; $i++) {
                                if ($i == 1 || $i == $last || ($i >= $left && $i < $right)) {
                                    $range[] = $i;
                                }
                            }

                            foreach ($range as $i) {
                                if ($l) {
                                    if ($i - $l === 2) {
                                        $rangeWithDots[] = $l + 1;
                                    } else if ($i - $l !== 1) {
                                        $rangeWithDots[] = '...';
                                    }
                                }
                                $rangeWithDots[] = $i;
                                $l = $i;
                            }
                        @endphp

                        @foreach ($rangeWithDots as $page)
                            @if ($page === '...')
                                <span class="px-4 py-2 bg-white border border-gray-300 text-gray-400 rounded cursor-default">...</span>
                            @elseif ($page == $current)
                                <span class="px-4 py-2 bg-[#2D5F3F] text-white rounded font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $mataKuliahs->url($page) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($mataKuliahs->hasMorePages())
                            <a href="{{ $mataKuliahs->nextPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
