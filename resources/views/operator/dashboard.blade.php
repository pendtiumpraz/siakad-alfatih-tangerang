@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Operator Keuangan</h1>
            <p class="text-gray-600 mt-1">Selamat datang di sistem informasi akademik STAI AL-FATIH</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
        </div>
    </div>

    <!-- Islamic Decorative Divider -->
    <div class="flex items-center justify-center py-2">
        <div class="flex space-x-2">
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card
            title="Total Pembayaran"
            value="1,247"
            color="green"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z\'/><path fill-rule=\'evenodd\' d=\'M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z\' clip-rule=\'evenodd\'/></svg>'"
        />

        <x-stat-card
            title="Pending"
            value="45"
            color="yellow"
            subtext="Menunggu verifikasi"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path fill-rule=\'evenodd\' d=\'M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z\' clip-rule=\'evenodd\'/></svg>'"
        />

        <x-stat-card
            title="Lunas"
            value="1,189"
            color="green"
            subtext="Pembayaran selesai"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path fill-rule=\'evenodd\' d=\'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z\' clip-rule=\'evenodd\'/></svg>'"
        />

        <x-stat-card
            title="Terlambat"
            value="13"
            color="red"
            subtext="Melewati jatuh tempo"
            :icon="'<svg class=\'w-8 h-8\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path fill-rule=\'evenodd\' d=\'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z\' clip-rule=\'evenodd\'/></svg>'"
        />
    </div>

    <!-- Total Revenue Card -->
    <x-islamic-card class="border-t-4 border-yellow-600">
        <div class="bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-lg p-8 text-center border-2 border-yellow-400">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Pendapatan</h3>
            <p class="text-5xl font-bold text-yellow-800">Rp 8.547.250.000</p>
            <p class="text-sm text-gray-600 mt-2">Tahun Akademik 2024/2025</p>
            <div class="mt-6 flex justify-center space-x-8">
                <div>
                    <p class="text-xs text-gray-600">Semester Ganjil</p>
                    <p class="text-xl font-bold text-green-700">Rp 4.5 M</p>
                </div>
                <div class="w-px bg-yellow-400"></div>
                <div>
                    <p class="text-xs text-gray-600">Semester Genap</p>
                    <p class="text-xl font-bold text-green-700">Rp 4.0 M</p>
                </div>
            </div>
        </div>
    </x-islamic-card>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Verification Section -->
        <x-islamic-card title="Pembayaran Pending Verifikasi">
            <div class="space-y-3">
                @forelse(range(1, 5) as $index)
                <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-gray-800">Ahmad Nur Rahman</span>
                            <span class="text-xs text-gray-500">2301001</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">SPP Semester 3 - Rp 2.500.000</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::now()->subMinutes($index * 15)->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-status-badge status="pending" type="payment" />
                        <a href="{{ route('operator.pembayaran.show', 1) }}" class="px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition-colors">
                            Verifikasi
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Tidak ada pembayaran yang menunggu verifikasi</p>
                @endforelse

                <div class="pt-3 border-t border-gray-200">
                    <a href="{{ route('operator.pembayaran.index') }}" class="block text-center text-green-600 hover:text-green-800 font-medium text-sm">
                        Lihat Semua Pembayaran
                    </a>
                </div>
            </div>
        </x-islamic-card>

        <!-- Recent Payments -->
        <x-islamic-card title="Pembayaran Terbaru">
            <div class="space-y-3">
                @forelse(range(1, 5) as $index)
                <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-gray-800">Fatimah Azzahra</span>
                            <span class="text-xs text-gray-500">2301{{ str_pad($index, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">UKT Semester {{ $index }} - Rp {{ number_format(3000000 + ($index * 100000), 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::now()->subDays($index)->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <x-status-badge status="lunas" type="payment" />
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Belum ada pembayaran</p>
                @endforelse

                <div class="pt-3 border-t border-gray-200">
                    <a href="{{ route('operator.pembayaran.index') }}" class="block text-center text-green-600 hover:text-green-800 font-medium text-sm">
                        Lihat Riwayat Lengkap
                    </a>
                </div>
            </div>
        </x-islamic-card>
    </div>

    <!-- Quick Stats -->
    <x-islamic-card title="Statistik Pembayaran Bulan Ini">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                <p class="text-2xl font-bold text-blue-700">342</p>
                <p class="text-xs text-gray-600 mt-1">SPP</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                <p class="text-2xl font-bold text-green-700">245</p>
                <p class="text-xs text-gray-600 mt-1">UKT</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                <p class="text-2xl font-bold text-yellow-700">89</p>
                <p class="text-xs text-gray-600 mt-1">Daftar Ulang</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                <p class="text-2xl font-bold text-purple-700">47</p>
                <p class="text-xs text-gray-600 mt-1">Wisuda</p>
            </div>
        </div>
    </x-islamic-card>
</div>
@endsection
