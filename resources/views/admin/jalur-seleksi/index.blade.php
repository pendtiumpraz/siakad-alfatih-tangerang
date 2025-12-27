@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Jalur Seleksi</h1>
            <p class="text-gray-600 mt-1">Kelola jalur seleksi dan biaya pendaftaran</p>
        </div>
        <a href="{{ route('admin.jalur-seleksi.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
            <i class="fas fa-plus mr-2"></i>Tambah Jalur Seleksi
        </a>
    </div>

    <!-- Batch Delete Actions -->
    @include('components.batch-delete-actions', ['routeName' => route('admin.jalur-seleksi.batch-delete')])

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jalur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya Pendaftaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jalurSeleksis as $jalur)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4">
                                <input type="checkbox" class="row-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" value="{{ $jalur->id }}" onchange="updateSelectedIds()">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-sm font-semibold text-gray-900">{{ $jalur->kode_jalur }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $jalur->nama }}</div>
                                @if($jalur->deskripsi)
                                    <div class="text-sm text-gray-500 mt-1">{{ Str::limit($jalur->deskripsi, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-green-600">Rp {{ number_format($jalur->biaya_pendaftaran, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $jalur->kuota_total ?? 'Unlimited' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $jalur->tanggal_mulai?->format('d/m/Y') ?? '-' }}</div>
                                <div class="text-gray-500">s/d {{ $jalur->tanggal_selesai?->format('d/m/Y') ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($jalur->is_active)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-ban mr-1"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.jalur-seleksi.edit', $jalur) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.jalur-seleksi.destroy', $jalur) }}" method="POST" class="inline" onsubmit="return confirmDelete(this, 'jalur seleksi ini')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-inbox text-6xl mb-4"></i>
                                    <p class="text-lg font-medium">Belum ada jalur seleksi</p>
                                    <a href="{{ route('admin.jalur-seleksi.create') }}" class="mt-4 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-plus mr-2"></i>Tambah Jalur Seleksi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jalurSeleksis->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $jalurSeleksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
