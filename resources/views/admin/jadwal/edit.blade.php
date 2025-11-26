@extends('layouts.admin')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px;
        padding: 6px 12px;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
</style>

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Jadwal</h1>
            <p class="text-gray-600 mt-1">Perbarui jadwal mengajar</p>
        </div>
        <a href="{{ route('admin.jadwal.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form -->
    <x-islamic-card title="Informasi Jadwal">
        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mata Kuliah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <select id="mata_kuliah_select" name="mata_kuliah_id" required class="w-full">
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliahs as $mk)
                            <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $jadwal->mata_kuliah_id) == $mk->id ? 'selected' : '' }}
                                data-prodi="{{ $mk->kurikulum->programStudi->nama_prodi ?? '-' }}"
                                data-sks="{{ $mk->sks }}"
                                data-semester="{{ $mk->semester }}">
                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }} (Sem {{ $mk->semester }}, {{ $mk->sks }} SKS) - {{ $mk->kurikulum->programStudi->nama_prodi ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Semester (Auto from Mata Kuliah) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Semester <span class="text-red-500">*</span>
                    </label>
                    @php
                        $currentJenisSemester = old('jenis_semester', $jadwal->jenis_semester);
                        $displayText = $currentJenisSemester == 'ganjil' ? 'Semester Ganjil' : 'Semester Genap';
                        if ($jadwal->mataKuliah) {
                            $displayText .= ' (Semester ' . $jadwal->mataKuliah->semester . ')';
                        }
                    @endphp
                    <input type="text" id="jenis_semester_display" disabled value="{{ $displayText }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    <input type="hidden" id="jenis_semester_input" name="jenis_semester" value="{{ $currentJenisSemester }}" required>
                    <p class="mt-1 text-xs text-blue-600">
                        <i class="fas fa-info-circle"></i>
                        Otomatis ditentukan dari semester mata kuliah (Ganjil: 1,3,5,7 | Genap: 2,4,6,8)
                    </p>
                    @error('jenis_semester')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dosen Pengajar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dosen Pengajar <span class="text-red-500">*</span>
                    </label>
                    <select id="dosen_select" name="dosen_id" required class="w-full">
                        <option value="">Pilih Dosen</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id', $jadwal->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nidn }} - {{ $dosen->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    @error('dosen_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ruangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select name="ruangan_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Ruangan</option>
                        @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ old('ruangan_id', $jadwal->ruangan_id) == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama_ruangan }} (Kapasitas: {{ $ruangan->kapasitas }})
                            </option>
                        @endforeach
                    </select>
                    @error('ruangan_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hari -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="hari" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Hari</option>
                        @foreach($hariOptions as $hari)
                            <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                    @error('hari')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_mulai" required value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jam_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_selesai" required value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jam_selesai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kelas" required value="{{ old('kelas', $jadwal->kelas) }}" placeholder="A, B, C, D, E, dll" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Isi kelas paralel (A, B, C), <strong>bukan angkatan</strong>. Jadwal berlaku untuk semua angkatan.
                    </p>
                    @error('kelas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Islamic Divider -->
            <div class="flex items-center justify-center py-4">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.jadwal.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md">
                    Update Jadwal
                </button>
            </div>
        </form>
    </x-islamic-card>
</div>
<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for Mata Kuliah
    $('#mata_kuliah_select').select2({
        placeholder: 'Cari mata kuliah...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Mata kuliah tidak ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });

    // Initialize Select2 for Dosen
    $('#dosen_select').select2({
        placeholder: 'Cari dosen...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Dosen tidak ditemukan";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });

    // Auto-set Jenis Semester based on Mata Kuliah
    $('#mata_kuliah_select').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const semester = selectedOption.data('semester');
        
        if (semester) {
            // Determine if ganjil (odd) or genap (even)
            const jenisSemester = (semester % 2 === 1) ? 'ganjil' : 'genap';
            const displayText = (semester % 2 === 1) ? 'Semester Ganjil' : 'Semester Genap';
            
            // Set hidden input value
            $('#jenis_semester_input').val(jenisSemester);
            
            // Set display text
            $('#jenis_semester_display').val(displayText + ' (Semester ' + semester + ')');
        } else {
            // Reset if no mata kuliah selected
            $('#jenis_semester_input').val('');
            $('#jenis_semester_display').val('Otomatis dari Mata Kuliah');
        }
    });

    // Trigger change on page load if mata kuliah already selected
    if ($('#mata_kuliah_select').val()) {
        $('#mata_kuliah_select').trigger('change');
    }
});
</script>

@endsection
