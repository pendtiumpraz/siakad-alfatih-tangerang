@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pengumuman</h1>
            <p class="text-gray-600 mt-1">Kelola pengumuman untuk mahasiswa</p>
        </div>
        <a href="{{ route('operator.pengumuman.create') }}" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
            <i class="fas fa-plus mr-2"></i>
            Buat Pengumuman
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Pembuat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pengumumans as $index => $pengumuman)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $pengumumans->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $pengumuman->judul }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $tipeColors = [
                                        'info' => 'bg-blue-100 text-blue-800',
                                        'penting' => 'bg-red-100 text-red-800',
                                        'pengingat' => 'bg-yellow-100 text-yellow-800',
                                        'kegiatan' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $tipeColors[$pengumuman->tipe] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($pengumuman->tipe) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $pengumuman->pembuat->username ?? '-' }}
                                <span class="text-xs text-gray-400">({{ ucfirst(str_replace('_', ' ', $pengumuman->pembuat_role)) }})</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $pengumuman->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($pengumuman->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('operator.pengumuman.edit', $pengumuman->id) }}" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('operator.pengumuman.destroy', $pengumuman->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-bullhorn text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada pengumuman</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pengumumans->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pengumumans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
