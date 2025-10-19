@extends('layouts.mahasiswa')

@section('title', 'Pembayaran')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Pembayaran</h1>
    </div>

    <div class="islamic-divider"></div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('mahasiswa.pembayaran.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div>
                <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester_id" id="semester_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->nama_semester }} - {{ $semester->tahun_akademik }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#C4A137] transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if($pembayarans->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pembayarans as $pembayaran)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pembayaran->semester->nama_semester ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pembayaran->status == 'lunas')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                @elseif($pembayaran->status == 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    @if($pembayaran->bukti_pembayaran)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pembayaran->bukti_pembayaran) }}"
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Bukti
                                        </a>
                                        @if($pembayaran->status != 'lunas')
                                            <button onclick="openUploadModal({{ $pembayaran->id }})"
                                                    class="text-green-600 hover:text-green-800 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                Ganti
                                            </button>
                                        @endif
                                    @else
                                        @if($pembayaran->status != 'lunas')
                                            <button onclick="openUploadModal({{ $pembayaran->id }})"
                                                    class="px-3 py-1 bg-[#D4AF37] text-white rounded hover:bg-[#C4A137] transition-colors inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                Upload Bukti
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pembayarans->links() }}
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-yellow-700">Belum ada data pembayaran.</p>
        </div>
    @endif
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative mx-auto p-6 border border-gray-200 w-full max-w-md shadow-xl rounded-lg bg-gray-50">
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Upload Bukti Pembayaran</h3>
            <form id="uploadForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File Bukti Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <input type="file"
                           name="bukti_pembayaran"
                           accept=".jpg,.jpeg,.png,.pdf"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, PDF (Max: 2MB)</p>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button"
                            onclick="closeUploadModal()"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-[#D4AF37] text-white rounded hover:bg-[#C4A137] transition-colors">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUploadModal(pembayaranId) {
    const modal = document.getElementById('uploadModal');
    const form = document.getElementById('uploadForm');
    form.action = `/mahasiswa/pembayaran/${pembayaranId}/upload`;
    modal.classList.remove('hidden');
}

function closeUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.add('hidden');
    document.getElementById('uploadForm').reset();
}

// Close modal when clicking outside
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});
</script>
@endsection
