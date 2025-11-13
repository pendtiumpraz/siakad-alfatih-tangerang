@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-operator.page-header title="Master Data Program Studi" />

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('operator.program-studi.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama program studi..."
                        class="w-full pl-10 pr-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Jenjang Filter -->
            <div class="w-full md:w-48">
                <select name="jenjang" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                    <option value="">Semua Jenjang</option>
                    <option value="D3" {{ request('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="S1" {{ request('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ request('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ request('jenjang') == 'S3' ? 'selected' : '' }}>S3</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select name="is_active" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <!-- Filter Button -->
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] text-[#2D5F3F] font-semibold rounded-lg hover:shadow-lg transition">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
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

    <!-- Program Studi Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Program Studi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Jenjang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($programStudis ?? [] as $index => $prodi)
                        <tr class="hover:bg-[#F4E5C3] hover:bg-opacity-30 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($programStudis->currentPage() - 1) * $programStudis->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#2D5F3F]">
                                {{ $prodi->kode }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $prodi->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    {{ $prodi->jenjang }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($prodi->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-graduation-cap text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data program studi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($programStudis) && $programStudis->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $programStudis->firstItem() }} - {{ $programStudis->lastItem() }} dari {{ $programStudis->total() }} data
                    </div>
                    <div class="flex space-x-1">
                        @if ($programStudis->onFirstPage())
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $programStudis->previousPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @php
                            $current = $programStudis->currentPage();
                            $last = $programStudis->lastPage();
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
                                <a href="{{ $programStudis->url($page) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($programStudis->hasMorePages())
                            <a href="{{ $programStudis->nextPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
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
