@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Master Data Semester" />

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('admin.semester.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari tahun akademik..."
                        class="w-full pl-10 pr-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select name="status" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
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
            <a href="{{ route('admin.semester.create') }}" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Semester
            </a>
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

    <!-- Active Semester Alert -->
    @if(isset($activeSemester))
    <div class="bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] rounded-lg p-4 border-2 border-[#D4AF37]">
        <div class="flex items-center">
            <i class="fas fa-star text-[#2D5F3F] text-2xl mr-3"></i>
            <div>
                <p class="font-semibold text-[#2D5F3F]">Semester Aktif Saat Ini</p>
                <p class="text-sm text-gray-700">{{ $activeSemester->nama_semester }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Semester Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tahun Akademik</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($semesters ?? [] as $index => $semester)
                        <tr class="hover:bg-[#F4E5C3] hover:bg-opacity-30 transition {{ $semester->is_active ? 'bg-[#F4E5C3] bg-opacity-20 border-l-4 border-[#D4AF37]' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($semesters->currentPage() - 1) * $semesters->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div>
                                    <p class="font-semibold text-[#2D5F3F]">{{ $semester->nama_semester }}</p>
                                    <span class="px-2 py-1 {{ $semester->jenis == 'ganjil' ? 'bg-blue-100 text-blue-800' : ($semester->jenis == 'genap' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800') }} text-xs font-semibold rounded-full">
                                        {{ ucfirst($semester->jenis) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-[#2D5F3F]">
                                {{ $semester->tahun_akademik }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="fas fa-calendar-alt text-[#D4AF37] mr-1"></i>
                                {{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($semester->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($semester->is_active)
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
                                    <a href="{{ route('admin.semester.show', $semester->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.semester.edit', $semester->id) }}" class="text-[#D4AF37] hover:text-[#b8941f] transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.semester.destroy', $semester->id) }}" method="POST" class="inline"
                                        x-data
                                        @submit.prevent="if(confirm('Apakah Anda yakin ingin menghapus semester ini?')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-calendar-alt text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data semester</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($semesters) && $semesters->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $semesters->firstItem() }} - {{ $semesters->lastItem() }} dari {{ $semesters->total() }} data
                    </div>
                    <div class="flex space-x-1">
                        @if ($semesters->onFirstPage())
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $semesters->previousPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @foreach ($semesters->getUrlRange(1, $semesters->lastPage()) as $page => $url)
                            @if ($page == $semesters->currentPage())
                                <span class="px-4 py-2 bg-[#2D5F3F] text-white rounded font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($semesters->hasMorePages())
                            <a href="{{ $semesters->nextPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
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
