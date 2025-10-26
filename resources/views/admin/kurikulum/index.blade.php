@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Master Data Kurikulum" />

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('admin.kurikulum.index') }}" class="flex flex-col gap-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama kurikulum..."
                            class="w-full pl-10 pr-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Program Studi Filter -->
                <div class="w-full md:w-64">
                    <select name="program_studi_id" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                        <option value="">Semua Program Studi</option>
                        @foreach($programStudis ?? [] as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
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
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <div class="flex gap-2">
            <a href="{{ route('admin.kurikulum.create') }}" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Kurikulum
            </a>

            @if(request('trashed'))
                <a href="{{ route('admin.kurikulum.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            @else
                <a href="{{ route('admin.kurikulum.index', ['trashed' => 1]) }}" class="px-6 py-2 bg-[#D4AF37] text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
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

    <!-- Kurikulum Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Program Studi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Kurikulum</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Total SKS</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kurikulums ?? [] as $index => $kurikulum)
                        <tr class="hover:bg-[#F4E5C3] hover:bg-opacity-30 transition {{ $kurikulum->is_active ? 'border-l-4 border-[#D4AF37]' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($kurikulums->currentPage() - 1) * $kurikulums->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div>
                                    <p class="font-semibold text-[#2D5F3F]">{{ $kurikulum->programStudi->nama_prodi ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $kurikulum->programStudi->kode_prodi ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $kurikulum->nama_kurikulum }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $kurikulum->tahun_mulai }} - {{ $kurikulum->tahun_selesai }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                    <i class="fas fa-book-open mr-1"></i>
                                    {{ $kurikulum->total_sks }} SKS
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($kurikulum->is_active)
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if(request('trashed'))
                                        <form action="{{ route('admin.kurikulum.restore', $kurikulum->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[#D4AF37] hover:text-[#b8941f] transition" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.kurikulum.force-delete', $kurikulum->id) }}" method="POST" class="inline"
                                            x-data
                                            @submit.prevent="if(confirm('Apakah Anda yakin ingin menghapus permanen kurikulum ini?')) $el.submit()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Hapus Permanen">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.kurikulum.show', $kurikulum->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.kurikulum.edit', $kurikulum->id) }}" class="text-[#D4AF37] hover:text-[#b8941f] transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.kurikulum.destroy', $kurikulum->id) }}" method="POST" class="inline"
                                            x-data
                                            @submit.prevent="if(confirm('Apakah Anda yakin ingin menghapus kurikulum ini?')) $el.submit()">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-book text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data kurikulum</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($kurikulums) && $kurikulums->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $kurikulums->firstItem() }} - {{ $kurikulums->lastItem() }} dari {{ $kurikulums->total() }} data
                    </div>
                    <div class="flex space-x-1">
                        @if ($kurikulums->onFirstPage())
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $kurikulums->previousPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @php
                            $current = $kurikulums->currentPage();
                            $last = $kurikulums->lastPage();
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
                                <a href="{{ $kurikulums->url($page) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($kurikulums->hasMorePages())
                            <a href="{{ $kurikulums->nextPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
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
