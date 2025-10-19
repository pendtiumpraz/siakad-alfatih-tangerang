@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pendaftar</h1>
            <p class="text-gray-600 mt-1">{{ $pendaftar->nomor_pendaftaran }}</p>
        </div>
        <a href="{{ route('admin.spmb.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-semibold">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Status Badge -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-800">Status Pendaftaran:</h3>
                <x-status-badge :status="$pendaftar->status" type="spmb" class="text-lg px-6 py-2" />
            </div>
            @if($pendaftar->status === 'pending')
                <div class="flex space-x-2">
                    <button onclick="showVerifyModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Verifikasi
                    </button>
                    <button onclick="showRejectModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        Tolak
                    </button>
                </div>
            @elseif($pendaftar->status === 'verified')
                <button onclick="showAcceptModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    Terima Pendaftar
                </button>
            @endif
        </div>

        @if($pendaftar->keterangan)
            <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <p class="text-sm font-semibold text-yellow-800">Keterangan:</p>
                <p class="text-sm text-yellow-700 mt-1">{{ $pendaftar->keterangan }}</p>
            </div>
        @endif
    </div>

    <!-- Photo Section -->
    @if($pendaftar->foto)
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Foto Pendaftar</h3>
            <div class="flex justify-center">
                <img src="{{ Storage::url($pendaftar->foto) }}" alt="Foto {{ $pendaftar->nama }}" class="w-48 h-48 object-cover rounded-lg border-4 border-[#D4AF37] shadow-lg">
            </div>
        </div>
    @endif

    <!-- Personal Information -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Data Pribadi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->nama }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">NIK</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->nik }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Email</label>
                <p class="text-gray-900">{{ $pendaftar->email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">No. Telepon</label>
                <p class="text-gray-900">{{ $pendaftar->phone }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Jenis Kelamin</label>
                <p class="text-gray-900">{{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Tempat, Tanggal Lahir</label>
                <p class="text-gray-900">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir->format('d F Y') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Agama</label>
                <p class="text-gray-900">{{ $pendaftar->agama }}</p>
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Alamat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Alamat Lengkap</label>
                <p class="text-gray-900">{{ $pendaftar->alamat }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Kelurahan</label>
                <p class="text-gray-900">{{ $pendaftar->kelurahan }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Kecamatan</label>
                <p class="text-gray-900">{{ $pendaftar->kecamatan }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Kota/Kabupaten</label>
                <p class="text-gray-900">{{ $pendaftar->kota_kabupaten }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Provinsi</label>
                <p class="text-gray-900">{{ $pendaftar->provinsi }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Kode Pos</label>
                <p class="text-gray-900">{{ $pendaftar->kode_pos ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Educational Background -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Latar Belakang Pendidikan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Asal Sekolah</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->asal_sekolah }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Tahun Lulus</label>
                <p class="text-gray-900">{{ $pendaftar->tahun_lulus }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Nilai Rata-rata</label>
                <p class="text-gray-900">{{ $pendaftar->nilai_rata_rata ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Parent Information -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Data Orang Tua</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Nama Ayah</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->nama_ayah }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Nama Ibu</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->nama_ibu }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Pekerjaan Ayah</label>
                <p class="text-gray-900">{{ $pendaftar->pekerjaan_ayah ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Pekerjaan Ibu</label>
                <p class="text-gray-900">{{ $pendaftar->pekerjaan_ibu ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">No. Telepon Orang Tua</label>
                <p class="text-gray-900">{{ $pendaftar->phone_orangtua ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Selection Information -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Informasi Seleksi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Jalur Seleksi</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->jalurSeleksi->nama ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Biaya Pendaftaran</label>
                <p class="text-gray-900 font-semibold text-green-600">Rp {{ number_format($pendaftar->jalurSeleksi->biaya_pendaftaran ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Program Studi Pilihan 1</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->programStudiPilihan1->nama_prodi ?? '-' }}</p>
                <p class="text-sm text-gray-500">{{ $pendaftar->programStudiPilihan1->jenjang ?? '' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Program Studi Pilihan 2</label>
                <p class="text-gray-900 font-semibold">{{ $pendaftar->programStudiPilihan2->nama_prodi ?? '-' }}</p>
                @if($pendaftar->programStudiPilihan2)
                    <p class="text-sm text-gray-500">{{ $pendaftar->programStudiPilihan2->jenjang }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    @if($pendaftar->pembayaranPendaftarans && $pendaftar->pembayaranPendaftarans->count() > 0)
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Riwayat Pembayaran</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Metode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pendaftar->pembayaranPendaftarans as $pembayaran)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst($pembayaran->metode_pembayaran ?? '-') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <x-status-badge :status="$pembayaran->status" type="payment" />
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($pembayaran->bukti_bayar)
                                        <a href="{{ Storage::url($pembayaran->bukti_bayar) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Verify Modal -->
<div id="verifyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Pendaftaran</h3>
            <form method="POST" action="{{ route('admin.spmb.verify', $pendaftar->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Pendaftaran</h3>
            <form method="POST" action="{{ route('admin.spmb.reject', $pendaftar->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="keterangan" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div id="acceptModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Terima Pendaftar</h3>
            <form method="POST" action="{{ route('admin.spmb.accept', $pendaftar->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Program Studi <span class="text-red-500">*</span></label>
                    <select name="program_studi_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Pilih Prodi --</option>
                        <option value="{{ $pendaftar->program_studi_pilihan_1 }}">{{ $pendaftar->programStudiPilihan1->nama_prodi ?? '-' }} (Pilihan 1)</option>
                        @if($pendaftar->program_studi_pilihan_2)
                            <option value="{{ $pendaftar->program_studi_pilihan_2 }}">{{ $pendaftar->programStudiPilihan2->nama_prodi }} (Pilihan 2)</option>
                        @endif
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeAcceptModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Terima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showVerifyModal() {
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }

    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function showAcceptModal() {
        document.getElementById('acceptModal').classList.remove('hidden');
    }

    function closeAcceptModal() {
        document.getElementById('acceptModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const verifyModal = document.getElementById('verifyModal');
        const rejectModal = document.getElementById('rejectModal');
        const acceptModal = document.getElementById('acceptModal');

        if (event.target === verifyModal) {
            closeVerifyModal();
        }
        if (event.target === rejectModal) {
            closeRejectModal();
        }
        if (event.target === acceptModal) {
            closeAcceptModal();
        }
    }
</script>
@endsection
