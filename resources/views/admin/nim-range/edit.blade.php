@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Edit NIM Range" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.nim-ranges.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-edit mr-2"></i>
                Edit NIM Range
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.nim-ranges.update', $nimRange->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Program Studi (Read-only) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Program Studi
                        </label>
                        <div class="px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg">
                            <div class="font-semibold text-[#2D5F3F]">{{ $nimRange->programStudi->nama_prodi }}</div>
                            <div class="text-sm text-gray-600 mt-1">
                                {{ $nimRange->programStudi->jenjang }} - Kode: {{ $nimRange->programStudi->kode_prodi }}
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Program studi tidak dapat diubah</p>
                    </div>

                    <!-- Tahun Masuk (Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Masuk
                        </label>
                        <div class="px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg">
                            <span class="font-semibold text-[#2D5F3F]">{{ $nimRange->tahun_masuk }}</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tahun masuk tidak dapat diubah</p>
                    </div>

                    <!-- Prefix (Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prefix NIM
                        </label>
                        <div class="px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg">
                            <code class="text-lg font-mono font-semibold text-[#2D5F3F]">{{ $nimRange->prefix }}</code>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Prefix tidak dapat diubah</p>
                    </div>

                    <!-- Current Number (Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor Saat Ini (Terpakai)
                        </label>
                        <div class="px-4 py-3 bg-blue-50 border-2 border-blue-300 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-blue-800">{{ $nimRange->current_number }}</span>
                                <i class="fas fa-users text-blue-400 text-2xl"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Nomor ini otomatis bertambah saat mahasiswa terdaftar</p>
                    </div>

                    <!-- Max Number (Editable) -->
                    <div>
                        <label for="max_number" class="block text-sm font-semibold text-gray-700 mb-2">
                            Maksimal Nomor <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="max_number"
                            id="max_number"
                            min="{{ $nimRange->current_number }}"
                            max="9999"
                            value="{{ old('max_number', $nimRange->max_number) }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('max_number') border-red-500 @enderror"
                            required
                        >
                        @error('max_number')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Minimal: {{ $nimRange->current_number }} (tidak boleh kurang dari nomor terpakai)
                        </p>
                    </div>

                    <!-- Remaining Quota (Info) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Sisa Kuota
                        </label>
                        <div class="px-4 py-3 bg-green-50 border-2 border-green-300 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-2xl font-bold text-green-800">
                                        {{ $nimRange->remaining_quota ?? 0 }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        Mahasiswa lagi dapat terdaftar
                                    </div>
                                </div>
                                @if($nimRange->remaining_quota < 10 && $nimRange->remaining_quota > 0)
                                    <div class="text-yellow-500">
                                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                                        <div class="text-xs mt-1">Kuota hampir habis</div>
                                    </div>
                                @elseif($nimRange->remaining_quota == 0)
                                    <div class="text-red-500">
                                        <i class="fas fa-ban text-3xl"></i>
                                        <div class="text-xs mt-1">Kuota habis</div>
                                    </div>
                                @else
                                    <i class="fas fa-check-circle text-green-400 text-3xl"></i>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Penggunaan
                        </label>
                        <div class="px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold">{{ $nimRange->current_number }}</span>
                                <span class="text-sm text-gray-500">/</span>
                                <span class="text-sm font-semibold">{{ $nimRange->max_number }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                @php
                                    $percentage = $nimRange->max_number > 0 ? ($nimRange->current_number / $nimRange->max_number) * 100 : 0;
                                    $barColor = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                @endphp
                                <div class="{{ $barColor }} h-3 rounded-full transition-all" style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1 text-center">{{ number_format($percentage, 1) }}% terpakai</div>
                        </div>
                    </div>
                </div>

                <!-- Islamic Divider -->
                <div class="islamic-divider my-6"></div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.nim-ranges.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg hover:from-[#4A7C59] hover:to-[#D4AF37] transition">
                        <i class="fas fa-save mr-2"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
            <div>
                <h4 class="text-blue-800 font-semibold mb-1">Informasi Penting</h4>
                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                    <li>Hanya <strong>Maksimal Nomor</strong> yang dapat diubah</li>
                    <li>Maksimal Nomor tidak boleh kurang dari nomor yang sudah terpakai ({{ $nimRange->current_number }})</li>
                    <li>Nomor saat ini akan otomatis bertambah ketika mahasiswa baru terdaftar</li>
                    <li>Perhatikan sisa kuota untuk menghindari kekurangan slot mahasiswa</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Example NIMs Card -->
    <div class="bg-gradient-to-br from-[#F4E5C3] to-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-list-ol mr-2"></i>
            Contoh NIM yang Sudah/Akan Dibuat
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-300">
                <div class="text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                    Sudah Terpakai ({{ $nimRange->current_number }})
                </div>
                @if($nimRange->current_number > 0)
                    <div class="space-y-1 font-mono text-sm">
                        <div class="flex justify-between items-center py-1 px-2 bg-green-50 rounded">
                            <span>Pertama:</span>
                            <span class="font-bold text-green-700">{{ $nimRange->prefix }}0001</span>
                        </div>
                        @if($nimRange->current_number >= 10)
                            <div class="flex justify-between items-center py-1 px-2 bg-green-50 rounded">
                                <span>Ke-10:</span>
                                <span class="font-bold text-green-700">{{ $nimRange->prefix }}0010</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center py-1 px-2 bg-green-50 rounded">
                            <span>Terakhir:</span>
                            <span class="font-bold text-green-700">{{ $nimRange->prefix }}{{ str_pad($nimRange->current_number, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada NIM yang terpakai</p>
                @endif
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-300">
                <div class="text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-clock text-blue-500 mr-1"></i>
                    Selanjutnya ({{ $nimRange->remaining_quota ?? 0 }} slot)
                </div>
                @if($nimRange->remaining_quota > 0)
                    <div class="space-y-1 font-mono text-sm">
                        <div class="flex justify-between items-center py-1 px-2 bg-blue-50 rounded">
                            <span>Berikutnya:</span>
                            <span class="font-bold text-blue-700">{{ $nimRange->prefix }}{{ str_pad($nimRange->current_number + 1, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        @if($nimRange->remaining_quota >= 10)
                            <div class="flex justify-between items-center py-1 px-2 bg-blue-50 rounded">
                                <span>+10:</span>
                                <span class="font-bold text-blue-700">{{ $nimRange->prefix }}{{ str_pad($nimRange->current_number + 10, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center py-1 px-2 bg-blue-50 rounded">
                            <span>Terakhir:</span>
                            <span class="font-bold text-blue-700">{{ $nimRange->prefix }}{{ str_pad($nimRange->max_number, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-red-500 italic">Kuota habis - tingkatkan Maksimal Nomor</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
