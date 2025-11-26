@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pengajuan Penggajian</h1>
            <p class="text-gray-600 mt-1">Periode: {{ $penggajian->periode_formatted }}</p>
        </div>
        <div class="flex space-x-2">
            @if($penggajian->canBeEdited())
                <a href="{{ route('dosen.penggajian.edit', $penggajian->id) }}" class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            @endif
            <a href="{{ route('dosen.penggajian.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
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

    <!-- Info Alert if not editable -->
    @if(!$penggajian->canBeEdited())
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-800">
                        <strong>Informasi:</strong> 
                        @if($penggajian->status === 'verified')
                            Pengajuan ini sudah <strong>diverifikasi</strong> oleh admin. Anda tidak dapat mengedit atau menghapus pengajuan ini.
                        @elseif($penggajian->status === 'paid')
                            Pengajuan ini sudah <strong>dibayar</strong>. Anda tidak dapat mengedit atau menghapus pengajuan ini.
                        @elseif($penggajian->status === 'rejected')
                            Pengajuan ini <strong>ditolak</strong>. Silakan buat pengajuan baru dengan data yang benar.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Status Pengajuan</h2>
                    {!! $penggajian->status_badge !!}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                        <p class="text-sm text-gray-600 mb-1">Tanggal Pengajuan</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $penggajian->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Jam Mengajar -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-[#2D5F3F]"></i>
                    Total Jam Mengajar
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Jam Diajukan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $penggajian->total_jam_diajukan }}</p>
                        <p class="text-sm text-gray-500">jam</p>
                    </div>
                    @if($penggajian->total_jam_disetujui)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Jam Disetujui</p>
                            <p class="text-3xl font-bold text-green-600">{{ $penggajian->total_jam_disetujui }}</p>
                            <p class="text-sm text-gray-500">jam</p>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-center">
                            <p class="text-gray-400 text-center">Menunggu verifikasi</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-[#2D5F3F]"></i>
                    Dokumen
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

            <!-- Catatan -->
            @if($penggajian->catatan_dosen || $penggajian->catatan_verifikasi)
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-[#2D5F3F]"></i>
                        Catatan
                    </h2>
                    @if($penggajian->catatan_dosen)
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-600 mb-1">Catatan Dosen:</p>
                            <p class="text-gray-800">{{ $penggajian->catatan_dosen }}</p>
                        </div>
                    @endif
                    @if($penggajian->catatan_verifikasi)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm font-semibold text-yellow-800 mb-1">Catatan Verifikasi dari Admin:</p>
                            <p class="text-gray-800">{{ $penggajian->catatan_verifikasi }}</p>
                        </div>
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

            <!-- Pembayaran Info -->
            @if($penggajian->status === 'paid')
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Dibayar:</p>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($penggajian->jumlah_dibayar, 0, ',', '.') }}</p>
                        </div>
                        @if($penggajian->bukti_pembayaran)
                            <a href="{{ $penggajian->bukti_pembayaran_url }}" target="_blank" class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                                <span class="text-sm font-semibold text-blue-800">
                                    <i class="fas fa-receipt mr-2"></i>Lihat Bukti Pembayaran
                                </span>
                                <i class="fas fa-external-link-alt text-blue-600"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
