@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 mb-6 border-2 border-[#D4AF37]">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-[#D4AF37] rounded-full flex items-center justify-center">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Transaksi Manual</h1>
                    <p class="text-emerald-50 text-sm">Update data transaksi yang sudah ada</p>
                </div>
            </div>
            <a href="{{ route('admin.keuangan.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    @if($pembukuan->is_otomatis)
        <!-- Warning for auto transactions -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Peringatan:</strong> Ini adalah transaksi otomatis dari sistem. Tidak dapat diedit atau dihapus.
                        <br>Silakan kembali ke dashboard.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.keuangan.update', $pembukuan->id) }}" method="POST" enctype="multipart/form-data" id="keuanganForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Jenis Transaksi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Jenis Transaksi <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-green-50 transition {{ $pembukuan->jenis === 'pemasukan' ? 'border-green-500 bg-green-50' : '' }}" id="label-pemasukan">
                                <input type="radio" name="jenis" value="pemasukan" class="mr-3" required onchange="updateSubKategori()" {{ $pembukuan->jenis === 'pemasukan' ? 'checked' : '' }}>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-arrow-down text-green-600"></i>
                                        <span class="font-bold text-gray-800">PEMASUKAN</span>
                                    </div>
                                    <span class="text-xs text-gray-500">Uang masuk</span>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-red-50 transition {{ $pembukuan->jenis === 'pengeluaran' ? 'border-red-500 bg-red-50' : '' }}" id="label-pengeluaran">
                                <input type="radio" name="jenis" value="pengeluaran" class="mr-3" required onchange="updateSubKategori()" {{ $pembukuan->jenis === 'pengeluaran' ? 'checked' : '' }}>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-arrow-up text-red-600"></i>
                                        <span class="font-bold text-gray-800">PENGELUARAN</span>
                                    </div>
                                    <span class="text-xs text-gray-500">Uang keluar</span>
                                </div>
                            </label>
                        </div>
                        @error('jenis')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub Kategori -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Sub Kategori</label>
                        <select name="sub_kategori" id="sub_kategori" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                            <option value="">-- Pilih Sub Kategori (Opsional) --</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Opsional - untuk kategorisasi lebih detail</p>
                    </div>

                    <!-- Semester -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Semester</label>
                        <select name="semester_id" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                            <option value="">-- Tidak Terkait Semester --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ $pembukuan->semester_id == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->nama_semester }}@if($semester->is_active) ⭐ (Aktif)@endif
                                </option>
                            @endforeach
                        </select>
                        @error('semester_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $pembukuan->tanggal->format('Y-m-d')) }}" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]" required>
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nominal -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nominal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                            <input type="text" name="nominal" id="nominal" value="{{ old('nominal', number_format($pembukuan->nominal, 0, ',', '.')) }}" class="w-full pl-12 pr-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]" placeholder="0" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Contoh: 1000000 (akan diformat otomatis)</p>
                        @error('nominal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan <span class="text-red-500">*</span></label>
                        <textarea name="keterangan" rows="4" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]" placeholder="Jelaskan detail transaksi ini..." required>{{ old('keterangan', $pembukuan->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bukti File -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Bukti Transaksi (Opsional)</label>
                        
                        @if($pembukuan->bukti_file)
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-cloud text-blue-500"></i>
                                        <span class="text-sm text-gray-700">File saat ini (Google Drive)</span>
                                    </div>
                                    <a href="{{ $pembukuan->bukti_file }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-external-link-alt mr-1"></i>Lihat di Google Drive
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        <input type="file" name="bukti_file" accept="image/*,application/pdf" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37]">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max: 2MB). Kosongkan jika tidak ingin mengubah.</p>
                        @error('bukti_file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Terakhir diubah: {{ $pembukuan->updated_at->diffForHumans() }}
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.keuangan.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] hover:shadow-lg text-white font-semibold rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>
                            Update Transaksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

</div>

<script>
    // Current sub_kategori from database
    const currentSubKategori = "{{ $pembukuan->sub_kategori }}";
    
    // Format nominal dengan thousand separator
    const nominalInput = document.getElementById('nominal');
    nominalInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            e.target.value = parseInt(value).toLocaleString('id-ID');
        }
    });

    // Remove formatting before submit
    document.getElementById('keuanganForm').addEventListener('submit', function(e) {
        let nominal = nominalInput.value.replace(/[^\d]/g, '');
        nominalInput.value = nominal;
    });

    // Update sub-kategori based on jenis
    function updateSubKategori() {
        const jenis = document.querySelector('input[name="jenis"]:checked')?.value;
        const subKategoriSelect = document.getElementById('sub_kategori');
        
        // Update border colors
        const labelPemasukan = document.getElementById('label-pemasukan');
        const labelPengeluaran = document.getElementById('label-pengeluaran');
        
        if (jenis === 'pemasukan') {
            labelPemasukan.classList.add('border-green-500', 'bg-green-50');
            labelPemasukan.classList.remove('border-gray-300');
            labelPengeluaran.classList.remove('border-red-500', 'bg-red-50');
            labelPengeluaran.classList.add('border-gray-300');
        } else if (jenis === 'pengeluaran') {
            labelPengeluaran.classList.add('border-red-500', 'bg-red-50');
            labelPengeluaran.classList.remove('border-gray-300');
            labelPemasukan.classList.remove('border-green-500', 'bg-green-50');
            labelPemasukan.classList.add('border-gray-300');
        }
        
        subKategoriSelect.innerHTML = '<option value="">-- Pilih Sub Kategori (Opsional) --</option>';
        
        if (jenis === 'pemasukan') {
            const options = [
                'Donasi',
                'Investor',
                'Hibah',
                'Sponsorship',
                'Sewa/Rental',
                'Lainnya'
            ];
            options.forEach(opt => {
                const selected = (opt === currentSubKategori) ? 'selected' : '';
                subKategoriSelect.innerHTML += `<option value="${opt}" ${selected}>${opt}</option>`;
            });
        } else if (jenis === 'pengeluaran') {
            const options = [
                'Pembangunan',
                'Operasional',
                'Pemeliharaan',
                'Utilitas',
                'ATK',
                'Transport',
                'Kegiatan',
                'Lainnya'
            ];
            options.forEach(opt => {
                const selected = (opt === currentSubKategori) ? 'selected' : '';
                subKategoriSelect.innerHTML += `<option value="${opt}" ${selected}>${opt}</option>`;
            });
        }
    }
    
    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        updateSubKategori();
    });
</script>
@endsection
