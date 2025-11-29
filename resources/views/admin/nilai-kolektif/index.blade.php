@extends('layouts.admin')

@section('title', 'Input Nilai Batch (Kolektif)')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2" style="color: #1F2937;">Input Nilai Batch (Kolektif)</h1>
        <p style="color: #4B5563;">Input nilai untuk seluruh mahasiswa dalam 1 prodi + angkatan sekaligus</p>
    </div>

    @if(session('success'))
        <div class="border px-6 py-4 rounded-lg relative mb-6" role="alert" style="background-color: #DCFCE7; border-color: #4ADE80; color: #15803D;">
            <span class="font-semibold">✅ {{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="border px-6 py-4 rounded-lg relative mb-6" role="alert" style="background-color: #FEE2E2; border-color: #F87171; color: #B91C1C;">
            <span class="font-semibold">❌ {{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2" style="border-color: #D4AF37;">
        <div class="px-6 py-4" style="background: linear-gradient(to right, #2D5F3F, #4A7C59);">
            <h3 class="text-xl font-bold text-white">📚 Pilih Program Studi, Angkatan & Semester</h3>
        </div>

        <form action="{{ route('admin.nilai-kolektif.preview') }}" method="GET" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Program Studi -->
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #374151;">
                        Program Studi <span style="color: #DC2626;">*</span>
                    </label>
                    <select name="program_studi_id" 
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:outline-none"
                            style="border-color: #D1D5DB; focus:ring-color: #2D5F3F; focus:border-color: #2D5F3F;">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($programStudis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Angkatan -->
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #374151;">
                        Angkatan <span style="color: #DC2626;">*</span>
                    </label>
                    <select name="angkatan" 
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:outline-none"
                            style="border-color: #D1D5DB; focus:ring-color: #2D5F3F; focus:border-color: #2D5F3F;">
                        <option value="">-- Pilih Angkatan --</option>
                        @foreach($angkatans as $angkatan)
                            <option value="{{ $angkatan }}" {{ old('angkatan') == $angkatan ? 'selected' : '' }}>
                                {{ $angkatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #374151;">
                        Semester <span style="color: #DC2626;">*</span>
                    </label>
                    <select name="semester_id" 
                            required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:outline-none"
                            style="border-color: #D1D5DB; focus:ring-color: #2D5F3F; focus:border-color: #2D5F3F;">
                        <option value="">-- Pilih Semester --</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->nama_semester }}@if($semester->is_active) ⭐ (Aktif)@endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2"
                        style="background: linear-gradient(to right, #2D5F3F, #4A7C59);"
                        onmouseover="this.style.background='linear-gradient(to right, #1F4530, #2D5F3F)'"
                        onmouseout="this.style.background='linear-gradient(to right, #2D5F3F, #4A7C59)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    🔍 Tampilkan Mahasiswa & Mata Kuliah
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-8 border-2 rounded-lg p-6" style="background-color: #ECFDF5; border-color: #D4AF37;">
        <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 mt-0.5" style="color: #2D5F3F;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-lg font-semibold mb-2" style="color: #2D5F3F;">ℹ️ Cara Menggunakan Input Nilai Batch</h4>
                <ul class="text-sm space-y-1" style="color: #065F46;">
                    <li>• <strong>Pilih Filter:</strong> Program Studi, Angkatan, dan Semester yang akan diinput nilai</li>
                    <li>• <strong>Preview Grid:</strong> System akan menampilkan semua mahasiswa dan mata kuliah dalam bentuk grid</li>
                    <li>• <strong>Input Nilai:</strong> Masukkan nilai angka (0-100), system otomatis calculate grade & status</li>
                    <li>• <strong>Auto Calculate:</strong> Grade A-E dan status Lulus/Tidak Lulus otomatis dihitung</li>
                    <li>• <strong>Visual Feedback:</strong> Hijau = Lulus (grade ≥ C), Merah = Tidak Lulus (grade < C)</li>
                    <li>• <strong>Batch Save:</strong> Klik "Simpan Semua Nilai" untuk save sekaligus</li>
                    <li>• <strong>Auto KHS:</strong> System otomatis generate KHS (IP & IPK) setelah save</li>
                    <li>• <strong>KRS Mengulang:</strong> MK dengan status "Tidak Lulus" otomatis muncul di list mengulang mahasiswa</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Example Use Case -->
    <div class="mt-6 border rounded-lg p-6" style="background-color: #FEF3C7; border-color: #F59E0B;">
        <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 mt-0.5" style="color: #D97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <div>
                <h4 class="text-lg font-semibold mb-2" style="color: #D97706;">💡 Contoh Use Case</h4>
                <div class="text-sm space-y-2" style="color: #92400E;">
                    <p><strong>Scenario:</strong> Input nilai semester 1 untuk angkatan 2022 program studi PAI</p>
                    <p><strong>Filter:</strong> Prodi = PAI, Angkatan = 2022, Semester = Semester 1</p>
                    <p><strong>Result:</strong> 25 mahasiswa × 9 mata kuliah = 225 nilai</p>
                    <p><strong>Time:</strong> Input 225 nilai dalam 1 page (±15 menit)</p>
                    <p><strong>Output:</strong> Nilai tersimpan + KHS ter-generate + MK tidak lulus muncul di mengulang</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
