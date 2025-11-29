@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 mb-6 border-2 border-[#D4AF37]">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-[#D4AF37] rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Pembukuan Keuangan</h1>
                    <p class="text-emerald-50 text-sm">{{ $semester->nama_semester ?? 'Semua Semester' }}</p>
                </div>
            </div>
            <a href="{{ route('admin.keuangan.create') }}" class="px-6 py-2 bg-[#D4AF37] hover:bg-yellow-600 text-white font-semibold rounded-lg transition flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Input Manual</span>
            </a>
        </div>
    </div>

    <!-- Semester Filter -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('admin.keuangan.index') }}" class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Semester</label>
                <select name="semester_id" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]" onchange="this.form.submit()">
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ $semester->id == $sem->id ? 'selected' : '' }}>
                            {{ $sem->nama_semester }}@if($sem->is_active) ⭐ (Aktif)@endif
                        </option>
                    @endforeach
                </select>
            </div>
            <a href="{{ route('admin.keuangan.show', $semester->id) }}" class="px-6 py-2 bg-[#2D5F3F] hover:bg-[#4A7C59] text-white font-semibold rounded-lg transition">
                <i class="fas fa-eye mr-2"></i>Detail
            </a>
        </form>
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
            <p class="text-xs opacity-75 mt-1">Saldo periode sebelumnya</p>
        </div>

        <!-- Total Pemasukan -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Total Pemasukan</h3>
                <i class="fas fa-arrow-down text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">{{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">
                <i class="fas fa-circle text-xs mr-1"></i>
                SPMB: Rp {{ number_format($summary['breakdown']['pemasukan']['spmb_daftar_ulang'] ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs opacity-75">
                <i class="fas fa-circle text-xs mr-1"></i>
                SPP: Rp {{ number_format($summary['breakdown']['pemasukan']['spp'] ?? 0, 0, ',', '.') }}
            </p>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Total Pengeluaran</h3>
                <i class="fas fa-arrow-up text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">{{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">
                <i class="fas fa-circle text-xs mr-1"></i>
                Gaji: Rp {{ number_format($summary['breakdown']['pengeluaran']['gaji_dosen'] ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs opacity-75">
                <i class="fas fa-circle text-xs mr-1"></i>
                Lain-lain: Rp {{ number_format($summary['breakdown']['pengeluaran']['lain_lain'] ?? 0, 0, ',', '.') }}
            </p>
        </div>

        <!-- Saldo Akhir -->
        <div class="bg-gradient-to-br from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-lg p-6 text-white border-2 border-[#D4AF37]">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Saldo Akhir</h3>
                <i class="fas fa-money-bill-wave text-2xl opacity-75"></i>
            </div>
            <p class="text-3xl font-bold">{{ number_format($summary['saldo_akhir'], 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-1">Saldo periode ini</p>
        </div>
    </div>

    <!-- Chart Placeholder -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Keuangan (6 Semester Terakhir)</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <div class="text-center text-gray-500">
                <i class="fas fa-chart-bar text-5xl mb-2"></i>
                <p>Chart.js akan ditambahkan di sini</p>
                <p class="text-sm">Data sudah tersedia di $chartData</p>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-lg font-bold text-white">Transaksi Terbaru (15 Terakhir)</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
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
                    @forelse($recentTransactions as $transaction)
                        <tr class="hover:bg-gray-50">
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
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $transaction->keterangan }}">
                                {{ $transaction->keterangan }}
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
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada transaksi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
