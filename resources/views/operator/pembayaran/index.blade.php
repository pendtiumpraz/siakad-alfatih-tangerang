@extends('layouts.operator')

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
            <a href="{{ route('operator.pembayaran.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pembayaran</span>
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter Pencarian">
        <form method="GET" action="{{ route('operator.pembayaran.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="lunas">Lunas</option>
                    <option value="terlambat">Terlambat</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                <input type="text" name="student" placeholder="Cari NIM atau Nama..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pembayaran</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Semua Jenis</option>
                    <option value="spp">SPP</option>
                    <option value="ukt">UKT</option>
                    <option value="daftar_ulang">Daftar Ulang</option>
                    <option value="wisuda">Wisuda</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="md:col-span-2 lg:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Terapkan Filter
                </button>
                <a href="{{ route('operator.pembayaran.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </x-islamic-card>

    <!-- Payments Table -->
    <x-islamic-card title="Daftar Pembayaran">
        <x-data-table :headers="['NIM', 'Nama', 'Jenis', 'Nominal', 'Jatuh Tempo', 'Tgl Bayar', 'Status', 'Bukti', 'Aksi']">
            @forelse(range(1, 10) as $index)
            <tr class="hover:bg-green-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    2301{{ str_pad($index, 3, '0', STR_PAD_LEFT) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ ['Ahmad Nur', 'Fatimah Zahra', 'Muhammad Ali', 'Khadijah', 'Umar Faruq', 'Aisyah', 'Bilal', 'Sumayah', 'Zaid', 'Hafsah'][$index - 1] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ ['SPP', 'UKT', 'Daftar Ulang', 'SPP', 'Wisuda', 'UKT', 'SPP', 'UKT', 'Daftar Ulang', 'SPP'][$index - 1] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    Rp {{ number_format(2000000 + ($index * 500000), 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ \Carbon\Carbon::now()->addDays($index * 5)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $index % 3 == 0 ? '-' : \Carbon\Carbon::now()->subDays($index)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-status-badge :status="$index % 3 == 0 ? 'pending' : ($index % 4 == 0 ? 'terlambat' : 'lunas')" type="payment" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if($index % 3 != 0)
                    <button class="text-green-600 hover:text-green-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    @else
                    <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('operator.pembayaran.show', $index) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('operator.pembayaran.edit', $index) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <button class="text-red-600 hover:text-red-800" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
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
