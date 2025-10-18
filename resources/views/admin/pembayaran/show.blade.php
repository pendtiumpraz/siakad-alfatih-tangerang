@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pembayaran</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap pembayaran mahasiswa</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.pembayaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Kwitansi</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Info -->
        <div class="lg:col-span-2 space-y-6">
            <x-islamic-card title="Informasi Pembayaran">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">ID Pembayaran</p>
                        <p class="text-lg font-semibold text-gray-800">#PAY-{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <x-status-badge :status="$pembayaran->status" type="payment" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jenis Pembayaran</p>
                        <p class="text-lg font-semibold text-gray-800">{{ ucwords(str_replace('_', ' ', $pembayaran->jenis_pembayaran)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nominal</p>
                        <p class="text-2xl font-bold text-green-700">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Jatuh Tempo</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $pembayaran->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Bayar</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                    <p class="text-gray-800">{{ $pembayaran->keterangan ?? '-' }}</p>
                </div>
            </x-islamic-card>

            <!-- Bukti Pembayaran -->
            <x-islamic-card title="Bukti Pembayaran">
                @if($pembayaran->bukti_pembayaran)
                    @php
                        $fileExtension = strtolower(pathinfo($pembayaran->bukti_pembayaran, PATHINFO_EXTENSION));
                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $fileUrl = \Illuminate\Support\Facades\Storage::url($pembayaran->bukti_pembayaran);
                    @endphp

                    @if($isImage)
                        <!-- Image Preview -->
                        <div class="space-y-3">
                            <div class="relative group">
                                <img src="{{ $fileUrl }}" alt="Bukti Pembayaran" class="w-full rounded-lg shadow-md">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <a href="{{ $fileUrl }}" target="_blank" class="opacity-0 group-hover:opacity-100 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-semibold flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>Lihat Full Size</span>
                                    </a>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span>{{ basename($pembayaran->bukti_pembayaran) }}</span>
                                <a href="{{ $fileUrl }}" download class="text-green-600 hover:text-green-800 font-semibold flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <span>Download</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- PDF Preview -->
                        <div class="bg-gray-50 rounded-lg p-6 border-2 border-green-200">
                            <div class="text-center">
                                <svg class="w-24 h-24 mx-auto text-red-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-800 font-semibold mb-1">{{ basename($pembayaran->bukti_pembayaran) }}</p>
                                <p class="text-xs text-gray-500 mb-4">Dokumen PDF</p>
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ $fileUrl }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>Lihat PDF</span>
                                    </a>
                                    <a href="{{ $fileUrl }}" download class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- No File -->
                    <div class="bg-gray-50 rounded-lg p-8 border-2 border-dashed border-gray-300 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm text-gray-500">Belum ada bukti pembayaran</p>
                    </div>
                @endif
            </x-islamic-card>
        </div>

        <!-- Student Info & Actions -->
        <div class="space-y-6">
            <!-- Student Info -->
            <x-islamic-card title="Data Mahasiswa">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-3xl font-bold mb-4">
                        {{ strtoupper(substr($pembayaran->mahasiswa->nama_lengkap ?? 'M', 0, 2)) }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $pembayaran->mahasiswa->nama_lengkap ?? '-' }}</h3>
                    <p class="text-gray-600">NIM: {{ $pembayaran->mahasiswa->nim ?? '-' }}</p>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="text-sm text-gray-600">Program Studi</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $pembayaran->mahasiswa->programStudi->nama_program_studi ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="text-sm text-gray-600">Semester</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $pembayaran->semester->nama_semester ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <span class="text-sm text-gray-600">Status</span>
                        <x-status-badge :status="$pembayaran->mahasiswa->status ?? 'active'" type="status" />
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Email</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $pembayaran->mahasiswa->email ?? '-' }}</span>
                    </div>
                </div>
            </x-islamic-card>

            <!-- Actions -->
            <x-islamic-card title="Aksi">
                <div class="space-y-3">
                    <form action="{{ route('admin.pembayaran.verify', $pembayaran->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Verifikasi Pembayaran</span>
                        </button>
                    </form>

                    <a href="{{ route('admin.pembayaran.edit', $pembayaran->id) }}" class="block w-full px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold text-center">
                        Edit Pembayaran
                    </a>

                    <button onclick="window.print()" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>Cetak Kwitansi</span>
                    </button>
                </div>
            </x-islamic-card>

            <!-- Payment History -->
            <x-islamic-card title="Riwayat Pembayaran">
                <div class="space-y-3">
                    @foreach(range(1, 3) as $index)
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-sm font-semibold text-gray-800">SPP Semester {{ $index }}</p>
                            <x-status-badge status="lunas" type="payment" />
                        </div>
                        <p class="text-sm text-gray-600">Rp 2.500.000</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::now()->subMonths($index)->format('d/m/Y') }}</p>
                    </div>
                    @endforeach
                </div>
            </x-islamic-card>
        </div>
    </div>
</div>
@endsection
