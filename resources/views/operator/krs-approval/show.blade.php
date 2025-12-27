@extends('layouts.operator')

@section('title', 'Detail KRS - ' . $mahasiswa->nama_lengkap)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center text-sm text-gray-600 mb-6">
        <a href="{{ route('operator.krs-approval.index', ['semester_id' => $semester->id]) }}" class="hover:text-blue-600">
            Approval KRS
        </a>
        <span class="mx-2">/</span>
        <a href="{{ route('operator.krs-approval.detail', ['prodiId' => $mahasiswa->program_studi_id, 'semester_id' => $semester->id]) }}" class="hover:text-blue-600">
            {{ $mahasiswa->programStudi->nama_prodi }}
        </a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-semibold">{{ $mahasiswa->nim }}</span>
    </div>

    @if(session('success'))
        <div class="border px-6 py-4 rounded-lg relative mb-6" role="alert" style="background-color: #DCFCE7; border-color: #4ADE80; color: #15803D;">
            <span class="font-semibold">✅ {{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg relative mb-6" role="alert">
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Mahasiswa Info Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Left Column - Mahasiswa Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #2D5F3F, #4A7C59);">
                    <h3 class="text-lg font-bold text-white">Informasi Mahasiswa</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm text-gray-600">NIM</label>
                        <p class="font-semibold text-gray-900">{{ $mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Nama Lengkap</label>
                        <p class="font-semibold text-gray-900">{{ $mahasiswa->nama_lengkap }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Program Studi</label>
                        <p class="font-semibold text-gray-900">{{ $mahasiswa->programStudi->nama_prodi }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Semester</label>
                        <p class="font-semibold text-gray-900">{{ $mahasiswa->semester_aktif ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status SPP</label>
                        @if($sppPayment && $sppPayment->status === 'lunas')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                ✅ Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                ❌ Belum Bayar
                            </span>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Total SKS Diambil</label>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalSks }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status KRS</label>
                        @php
                            $krsStatus = $krsItems->first()?->status ?? 'not_submitted';
                        @endphp
                        @if($krsStatus === 'submitted')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                ⏳ Pending Approval
                            </span>
                        @elseif($krsStatus === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                ✅ Approved
                            </span>
                            @if($krsItems->first()->approved_at)
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $krsItems->first()->approved_at->format('d M Y H:i') }}
                                    oleh {{ $krsItems->first()->approvedBy->name ?? '-' }}
                                </p>
                            @endif
                        @elseif($krsStatus === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                ❌ Rejected
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                📝 Belum Submit
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($krsStatus === 'submitted')
                <div class="mt-6 space-y-3">
                    <!-- Approve Button (Normal) -->
                    @if($sppPayment && $sppPayment->status === 'lunas')
                        <form action="{{ route('operator.krs-approval.approve', $mahasiswa->id) }}" method="POST"
                              onsubmit="return confirmApprove(this, 'KRS mahasiswa ini')">
                            @csrf
                            <input type="hidden" name="semester_id" value="{{ $semester->id }}">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Approve KRS
                            </button>
                        </form>
                    @else
                        <!-- Warning + Force Approve Option -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-red-800 mb-1">Mahasiswa Belum Bayar SPP</h4>
                                    <p class="text-xs text-red-700">Tidak bisa approve secara normal. Gunakan Force Approve untuk kasus khusus (terlambat, beasiswa, dll).</p>
                                </div>
                            </div>
                        </div>

                        <!-- Force Approve Form -->
                        <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-yellow-800 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Force Approve (Override)
                            </h4>
                            <form action="{{ route('operator.krs-approval.approve', $mahasiswa->id) }}" method="POST"
                                  onsubmit="return confirmForceApprove(this)">
                                @csrf
                                <input type="hidden" name="semester_id" value="{{ $semester->id }}">
                                <input type="hidden" name="force_approve" value="1">
                                
                                <div class="mb-3">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Force Approve: <span class="text-red-600">*</span></label>
                                    <textarea name="keterangan" 
                                              rows="3" 
                                              required
                                              placeholder="Contoh: Mahasiswa telat submit, disetujui khusus..."
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500"></textarea>
                                </div>
                                
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Force Approve
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Reject Button -->
                    <button type="button" 
                            onclick="showRejectModal()"
                            class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject KRS
                    </button>
                </div>
            @endif
        </div>

        <!-- Right Column - KRS Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4" style="background: linear-gradient(to right, #2D5F3F, #4A7C59);">
                    <h3 class="text-lg font-bold text-white">Daftar Mata Kuliah ({{ $krsItems->count() }} MK)</h3>
                </div>

                @if($krsItems->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">KRS Masih Kosong</h3>
                        <p class="text-gray-500">Mahasiswa belum mengisi KRS.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($krsItems as $index => $krs)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-gray-900">{{ $krs->mataKuliah->kode_mk }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $krs->mataKuliah->nama_mk }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-sm font-bold" style="color: #2D5F3F;">{{ $krs->mataKuliah->sks }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($krs->is_mengulang)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                                    Mengulang
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    {{ ucfirst($krs->mataKuliah->jenis) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $jadwal = $krs->mataKuliah->jadwals->first();
                                            @endphp
                                            @if($jadwal)
                                                <div class="text-sm">
                                                    <div class="font-semibold text-gray-900">{{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</div>
                                                    <div class="text-gray-600">{{ $jadwal->ruangan->nama_ruangan ?? '-' }}</div>
                                                    <div class="text-gray-500">{{ $jadwal->dosen->nama_lengkap ?? '-' }}</div>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">Belum ada jadwal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                        Total SKS:
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-lg font-bold" style="color: #2D5F3F;">{{ $totalSks }}</span>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($krsItems->first() && $krsItems->first()->keterangan)
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <label class="text-xs font-semibold text-gray-600">Keterangan:</label>
                            <p class="text-sm text-gray-800 mt-1">{{ $krsItems->first()->keterangan }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Reject KRS</h3>
                <button onclick="hideRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('operator.krs-approval.reject', $mahasiswa->id) }}" method="POST">
                @csrf
                <input type="hidden" name="semester_id" value="{{ $semester->id }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alasan Reject: <span class="text-red-600">*</span>
                    </label>
                    <textarea name="keterangan" 
                              rows="4" 
                              required
                              placeholder="Jelaskan alasan reject KRS ini..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="hideRejectModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">
                        Reject KRS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRejectModal();
    }
});
</script>
@endpush
@endsection

