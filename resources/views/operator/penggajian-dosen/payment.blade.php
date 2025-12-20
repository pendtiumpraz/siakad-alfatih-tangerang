@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Proses Pembayaran Gaji</h1>
            <p class="text-gray-600 mt-1">{{ $penggajian->dosen->nama_lengkap }} - {{ $penggajian->periode_formatted }}</p>
        </div>
        <a href="{{ route('operator.penggajian.show', $penggajian->id) }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

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
        <!-- Form Pembayaran -->
        <div class="lg:col-span-2">
            <form action="{{ route('operator.penggajian.storePayment', $penggajian->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6 space-y-6">
                @csrf

                <!-- Info Dosen -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-tie mr-2 text-[#2D5F3F]"></i>
                        Informasi Penerima
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Nama Lengkap:</span>
                            <span class="font-semibold text-gray-800">{{ $penggajian->dosen->nama_lengkap }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">NIP:</span>
                            <span class="font-semibold text-gray-800">{{ $penggajian->dosen->nidn }}</span>
                        </div>
                    </div>
                </div>

                <!-- Data Rekening -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-university mr-2 text-[#2D5F3F]"></i>
                        Data Transfer
                    </h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Nama Bank:</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nama_bank ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">No. Rekening:</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nomor_rekening ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Atas Nama:</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $penggajian->dosen->nama_lengkap }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Pengajuan -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-invoice mr-2 text-[#2D5F3F]"></i>
                        Detail Pengajuan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Periode:</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $penggajian->periode_formatted }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Semester:</p>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $penggajian->semester ? $penggajian->semester->nama_semester : '-' }}
                            </p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">JP Disetujui:</p>
                            <p class="text-2xl font-bold text-green-600">{{ (int) $penggajian->total_jam_disetujui }}</p>
                            <p class="text-sm text-gray-500">JP</p>
                        </div>
                    </div>
                </div>

                <!-- Form Input Pembayaran -->
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-money-bill-wave mr-2 text-[#2D5F3F]"></i>
                        Form Pembayaran
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label for="jumlah_dibayar" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah yang Dibayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 font-semibold">Rp</span>
                                <input type="number" step="1000" min="0" id="jumlah_dibayar" name="jumlah_dibayar" 
                                    value="{{ old('jumlah_dibayar') }}" 
                                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent text-lg font-semibold" 
                                    placeholder="0" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan jumlah nominal yang akan ditransfer</p>
                        </div>

                        <div>
                            <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                                Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" 
                                accept="image/jpeg,image/jpg,image/png,application/pdf"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                                required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                Upload bukti transfer (JPG, PNG, PDF max 5MB). File akan otomatis disimpan ke Google Drive.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Warning -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Perhatian:</strong> Pastikan nominal dan data rekening sudah benar sebelum menyimpan. 
                                Setelah pembayaran dicatat, status akan berubah menjadi "Sudah Dibayar" dan tidak dapat diubah.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('operator.penggajian.show', $penggajian->id) }}" class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition">
                        Batalkan
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-check-circle mr-2"></i>
                        Tandai Sudah Dibayar
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Status Current -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Status Saat Ini</h3>
                {!! $penggajian->status_badge !!}
                <p class="text-sm text-gray-600 mt-3">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    Pengajuan sudah diverifikasi dan siap untuk pembayaran
                </p>
            </div>

            <!-- Timeline Preview -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <p class="ml-3 text-sm text-gray-600">Diajukan</p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <p class="ml-3 text-sm text-gray-600">Diverifikasi</p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                            <i class="fas fa-clock text-white text-xs"></i>
                        </div>
                        <p class="ml-3 text-sm font-semibold text-yellow-700">Proses Pembayaran</p>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Tips Pembayaran
                </h4>
                <ul class="text-xs text-blue-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                        Verifikasi data rekening sebelum transfer
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                        Lakukan transfer ke rekening dosen
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                        Screenshot bukti transfer dari bank
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                        Upload file (akan otomatis ke Google Drive)
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

