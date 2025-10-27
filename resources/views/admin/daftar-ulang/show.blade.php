@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ verifyModal: false, rejectModal: false }">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Daftar Ulang</h1>
            <p class="text-gray-600 mt-1">Detail lengkap daftar ulang mahasiswa</p>
        </div>
        <a href="{{ route('admin.daftar-ulang.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Status & Payment Info -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Status Daftar Ulang</h2>
                </div>
                <div class="p-6 text-center">
                    @if($daftarUlang->status == 'pending')
                        <div class="mb-4">
                            <i class="fas fa-clock text-6xl text-yellow-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-600 mb-2">Menunggu Verifikasi</h3>
                        <p class="text-gray-600 mb-6">Daftar ulang ini belum diverifikasi</p>

                        <!-- Action Buttons -->
                        <button @click="verifyModal = true" class="w-full mb-3 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-check-circle mr-2"></i>Verifikasi & Buat Akun
                        </button>
                        <button @click="rejectModal = true" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            <i class="fas fa-times-circle mr-2"></i>Tolak
                        </button>
                    @elseif($daftarUlang->status == 'verified')
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-6xl text-green-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-green-600 mb-2">Terverifikasi</h3>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 space-y-1">
                            <p><span class="font-semibold">Diverifikasi pada:</span></p>
                            <p class="text-gray-900 font-semibold">{{ $daftarUlang->tanggal_verifikasi->format('d/m/Y H:i') }}</p>
                            <p class="mt-2"><span class="font-semibold">Oleh:</span> {{ $daftarUlang->verifier->name ?? '-' }}</p>
                        </div>
                        @if($daftarUlang->mahasiswaUser)
                            <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                                <i class="fas fa-user-check text-green-600 text-2xl mb-2"></i>
                                <p class="font-semibold text-green-800">Akun Mahasiswa Sudah Dibuat</p>
                                <p class="text-sm text-green-700 mt-1">Username: <code class="bg-green-100 px-2 py-1 rounded">{{ $daftarUlang->mahasiswaUser->username }}</code></p>
                            </div>
                        @endif
                    @else
                        <div class="mb-4">
                            <i class="fas fa-times-circle text-6xl text-red-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-red-600 mb-2">Ditolak</h3>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 space-y-1">
                            <p><span class="font-semibold">Ditolak pada:</span></p>
                            <p class="text-gray-900 font-semibold">{{ $daftarUlang->tanggal_verifikasi->format('d/m/Y H:i') }}</p>
                            <p class="mt-2"><span class="font-semibold">Oleh:</span> {{ $daftarUlang->verifier->name ?? '-' }}</p>
                        </div>
                    @endif

                    @if($daftarUlang->keterangan)
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                            <p class="font-semibold text-blue-800 mb-2">Keterangan:</p>
                            <p class="text-sm text-blue-700">{{ $daftarUlang->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Informasi Pembayaran</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Biaya Daftar Ulang:</span>
                            <span class="text-lg font-bold text-green-600">Rp {{ number_format($daftarUlang->biaya_daftar_ulang, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Metode:</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ strtoupper($daftarUlang->metode_pembayaran) }}</span>
                        </div>
                        @if($daftarUlang->nomor_referensi)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">No. Referensi:</span>
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $daftarUlang->nomor_referensi }}</code>
                            </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tanggal Submit:</span>
                            <span class="text-gray-900">{{ $daftarUlang->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    @if($daftarUlang->bukti_pembayaran)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Bukti Pembayaran:</p>
                            <a href="{{ $daftarUlang->bukti_pembayaran }}" target="_blank" class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i>Lihat Bukti Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Pendaftar Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Data Pendaftar</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Data Pribadi -->
                        <div>
                            <h3 class="text-base font-semibold text-green-700 border-b-2 border-green-200 pb-2 mb-4">Data Pribadi</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-gray-600 block">NIM Sementara:</span>
                                    <span class="font-bold text-gray-900">{{ $daftarUlang->nim_sementara }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Nomor Pendaftaran:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->nomor_pendaftaran }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Nama Lengkap:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->nama }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Tempat/Tgl Lahir:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->tempat_lahir }}, {{ \Carbon\Carbon::parse($daftarUlang->pendaftar->tanggal_lahir)->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Jenis Kelamin:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->jenis_kelamin }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Agama:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->agama }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Alamat:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->alamat }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & Akademik -->
                        <div>
                            <h3 class="text-base font-semibold text-green-700 border-b-2 border-green-200 pb-2 mb-4">Kontak & Akademik</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-gray-600 block">Email:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->email }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">No. Telepon:</span>
                                    <span class="text-gray-900">{{ $daftarUlang->pendaftar->no_telepon }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Jurusan:</span>
                                    <span class="font-bold text-gray-900">{{ $daftarUlang->pendaftar->jurusan->nama_prodi ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Jalur:</span>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ $daftarUlang->pendaftar->jalurSeleksi->nama ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block">Angkatan:</span>
                                    <span class="text-gray-900">{{ date('Y') }}</span>
                                </div>
                            </div>

                            @if($daftarUlang->pendaftar->google_drive_file_id)
                                <div class="mt-6">
                                    <h3 class="text-base font-semibold text-green-700 border-b-2 border-green-200 pb-2 mb-4">Foto</h3>
                                    <img src="https://drive.google.com/thumbnail?id={{ basename(parse_url($daftarUlang->pendaftar->google_drive_file_id, PHP_URL_PATH)) }}&sz=w200"
                                         alt="Foto"
                                         class="border-2 border-gray-300 rounded-lg shadow-sm"
                                         style="max-width: 200px;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dokumen Pendukung -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-base font-semibold text-green-700 border-b-2 border-green-200 pb-2 mb-4">Dokumen Pendukung</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                $documents = [
                                    ['name' => 'Ijazah', 'id_field' => 'ijazah_google_drive_id', 'link_field' => 'ijazah_google_drive_link'],
                                    ['name' => 'Transkrip Nilai', 'id_field' => 'transkrip_google_drive_id', 'link_field' => 'transkrip_google_drive_link'],
                                    ['name' => 'KTP', 'id_field' => 'ktp_google_drive_id', 'link_field' => 'ktp_google_drive_link'],
                                    ['name' => 'Kartu Keluarga', 'id_field' => 'kk_google_drive_id', 'link_field' => 'kk_google_drive_link'],
                                    ['name' => 'Akta Kelahiran', 'id_field' => 'akta_google_drive_id', 'link_field' => 'akta_google_drive_link'],
                                    ['name' => 'SKTM', 'id_field' => 'sktm_google_drive_id', 'link_field' => 'sktm_google_drive_link'],
                                ];
                            @endphp

                            @foreach($documents as $doc)
                                @if($daftarUlang->pendaftar->{$doc['id_field']} && $daftarUlang->pendaftar->{$doc['link_field']})
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow">
                                        <i class="fas fa-file-pdf text-4xl text-red-500 mb-2"></i>
                                        <p class="text-sm font-semibold text-gray-800 mb-2">{{ $doc['name'] }}</p>
                                        <a href="{{ $daftarUlang->pendaftar->{$doc['link_field']} }}"
                                           target="_blank"
                                           class="inline-block px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-external-link-alt mr-1"></i>Lihat
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verify Modal -->
    <div x-show="verifyModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="verifyModal = false"></div>

            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <form action="{{ route('admin.daftar-ulang.verify', $daftarUlang->id) }}" method="POST">
                    @csrf
                    <div class="bg-green-600 text-white px-6 py-4 rounded-t-lg flex items-center justify-between">
                        <h3 class="text-lg font-semibold">
                            <i class="fas fa-check-circle mr-2"></i>Verifikasi Daftar Ulang
                        </h3>
                        <button type="button" @click="verifyModal = false" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-800 font-semibold mb-2"><i class="fas fa-info-circle mr-2"></i>Perhatian:</p>
                            <p class="text-sm text-blue-700 mb-2">Dengan memverifikasi daftar ulang ini, sistem akan otomatis membuat:</p>
                            <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                                <li>Akun user dengan role <strong>mahasiswa</strong></li>
                                <li>Data mahasiswa di sistem</li>
                                <li>Username: <strong>{{ $daftarUlang->nim_sementara }}</strong></li>
                                <li>Password akan di-generate otomatis</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Catatan verifikasi (opsional)"></textarea>
                        </div>

                        <p class="text-xs text-gray-500">Pastikan semua data dan bukti pembayaran sudah benar sebelum verifikasi.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end space-x-3">
                        <button type="button" @click="verifyModal = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-check-circle mr-2"></i>Ya, Verifikasi & Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" @click="rejectModal = false"></div>

            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <form action="{{ route('admin.daftar-ulang.reject', $daftarUlang->id) }}" method="POST">
                    @csrf
                    <div class="bg-red-600 text-white px-6 py-4 rounded-t-lg flex items-center justify-between">
                        <h3 class="text-lg font-semibold">
                            <i class="fas fa-times-circle mr-2"></i>Tolak Daftar Ulang
                        </h3>
                        <button type="button" @click="rejectModal = false" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-yellow-800 font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Perhatian:</p>
                            <p class="text-sm text-yellow-700">Daftar ulang yang ditolak tidak dapat diubah kembali. Pastikan alasan penolakan sudah jelas.</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-600">*</span></label>
                            <textarea name="keterangan" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan alasan penolakan..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Alasan ini akan ditampilkan kepada pendaftar.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end space-x-3">
                        <button type="button" @click="rejectModal = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            <i class="fas fa-times-circle mr-2"></i>Ya, Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
