@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 mb-6 border-2 border-[#D4AF37]">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-[#D4AF37] rounded-full flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Detail Pembukuan Keuangan</h1>
                    <p class="text-emerald-50 text-sm">{{ $semester->nama_semester }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.keuangan.create') }}" class="px-4 py-2 bg-[#D4AF37] hover:bg-yellow-600 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Input Manual
                </a>
                <a href="{{ route('admin.keuangan.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Saldo Awal -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Saldo Awal</h3>
                <i class="fas fa-wallet text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">{{ number_format($summary['saldo_awal'], 0, ',', '.') }}</p>
        </div>

        <!-- Total Pemasukan -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Total Pemasukan</h3>
                <i class="fas fa-arrow-down text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">{{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</p>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Total Pengeluaran</h3>
                <i class="fas fa-arrow-up text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">{{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</p>
        </div>

        <!-- Saldo Akhir -->
        <div class="bg-gradient-to-br from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-lg p-6 text-white border-2 border-[#D4AF37]">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Saldo Akhir</h3>
                <i class="fas fa-money-bill-wave text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">{{ number_format($summary['saldo_akhir'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Breakdown by Kategori -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        
        <!-- Pemasukan Breakdown -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-arrow-down mr-2"></i>
                    Rincian Pemasukan
                </h3>
            </div>
            <div class="p-6">
                @foreach($summary['breakdown']['pemasukan'] as $kategori => $nominal)
                    <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                        <span class="text-gray-700 font-medium">
                            @if($kategori === 'spmb_daftar_ulang')
                                SPMB & Daftar Ulang
                            @elseif($kategori === 'spp')
                                SPP
                            @else
                                Lain-lain
                            @endif
                        </span>
                        <span class="text-green-600 font-bold">
                            Rp {{ number_format($nominal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
                @if(empty($summary['breakdown']['pemasukan']))
                    <p class="text-gray-500 text-center py-4">Belum ada pemasukan</p>
                @endif
            </div>
        </div>

        <!-- Pengeluaran Breakdown -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-arrow-up mr-2"></i>
                    Rincian Pengeluaran
                </h3>
            </div>
            <div class="p-6">
                @foreach($summary['breakdown']['pengeluaran'] as $kategori => $nominal)
                    <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                        <span class="text-gray-700 font-medium">
                            @if($kategori === 'gaji_dosen')
                                Gaji Dosen
                            @else
                                Lain-lain
                            @endif
                        </span>
                        <span class="text-red-600 font-bold">
                            Rp {{ number_format($nominal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
                @if(empty($summary['breakdown']['pengeluaran']))
                    <p class="text-gray-500 text-center py-4">Belum ada pengeluaran</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="semester_id" value="{{ $semester->id }}">
            
            <!-- Jenis Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                    <option value="">-- Semua --</option>
                    <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <!-- Kategori Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                <select name="kategori" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                    <option value="">-- Semua --</option>
                    <option value="spmb_daftar_ulang" {{ request('kategori') == 'spmb_daftar_ulang' ? 'selected' : '' }}>SPMB & Daftar Ulang</option>
                    <option value="spp" {{ request('kategori') == 'spp' ? 'selected' : '' }}>SPP</option>
                    <option value="gaji_dosen" {{ request('kategori') == 'gaji_dosen' ? 'selected' : '' }}>Gaji Dosen</option>
                    <option value="lain_lain" {{ request('kategori') == 'lain_lain' ? 'selected' : '' }}>Lain-lain</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="is_otomatis" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                    <option value="">-- Semua --</option>
                    <option value="1" {{ request('is_otomatis') == '1' ? 'selected' : '' }}>Otomatis</option>
                    <option value="0" {{ request('is_otomatis') == '0' ? 'selected' : '' }}>Manual</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.keuangan.show', $semester->id) }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-lg font-bold text-white">Semua Transaksi - {{ $semester->nama_semester }}</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Nominal</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $index => $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $transactions->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $transaction->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($transaction->jenis === 'pemasukan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-down mr-1"></i>
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $transaction->kategori_label }}
                                @if($transaction->sub_kategori)
                                    <br><span class="text-xs text-gray-500">{{ $transaction->sub_kategori }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="line-clamp-2" title="{{ $transaction->keterangan }}">
                                    {{ $transaction->keterangan }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold {{ $transaction->jenis === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->jenis === 'pemasukan' ? '+' : '-' }}
                                Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($transaction->is_otomatis)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-robot mr-1"></i>
                                        Otomatis
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-hand-pointer mr-1"></i>
                                        Manual
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if(!$transaction->is_otomatis)
                                    <a href="{{ route('admin.keuangan.edit', $transaction->id) }}" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.keuangan.destroy', $transaction->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus (Soft Delete)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">
                                        <i class="fas fa-lock" title="Tidak dapat diedit (otomatis)"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada transaksi untuk semester ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="px-6 py-4 bg-gray-50">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
