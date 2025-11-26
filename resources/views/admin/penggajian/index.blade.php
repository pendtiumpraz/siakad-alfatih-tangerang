@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Penggajian Dosen</h1>
            <p class="text-gray-600 mt-1">Kelola verifikasi dan pembayaran gaji dosen</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Pencarian</h3>
        <form method="GET" action="{{ route('admin.penggajian.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-[#2D5F3F]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select name="periode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-[#2D5F3F]">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $periode)
                        <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($periode . '-01')->format('F Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Dosen (Nama/NIP)</label>
                <div class="flex space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama atau NIP..." 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-[#2D5F3F]">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white rounded-lg hover:shadow-lg transition font-semibold">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request()->hasAny(['status', 'periode', 'search']))
                        <a href="{{ route('admin.penggajian.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Dosen</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Jam</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($penggajians as $index => $penggajian)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $penggajians->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $penggajian->dosen->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $penggajian->dosen->nidn }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $penggajian->periode_formatted }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex flex-col">
                                    <span class="text-gray-600 text-xs">Diajukan:</span>
                                    <span class="font-semibold text-gray-900">{{ $penggajian->total_jam_diajukan }} jam</span>
                                    @if($penggajian->total_jam_disetujui)
                                        <span class="text-green-600 text-xs mt-1">Disetujui: {{ $penggajian->total_jam_disetujui }} jam</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {!! $penggajian->status_badge !!}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.penggajian.show', $penggajian->id) }}" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($penggajian->canBeVerified())
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">
                                            <i class="fas fa-exclamation-circle mr-1"></i>Perlu Verifikasi
                                        </span>
                                    @elseif($penggajian->canBePaid())
                                        <a href="{{ route('admin.penggajian.payment', $penggajian->id) }}" class="p-2 bg-green-500 text-white rounded hover:bg-green-600 transition" title="Proses Pembayaran">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">Tidak ada pengajuan penggajian</p>
                                    <p class="text-gray-400 text-sm mt-1">Belum ada dosen yang mengajukan pencairan gaji</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($penggajians->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $penggajians->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
