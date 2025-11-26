@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pengajuan Penggajian</h1>
            <p class="text-gray-600 mt-1">{{ $penggajian->dosen->nama_lengkap }} - {{ $penggajian->periode_formatted }}</p>
        </div>
        <div class="flex space-x-2">
            @if($penggajian->canBePaid())
                <a href="{{ route('admin.penggajian.payment', $penggajian->id) }}" class="px-6 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    Proses Pembayaran
                </a>
            @endif
            <a href="{{ route('admin.penggajian.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
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

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
            <p class="font-medium mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dosen Info -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user-tie mr-2 text-[#2D5F3F]"></i>
                    Informasi Dosen
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIP:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nidn }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->no_telepon ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Rekening -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-university mr-2 text-[#2D5F3F]"></i>
                    Data Rekening
                </h2>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nama Bank:</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nama_bank ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nomor Rekening:</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nomor_rekening ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Atas Nama:</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nama_lengkap }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengajuan Detail -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-invoice mr-2 text-[#2D5F3F]"></i>
                    Detail Pengajuan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Periode</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $penggajian->periode_formatted }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Semester</p>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $penggajian->semester ? $penggajian->semester->jenis . ' ' . $penggajian->semester->tahun_ajaran : '-' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        {!! $penggajian->status_badge !!}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Total Jam Diajukan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $penggajian->total_jam_diajukan }}</p>
                        <p class="text-sm text-gray-500">jam</p>
                    </div>
                    @if($penggajian->total_jam_disetujui)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Total Jam Disetujui</p>
                            <p class="text-3xl font-bold text-green-600">{{ $penggajian->total_jam_disetujui }}</p>
                            <p class="text-sm text-gray-500">jam</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-[#2D5F3F]"></i>
                    Dokumen untuk Verifikasi
                </h2>
                <div class="space-y-3">
                    <a href="{{ $penggajian->link_rps }}" target="_blank" class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-800">RPS (Rencana Pembelajaran Semester)</p>
                                <p class="text-sm text-gray-600">Klik untuk membuka di Google Drive</p>
                            </div>
                        </div>
                        <i class="fas fa-external-link-alt text-blue-600"></i>
                    </a>

                    <a href="{{ $penggajian->link_materi_ajar }}" target="_blank" class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-book text-green-500 text-2xl mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Materi Ajar</p>
                                <p class="text-sm text-gray-600">Klik untuk membuka di Google Drive</p>
                            </div>
                        </div>
                        <i class="fas fa-external-link-alt text-green-600"></i>
                    </a>

                    <a href="{{ $penggajian->link_absensi }}" target="_blank" class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-clipboard-list text-yellow-500 text-2xl mr-3"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Absensi Mahasiswa</p>
                                <p class="text-sm text-gray-600">Klik untuk membuka di Google Drive</p>
                            </div>
                        </div>
                        <i class="fas fa-external-link-alt text-yellow-600"></i>
                    </a>
                </div>
            </div>

            <!-- Catatan Dosen -->
            @if($penggajian->catatan_dosen)
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-comment mr-2 text-[#2D5F3F]"></i>
                        Catatan Dosen
                    </h2>
                    <p class="text-gray-700">{{ $penggajian->catatan_dosen }}</p>
                </div>
            @endif

            <!-- Form Verifikasi -->
            @if($penggajian->canBeVerified())
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-check-circle mr-2 text-[#2D5F3F]"></i>
                        Form Verifikasi
                    </h2>
                    <form action="{{ route('admin.penggajian.verify', $penggajian->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Keputusan <span class="text-red-500">*</span>
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center px-4 py-3 bg-green-50 border-2 border-green-200 rounded-lg cursor-pointer hover:bg-green-100 transition">
                                    <input type="radio" name="action" value="approve" class="mr-3" required>
                                    <span class="font-semibold text-green-700">
                                        <i class="fas fa-check-circle mr-2"></i>Setujui
                                    </span>
                                </label>
                                <label class="flex items-center px-4 py-3 bg-red-50 border-2 border-red-200 rounded-lg cursor-pointer hover:bg-red-100 transition">
                                    <input type="radio" name="action" value="reject" class="mr-3" required>
                                    <span class="font-semibold text-red-700">
                                        <i class="fas fa-times-circle mr-2"></i>Tolak
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div id="approve-fields" style="display: none;">
                            <label for="total_jam_disetujui" class="block text-sm font-semibold text-gray-700 mb-2">
                                Total Jam yang Disetujui <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.5" min="0" name="total_jam_disetujui" id="total_jam_disetujui" 
                                value="{{ old('total_jam_disetujui', $penggajian->total_jam_diajukan) }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                                placeholder="Contoh: 24">
                            <p class="text-xs text-gray-500 mt-1">Dapat berbeda dengan jam yang diajukan jika ada penyesuaian</p>
                        </div>

                        <div>
                            <label for="catatan_verifikasi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Catatan Verifikasi (Opsional)
                            </label>
                            <textarea name="catatan_verifikasi" id="catatan_verifikasi" rows="4" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                                placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_verifikasi') }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.penggajian.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition">
                                Batalkan
                            </a>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                                <i class="fas fa-check mr-2"></i>
                                Simpan Verifikasi
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                    document.querySelectorAll('input[name="action"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            const approveFields = document.getElementById('approve-fields');
                            const totalJamInput = document.getElementById('total_jam_disetujui');
                            
                            if (this.value === 'approve') {
                                approveFields.style.display = 'block';
                                totalJamInput.required = true;
                            } else {
                                approveFields.style.display = 'none';
                                totalJamInput.required = false;
                            }
                        });
                    });
                </script>
            @elseif($penggajian->catatan_verifikasi)
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-clipboard-check mr-2 text-[#2D5F3F]"></i>
                        Hasil Verifikasi
                    </h2>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-600 mb-2">Catatan dari Admin:</p>
                        <p class="text-gray-800">{{ $penggajian->catatan_verifikasi }}</p>
                        @if($penggajian->verifier)
                            <p class="text-xs text-gray-500 mt-2">
                                oleh {{ $penggajian->verifier->username }} pada {{ $penggajian->verified_at->format('d M Y, H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Pembayaran Info -->
            @if($penggajian->status === 'paid')
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-money-check-alt mr-2 text-[#2D5F3F]"></i>
                        Informasi Pembayaran
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Jumlah Dibayar:</p>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($penggajian->jumlah_dibayar, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Tanggal Pembayaran:</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $penggajian->paid_at->format('d M Y, H:i') }}</p>
                            @if($penggajian->payer)
                                <p class="text-xs text-gray-500 mt-1">oleh {{ $penggajian->payer->username }}</p>
                            @endif
                        </div>
                    </div>
                    @if($penggajian->bukti_pembayaran)
                        <a href="{{ $penggajian->bukti_pembayaran }}" target="_blank" class="mt-4 flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                            <span class="text-sm font-semibold text-blue-800">
                                <i class="fas fa-receipt mr-2"></i>Bukti Pembayaran
                            </span>
                            <i class="fas fa-external-link-alt text-blue-600"></i>
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <!-- Diajukan -->
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-800">Diajukan</p>
                            <p class="text-xs text-gray-600">{{ $penggajian->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Verifikasi -->
                    @if($penggajian->verified_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 {{ $penggajian->status === 'rejected' ? 'bg-red-500' : 'bg-green-500' }} rounded-full flex items-center justify-center">
                                    <i class="fas {{ $penggajian->status === 'rejected' ? 'fa-times' : 'fa-check' }} text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $penggajian->status === 'rejected' ? 'Ditolak' : 'Diverifikasi' }}
                                </p>
                                <p class="text-xs text-gray-600">{{ $penggajian->verified_at->format('d M Y, H:i') }}</p>
                                @if($penggajian->verifier)
                                    <p class="text-xs text-gray-500">oleh {{ $penggajian->verifier->username }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-500">Menunggu Verifikasi</p>
                            </div>
                        </div>
                    @endif

                    <!-- Pembayaran -->
                    @if($penggajian->paid_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-800">Sudah Dibayar</p>
                                <p class="text-xs text-gray-600">{{ $penggajian->paid_at->format('d M Y, H:i') }}</p>
                                @if($penggajian->payer)
                                    <p class="text-xs text-gray-500">oleh {{ $penggajian->payer->username }}</p>
                                @endif
                            </div>
                        </div>
                    @elseif($penggajian->status === 'verified')
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold text-gray-500">Menunggu Pembayaran</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
