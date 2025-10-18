@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Master Data Mata Kuliah</h1>
    </div>

    <div class="islamic-divider"></div>

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('dosen.mata-kuliah.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i> Cari Mata Kuliah
                    </label>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Kode atau Nama Mata Kuliah..."
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent transition-all">
                </div>

                <!-- Kurikulum Filter -->
                <div>
                    <label for="kurikulum_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-1"></i> Kurikulum
                    </label>
                    <select name="kurikulum_id"
                            id="kurikulum_id"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent">
                        <option value="">Semua Kurikulum</option>
                        @foreach($kurikulums as $kurikulum)
                            <option value="{{ $kurikulum->id }}" {{ request('kurikulum_id') == $kurikulum->id ? 'selected' : '' }}>
                                {{ $kurikulum->nama_kurikulum }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester Filter -->
                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Semester
                    </label>
                    <select name="semester"
                            id="semester"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent">
                        <option value="">Semua Semester</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Jenis Filter -->
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i> Jenis
                    </label>
                    <select name="jenis"
                            id="jenis"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="wajib" {{ request('jenis') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                        <option value="pilihan" {{ request('jenis') == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 justify-end">
                <a href="{{ route('dosen.mata-kuliah.index') }}"
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center space-x-2">
                    <i class="fas fa-redo"></i>
                    <span>Reset</span>
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#C4A137] transition-colors flex items-center space-x-2">
                    <i class="fas fa-filter"></i>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        @if($mataKuliahs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#3D7F5F]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Kode MK
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Nama Mata Kuliah
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Kurikulum
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                                SKS
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                                Semester
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                                Jenis
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($mataKuliahs as $mataKuliah)
                            <tr class="hover:bg-gray-50 transition-colors {{ $mataKuliah->deleted_at ? 'bg-red-50 opacity-60' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $mataKuliah->kode_mk }}
                                    @if($mataKuliah->deleted_at)
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Dihapus</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $mataKuliah->nama_mk }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $mataKuliah->kurikulum->nama_kurikulum ?? '-' }}
                                    @if($mataKuliah->kurikulum && $mataKuliah->kurikulum->programStudi)
                                        <br>
                                        <span class="text-xs text-gray-400">{{ $mataKuliah->kurikulum->programStudi->nama_prodi }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $mataKuliah->sks }} SKS
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                    {{ $mataKuliah->semester }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($mataKuliah->jenis == 'Wajib')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#D4AF37] text-white">
                                            <i class="fas fa-star mr-1"></i> Wajib
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-list-ul mr-1"></i> Pilihan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $mataKuliahs->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-inbox text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-lg">Tidak ada data mata kuliah ditemukan</p>
                <p class="text-gray-400 text-sm mt-2">Coba ubah filter atau kata kunci pencarian</p>
            </div>
        @endif
    </div>
</div>
@endsection
