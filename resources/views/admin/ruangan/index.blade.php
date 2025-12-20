@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Master Data Ruangan" />

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('admin.ruangan.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode atau nama ruangan..."
                        class="w-full pl-10 pr-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Availability Filter -->
            <div class="w-full md:w-48">
                <select name="is_available" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('is_available') == '1' ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ request('is_available') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
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
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <div class="flex gap-2">
            <a href="{{ route('admin.ruangan.create') }}" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Ruangan
            </a>

            @if(request('trashed'))
                <a href="{{ route('admin.ruangan.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            @else
                <a href="{{ route('admin.ruangan.index', ['trashed' => 1]) }}" class="px-6 py-2 bg-[#D4AF37] text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                    <i class="fas fa-trash-restore mr-2"></i>
                    Data Terhapus
                </a>
            @endif
        </div>

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

    <!-- Batch Delete Actions -->
    @if(!request('trashed'))
        @include('components.batch-delete-actions', ['routeName' => route('admin.ruangan.batch-delete')])
    @else
        @include('components.batch-restore-actions', ['routeName' => route('admin.ruangan.batch-restore')])
    @endif

    <!-- Ruangan Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        @if(!request('trashed'))
                        <th class="px-4 py-4 text-left">
                            <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </th>
                        @else
                        <th class="px-4 py-4 text-left">
                            <input type="checkbox" id="restore-select-all" onchange="toggleRestoreSelectAll(this)" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </th>
                        @endif
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kode Ruangan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Ruangan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Kapasitas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Fasilitas</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ruangans ?? [] as $index => $ruangan)
                        <tr class="{{ $ruangan->trashed() ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-[#F4E5C3] hover:bg-opacity-30' }} transition">
                            @if(!request('trashed'))
                            <td class="px-4 py-4">
                                <input type="checkbox" class="row-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" value="{{ $ruangan->id }}" onchange="updateSelectedIds()">
                            </td>
                            @else
                            <td class="px-4 py-4">
                                <input type="checkbox" class="restore-row-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" value="{{ $ruangan->id }}" onchange="updateRestoreSelectedIds()">
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($ruangans->currentPage() - 1) * $ruangans->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#2D5F3F]">
                                {{ $ruangan->kode_ruangan }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $ruangan->nama_ruangan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $ruangan->kapasitas }} Orang
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $fasilitas = is_array($ruangan->fasilitas) ? $ruangan->fasilitas : explode(',', $ruangan->fasilitas ?? '');
                                    @endphp
                                    @foreach(array_slice($fasilitas, 0, 3) as $f)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ trim($f) }}</span>
                                    @endforeach
                                    @if(count($fasilitas) > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">+{{ count($fasilitas) - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($ruangan->is_available)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Tersedia
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Tidak Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if(request('trashed'))
                                        <form action="{{ route('admin.ruangan.restore', $ruangan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 transition" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.ruangan.show', $ruangan->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ruangan.edit', $ruangan->id) }}" class="text-[#D4AF37] hover:text-[#b8941f] transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ruangan.destroy', $ruangan->id) }}" method="POST" class="inline"
                                            x-data
                                            @submit.prevent="if(confirm('Apakah Anda yakin ingin menghapus ruangan ini?')) $el.submit()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ request('trashed') ? 7 : 8 }}" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-door-open text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data ruangan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($ruangans) && $ruangans->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $ruangans->firstItem() }} - {{ $ruangans->lastItem() }} dari {{ $ruangans->total() }} data
                    </div>
                    <div class="flex space-x-1">
                        @if ($ruangans->onFirstPage())
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $ruangans->previousPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @php
                            $current = $ruangans->currentPage();
                            $last = $ruangans->lastPage();
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
                                <a href="{{ $ruangans->url($page) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($ruangans->hasMorePages())
                            <a href="{{ $ruangans->nextPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
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
