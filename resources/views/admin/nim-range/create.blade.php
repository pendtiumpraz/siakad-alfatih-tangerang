@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Tambah NIM Range" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.nim-ranges.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden" x-data="nimRangeForm()">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-id-card mr-2"></i>
                Form NIM Range
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.nim-ranges.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Program Studi -->
                    <div class="md:col-span-2">
                        <label for="program_studi_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="program_studi_id"
                            id="program_studi_id"
                            x-model="prodiId"
                            @change="updatePreview()"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('program_studi_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Pilih Program Studi</option>
                            @foreach($programStudis as $prodi)
                                <option
                                    value="{{ $prodi->id }}"
                                    data-kode="{{ $prodi->kode_prodi }}"
                                    data-nama="{{ $prodi->nama_prodi }}"
                                    data-jenjang="{{ $prodi->jenjang }}"
                                    {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}
                                >
                                    {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }}) - Kode: {{ $prodi->kode_prodi }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_id')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Pilih program studi untuk NIM Range ini</p>
                    </div>

                    <!-- Tahun Masuk -->
                    <div>
                        <label for="tahun_masuk" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Masuk <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="tahun_masuk"
                            id="tahun_masuk"
                            x-model="tahunMasuk"
                            @input="updatePreview()"
                            min="2000"
                            max="2100"
                            value="{{ old('tahun_masuk', $currentYear) }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tahun_masuk') border-red-500 @enderror"
                            required
                        >
                        @error('tahun_masuk')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Tahun angkatan mahasiswa</p>
                    </div>

                    <!-- Maksimal Nomor -->
                    <div>
                        <label for="max_number" class="block text-sm font-semibold text-gray-700 mb-2">
                            Maksimal Nomor <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="max_number"
                            id="max_number"
                            min="1"
                            max="9999"
                            value="{{ old('max_number', 999) }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('max_number') border-red-500 @enderror"
                            required
                        >
                        @error('max_number')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kapasitas maksimal mahasiswa (1-9999)</p>
                    </div>

                    <!-- Prefix (Auto-generated, Read-only) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prefix NIM (Auto-generated)
                        </label>
                        <div class="px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg">
                            <code class="text-lg font-mono font-semibold text-[#2D5F3F]" x-text="prefix || '-'"></code>
                            <p class="text-xs text-gray-500 mt-1">Format: YY (Tahun) + Kode Prodi (3 digit)</p>
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
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Card -->
    <div class="bg-gradient-to-br from-[#F4E5C3] to-white rounded-lg shadow-md border border-[#D4AF37] p-6" x-data="nimRangeForm()">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-eye mr-2"></i>
            Preview Format NIM
        </h3>

        <div class="space-y-4">
            <!-- NIM Format -->
            <div class="bg-white rounded-lg p-4 border-2 border-[#D4AF37]">
                <div class="text-sm text-gray-600 mb-2">Format NIM yang akan dihasilkan:</div>
                <div class="flex items-center justify-center space-x-2 py-4">
                    <div class="text-center">
                        <div class="px-4 py-3 bg-blue-100 text-blue-800 rounded-lg font-mono text-xl font-bold" x-text="prefix || 'XX'"></div>
                        <div class="text-xs text-gray-500 mt-1">Prefix</div>
                    </div>
                    <div class="text-2xl text-gray-400">+</div>
                    <div class="text-center">
                        <div class="px-4 py-3 bg-green-100 text-green-800 rounded-lg font-mono text-xl font-bold">XXXX</div>
                        <div class="text-xs text-gray-500 mt-1">Nomor Urut</div>
                    </div>
                </div>
            </div>

            <!-- Preview Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-semibold text-gray-700">Program Studi:</span>
                    <span class="ml-2" x-text="prodiNama || '-'"></span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Jenjang:</span>
                    <span class="ml-2" x-text="prodiJenjang || '-'"></span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Tahun Masuk:</span>
                    <span class="ml-2" x-text="tahunMasuk || '-'"></span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Kode Prodi:</span>
                    <span class="ml-2" x-text="prodiKode || '-'"></span>
                </div>
            </div>

            <!-- Example NIMs -->
            <div class="bg-white rounded-lg p-4 border border-gray-300">
                <div class="text-sm font-semibold text-gray-700 mb-2">Contoh NIM yang akan dihasilkan:</div>
                <div class="space-y-1 font-mono text-sm">
                    <div class="flex justify-between items-center py-1 px-2 hover:bg-gray-50 rounded">
                        <span>Mahasiswa ke-1:</span>
                        <span class="font-bold text-[#2D5F3F]" x-text="prefix ? prefix + '0001' : '-'"></span>
                    </div>
                    <div class="flex justify-between items-center py-1 px-2 hover:bg-gray-50 rounded">
                        <span>Mahasiswa ke-10:</span>
                        <span class="font-bold text-[#2D5F3F]" x-text="prefix ? prefix + '0010' : '-'"></span>
                    </div>
                    <div class="flex justify-between items-center py-1 px-2 hover:bg-gray-50 rounded">
                        <span>Mahasiswa ke-100:</span>
                        <span class="font-bold text-[#2D5F3F]" x-text="prefix ? prefix + '0100' : '-'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function nimRangeForm() {
    return {
        prodiId: '{{ old("program_studi_id") }}',
        tahunMasuk: '{{ old("tahun_masuk", $currentYear) }}',
        prodiKode: '',
        prodiNama: '',
        prodiJenjang: '',
        prefix: '',

        init() {
            this.updatePreview();

            // Watch for changes
            this.$watch('prodiId', () => this.updatePreview());
            this.$watch('tahunMasuk', () => this.updatePreview());
        },

        updatePreview() {
            const select = document.getElementById('program_studi_id');
            if (select && this.prodiId) {
                const option = select.options[select.selectedIndex];
                this.prodiKode = option.getAttribute('data-kode') || '';
                this.prodiNama = option.getAttribute('data-nama') || '';
                this.prodiJenjang = option.getAttribute('data-jenjang') || '';

                // Generate prefix
                if (this.tahunMasuk && this.prodiKode) {
                    const yearPrefix = String(this.tahunMasuk).slice(-2); // Last 2 digits
                    const kodeProdi = String(this.prodiKode).padStart(3, '0'); // Pad to 3 digits
                    this.prefix = yearPrefix + kodeProdi;
                } else {
                    this.prefix = '';
                }
            } else {
                this.prodiKode = '';
                this.prodiNama = '';
                this.prodiJenjang = '';
                this.prefix = '';
            }
        }
    }
}
</script>
@endsection
