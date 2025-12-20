@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Pengajuan Penggajian</h1>
            <p class="text-gray-600 mt-1">Ubah data pengajuan pencairan gaji</p>
        </div>
        <a href="{{ route('dosen.penggajian.show', $penggajian->id) }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
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

    <!-- Form -->
    <form action="{{ route('dosen.penggajian.update', $penggajian->id) }}" method="POST" class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Periode & Semester -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="periode" class="block text-sm font-semibold text-gray-700 mb-2">
                    Periode <span class="text-red-500">*</span>
                </label>
                <input type="month" id="periode" name="periode" value="{{ old('periode', $penggajian->periode) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                    required>
                <p class="text-xs text-gray-500 mt-1">Format: YYYY-MM (contoh: 2025-11 untuk November 2025)</p>
            </div>

            <div>
                <label for="semester_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Semester (Opsional)
                </label>
                <select id="semester_id" name="semester_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent">
                    <option value="">-- Pilih Semester --</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ old('semester_id', $penggajian->semester_id) == $semester->id ? 'selected' : '' }}>
                            {{ $semester->nama_semester }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Total Jam Pelajaran -->
        <div>
            <label for="total_jam_diajukan" class="block text-sm font-semibold text-gray-700 mb-2">
                Total Jam Pelajaran (JP) <span class="text-red-500">*</span>
            </label>
            <input type="number" step="1" min="1" id="total_jam_diajukan" name="total_jam_diajukan" 
                value="{{ old('total_jam_diajukan', $penggajian->total_jam_diajukan) }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent text-lg font-semibold" 
                placeholder="24" required>
            
            <!-- Help Text -->
            <div class="mt-2 bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-sm font-semibold text-blue-800 mb-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Keterangan:
                </p>
                <p class="text-sm text-blue-700">
                    Masukkan jumlah Jam Pelajaran (JP) dalam bilangan bulat.<br>
                    Contoh: 12 JP, 24 JP, 36 JP, dst.
                </p>
            </div>
        </div>

        <!-- Dokumen Section -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-link mr-2 text-[#2D5F3F]"></i>
                Link Dokumen (Google Drive)
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                Pastikan semua link sudah di-share dengan pengaturan <strong>"anyone with the link can view"</strong>
            </p>

            <div class="space-y-4">
                <div>
                    <label for="link_rps" class="block text-sm font-semibold text-gray-700 mb-2">
                        Link RPS (Rencana Pembelajaran Semester) <span class="text-red-500">*</span>
                    </label>
                    <input type="url" id="link_rps" name="link_rps" value="{{ old('link_rps', $penggajian->link_rps) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                        placeholder="https://drive.google.com/..." required>
                </div>

                <div>
                    <label for="link_materi_ajar" class="block text-sm font-semibold text-gray-700 mb-2">
                        Link Materi Ajar <span class="text-red-500">*</span>
                    </label>
                    <input type="url" id="link_materi_ajar" name="link_materi_ajar" value="{{ old('link_materi_ajar', $penggajian->link_materi_ajar) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                        placeholder="https://drive.google.com/..." required>
                </div>

                <div>
                    <label for="link_absensi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Link Absensi Mahasiswa <span class="text-red-500">*</span>
                    </label>
                    <input type="url" id="link_absensi" name="link_absensi" value="{{ old('link_absensi', $penggajian->link_absensi) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                        placeholder="https://drive.google.com/..." required>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div>
            <label for="catatan_dosen" class="block text-sm font-semibold text-gray-700 mb-2">
                Catatan (Opsional)
            </label>
            <textarea id="catatan_dosen" name="catatan_dosen" rows="4" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent" 
                placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan_dosen', $penggajian->catatan_dosen) }}</textarea>
        </div>

        <!-- Data Rekening -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-university mr-2 text-[#2D5F3F]"></i>
                Data Rekening Anda
            </h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Bank:</p>
                        <p class="text-base font-semibold text-gray-800">{{ $dosen->nama_bank ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor Rekening:</p>
                        <p class="text-base font-semibold text-gray-800">{{ $dosen->nomor_rekening ?? '-' }}</p>
                    </div>
                </div>
                <a href="{{ route('dosen.profile') }}" class="text-sm text-blue-600 hover:text-blue-800 mt-3 inline-block">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Data Rekening
                </a>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('dosen.penggajian.show', $penggajian->id) }}" class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition">
                Batalkan
            </a>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                <i class="fas fa-save mr-2"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
