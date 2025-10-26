@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Pembayaran</h1>
            <p class="text-gray-600 mt-1">Kelola data pembayaran mahasiswa</p>
        </div>
        <div class="flex space-x-3">
            <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Export</span>
            </button>
            <a href="{{ route('admin.pembayaran.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pembayaran</span>
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter Pencarian">
        <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pembayaran</label>
                <select name="jenis_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Jenis</option>
                    <option value="spp" {{ request('jenis_pembayaran') == 'spp' ? 'selected' : '' }}>SPP</option>
                    <option value="daftar_ulang" {{ request('jenis_pembayaran') == 'daftar_ulang' ? 'selected' : '' }}>Daftar Ulang</option>
                    <option value="wisuda" {{ request('jenis_pembayaran') == 'wisuda' ? 'selected' : '' }}>Wisuda</option>
                    <option value="lainnya" {{ request('jenis_pembayaran') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Semester</option>
                    @foreach($semesters ?? [] as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->tahun_akademik }} - {{ ucfirst($semester->jenis) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM atau Nama..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="md:col-span-2 lg:col-span-2 flex items-end">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="include_deleted" value="1" {{ request('include_deleted') ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                    <span class="ml-2 text-sm text-gray-700">Tampilkan data terhapus</span>
                </label>
            </div>

            <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.pembayaran.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </x-islamic-card>

    <!-- Payments Table -->
    <x-islamic-card title="Daftar Pembayaran">
        <x-data-table :headers="['NIM', 'Nama', 'Jenis', 'Nominal', 'Jatuh Tempo', 'Tgl Bayar', 'Status', 'Bukti', 'Aksi']">
            @forelse($pembayarans ?? [] as $pembayaran)
            <tr class="hover:bg-green-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $pembayaran->mahasiswa->nim ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $pembayaran->mahasiswa->nama_lengkap ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ ucwords(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $pembayaran->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('d/m/Y') : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-status-badge :status="$pembayaran->status" type="payment" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if($pembayaran->google_drive_link || $pembayaran->bukti_pembayaran)
                        <a href="{{ $pembayaran->google_drive_link ?? \Illuminate\Support\Facades\Storage::url($pembayaran->bukti_pembayaran) }}" target="_blank" class="text-green-600 hover:text-green-800 flex items-center justify-center gap-1" title="Lihat Bukti">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            @if($pembayaran->google_drive_link)
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24" title="Google Drive">
                                    <path d="M12.01 1.485c-2.082 0-3.754.02-3.743.047.011.024 1.793 3.099 3.959 6.833l3.938 6.78-2.258 3.905c-1.242 2.148-2.25 3.919-2.241 3.936.013.024 1.653.042 3.646.042H19l2.24-3.878c1.232-2.133 2.231-3.906 2.22-3.942-.013-.036-1.802-3.138-3.975-6.894zm-2.555 5.894c-1.425-2.479-2.613-4.521-2.638-4.537-.038-.023-1.715 2.851-5.088 8.721l-1.067 1.859 2.255 3.904c1.241 2.148 2.259 3.893 2.263 3.879.004-.014 1.212-2.063 2.683-4.553l2.675-4.527z"/>
                                </svg>
                            @endif
                        </a>
                    @else
                        <span class="text-gray-400 text-xs">Belum ada</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex items-center space-x-2">
                        @if($pembayaran->trashed())
                            <!-- Restore Button -->
                            <form action="{{ route('admin.pembayaran.restore', $pembayaran->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800" title="Restore" onclick="return confirm('Restore pembayaran ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <!-- Detail Button -->
                            <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>

                            <!-- Edit Button -->
                            <a href="{{ route('admin.pembayaran.edit', $pembayaran->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <!-- Soft Delete Button -->
                            <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus" onclick="return confirm('Hapus pembayaran ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                    Tidak ada data pembayaran
                </td>
            </tr>
            @endforelse
        </x-data-table>

        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-between">
            <p class="text-sm text-gray-600">Menampilkan 1-10 dari 247 data</p>
            <div class="flex space-x-2">
                <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">Previous</button>
                <button class="px-3 py-1 bg-green-600 text-white rounded">1</button>
                <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">2</button>
                <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">3</button>
                <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded hover:bg-gray-300 transition-colors">Next</button>
            </div>
        </div>
    </x-islamic-card>
</div>
@endsection
