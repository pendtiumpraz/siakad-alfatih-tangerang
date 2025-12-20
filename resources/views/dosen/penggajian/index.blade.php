@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Riwayat Penggajian</h1>
            <p class="text-gray-600 mt-1">Kelola pengajuan pencairan gaji Anda</p>
        </div>
        <a href="{{ route('dosen.penggajian.create') }}" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
            <i class="fas fa-plus mr-2"></i>
            Ajukan Pencairan Baru
        </a>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">JP Diajukan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">JP Disetujui</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider">Jumlah Dibayar</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($penggajians as $index => $penggajian)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $penggajians->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $penggajian->periode_formatted }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $penggajian->semester ? $penggajian->semester->nama_semester : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center font-medium">
                                {{ (int) $penggajian->total_jam_diajukan }} JP
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center font-medium">
                                {{ $penggajian->total_jam_disetujui ? (int) $penggajian->total_jam_disetujui . ' JP' : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {!! $penggajian->status_badge !!}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right font-medium">
                                {{ $penggajian->jumlah_dibayar ? 'Rp ' . number_format($penggajian->jumlah_dibayar, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('dosen.penggajian.show', $penggajian->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($penggajian->canBeEdited())
                                        <a href="{{ route('dosen.penggajian.edit', $penggajian->id) }}" class="text-[#D4AF37] hover:text-[#b8941f] transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dosen.penggajian.destroy', $penggajian->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada pengajuan pencairan gaji</p>
                                    <p class="text-gray-400 text-sm mt-1">Klik tombol "Ajukan Pencairan Baru" untuk membuat pengajuan</p>
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
