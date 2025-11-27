@extends('layouts.admin')

@section('title', 'Input Nilai - Grid')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2" style="color: #1F2937;">Input Nilai Batch</h1>
        <nav class="text-sm" style="color: #6B7280;">
            <a href="{{ route('admin.nilai-kolektif.index') }}" class="hover:underline" style="color: #2D5F3F;">Input Nilai Batch</a>
            <span class="mx-2">/</span>
            <span class="font-semibold" style="color: #1F2937;">Grid Input</span>
        </nav>
    </div>

    <!-- Summary Info -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4" style="border-color: #2D5F3F;">
            <div class="text-sm mb-1" style="color: #4B5563;">Program Studi</div>
            <div class="text-lg font-bold" style="color: #1F2937;">{{ $prodi->nama_prodi }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4" style="border-color: #F59E0B;">
            <div class="text-sm mb-1" style="color: #4B5563;">Angkatan</div>
            <div class="text-lg font-bold" style="color: #1F2937;">{{ $validated['angkatan'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4" style="border-color: #3B82F6;">
            <div class="text-sm mb-1" style="color: #4B5563;">Semester</div>
            <div class="text-lg font-bold" style="color: #1F2937;">Semester {{ $semesterNumber }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4" style="border-color: #8B5CF6;">
            <div class="text-sm mb-1" style="color: #4B5563;">Total Input</div>
            <div class="text-lg font-bold" style="color: #1F2937;">{{ $mahasiswas->count() }} × {{ $mataKuliahs->count() }} = {{ $totalInput }}</div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.nilai-kolektif.store') }}" method="POST" id="nilaiForm" onsubmit="return confirmSubmit()">
        @csrf
        <input type="hidden" name="program_studi_id" value="{{ $validated['program_studi_id'] }}">
        <input type="hidden" name="angkatan" value="{{ $validated['angkatan'] }}">
        <input type="hidden" name="semester_id" value="{{ $validated['semester_id'] }}">

        <!-- Input Grid -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border-2" style="border-color: #D4AF37;">
            <div class="px-6 py-4" style="background: linear-gradient(to right, #2D5F3F, #4A7C59);">
                <h3 class="text-xl font-bold text-white">📝 Input Nilai ({{ $mahasiswas->count() }} Mahasiswa × {{ $mataKuliahs->count() }} MK)</h3>
                <p class="text-sm mt-1" style="color: #D1FAE5;">Input nilai angka (0-100), grade & status otomatis dihitung</p>
            </div>

            <div class="p-6 overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr style="background-color: #F3F4F6;">
                            <th class="border px-3 py-3 text-left sticky left-0 z-10" style="background-color: #F3F4F6; min-width: 180px;">
                                <div class="font-bold" style="color: #1F2937;">Mahasiswa</div>
                                <div class="text-xs font-normal" style="color: #6B7280;">NIM - Nama</div>
                            </th>
                            @foreach($mataKuliahs as $mk)
                                <th class="border px-3 py-3 text-center" style="min-width: 150px;">
                                    <div class="font-bold" style="color: #1F2937;">{{ $mk->kode_mk }}</div>
                                    <div class="text-xs font-normal" style="color: #6B7280;">{{ $mk->nama_mk }}</div>
                                    <div class="text-xs" style="color: #9CA3AF;">{{ $mk->sks }} SKS</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $mhs)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-3 py-3 sticky left-0 z-10 bg-white">
                                    <div class="font-semibold" style="color: #1F2937;">{{ $mhs->nim }}</div>
                                    <div class="text-xs" style="color: #6B7280;">{{ $mhs->nama_lengkap }}</div>
                                </td>
                                @foreach($mataKuliahs as $mk)
                                    @php
                                        $existingNilaiItem = $existingNilai->get($mhs->id)?->where('mata_kuliah_id', $mk->id)->first();
                                    @endphp
                                    <td class="border px-2 py-2 text-center">
                                        <div class="nilai-cell" data-mahasiswa-id="{{ $mhs->id }}" data-mk-id="{{ $mk->id }}">
                                            <input type="number" 
                                                   name="nilai[{{ $mhs->id }}][{{ $mk->id }}]" 
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   value="{{ $existingNilaiItem ? $existingNilaiItem->nilai : '' }}"
                                                   class="nilai-input w-full px-2 py-2 text-center border rounded focus:ring-2 focus:outline-none text-lg font-bold"
                                                   style="border-color: #D1D5DB;"
                                                   placeholder="0-100"
                                                   onkeyup="calculateGrade(this)"
                                                   onchange="calculateGrade(this)">
                                            <div class="grade-display mt-1 text-xs font-semibold"></div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 border-t" style="background-color: #F9FAFB; border-color: #E5E7EB;">
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.nilai-kolektif.index') }}" 
                       class="px-6 py-2 border rounded-lg font-semibold transition"
                       style="border-color: #D1D5DB; color: #6B7280;"
                       onmouseover="this.style.backgroundColor='#F3F4F6'"
                       onmouseout="this.style.backgroundColor='transparent'">
                        ← Kembali
                    </a>
                    <div class="flex gap-3">
                        <button type="button" 
                                onclick="resetForm()"
                                class="px-6 py-2 border rounded-lg font-semibold transition"
                                style="border-color: #F59E0B; color: #F59E0B;"
                                onmouseover="this.style.backgroundColor='#FEF3C7'"
                                onmouseout="this.style.backgroundColor='transparent'">
                            🔄 Reset
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2"
                                style="background: linear-gradient(to right, #16A34A, #15803D);"
                                onmouseover="this.style.background='linear-gradient(to right, #15803D, #166534)'"
                                onmouseout="this.style.background='linear-gradient(to right, #16A34A, #15803D)'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            💾 Simpan Semua Nilai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-4 border" style="border-color: #E5E7EB;">
        <h4 class="text-sm font-bold mb-3" style="color: #374151;">📊 Keterangan Grade & Status:</h4>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-xs">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #DCFCE7; color: #166534;">A</div>
                <div><strong>85-100:</strong> A (4.0) ✓</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #D1FAE5; color: #065F46;">A-</div>
                <div><strong>80-84:</strong> A- (3.7) ✓</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #D1FAE5; color: #065F46;">B+</div>
                <div><strong>75-79:</strong> B+ (3.3) ✓</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #DBEAFE; color: #1E40AF;">B</div>
                <div><strong>70-74:</strong> B (3.0) ✓</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #DBEAFE; color: #1E40AF;">B-</div>
                <div><strong>65-69:</strong> B- (2.7) ✓</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #FEF3C7; color: #92400E;">C+</div>
                <div><strong>60-64:</strong> C+ (2.3) ✓</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #FEF3C7; color: #92400E;">C</div>
                <div><strong>55-59:</strong> C (2.0) ✓</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #FEE2E2; color: #991B1B;">D</div>
                <div><strong>50-54:</strong> D (1.0) ✗</div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded flex items-center justify-center font-bold" style="background-color: #FEE2E2; color: #991B1B;">E</div>
                <div><strong>0-49:</strong> E (0.0) ✗</div>
            </div>
        </div>
        <div class="mt-3 text-xs" style="color: #6B7280;">
            <strong>Keterangan:</strong> ✓ = Lulus (Grade ≥ C), ✗ = Tidak Lulus (Grade < C)
        </div>
    </div>
</div>

<script>
// Calculate grade and status from nilai
function calculateGrade(input) {
    const nilai = parseFloat(input.value);
    const gradeDisplay = input.parentElement.querySelector('.grade-display');
    
    if (isNaN(nilai) || nilai === '') {
        gradeDisplay.innerHTML = '';
        input.style.backgroundColor = '';
        return;
    }
    
    let grade, bobot, status, bgColor, textColor;
    
    if (nilai >= 85) {
        grade = 'A'; bobot = '4.0'; status = '✓ Lulus'; 
        bgColor = '#DCFCE7'; textColor = '#166534';
    } else if (nilai >= 80) {
        grade = 'A-'; bobot = '3.7'; status = '✓ Lulus';
        bgColor = '#D1FAE5'; textColor = '#065F46';
    } else if (nilai >= 75) {
        grade = 'B+'; bobot = '3.3'; status = '✓ Lulus';
        bgColor = '#D1FAE5'; textColor = '#065F46';
    } else if (nilai >= 70) {
        grade = 'B'; bobot = '3.0'; status = '✓ Lulus';
        bgColor = '#DBEAFE'; textColor = '#1E40AF';
    } else if (nilai >= 65) {
        grade = 'B-'; bobot = '2.7'; status = '✓ Lulus';
        bgColor = '#DBEAFE'; textColor = '#1E40AF';
    } else if (nilai >= 60) {
        grade = 'C+'; bobot = '2.3'; status = '✓ Lulus';
        bgColor = '#FEF3C7'; textColor = '#92400E';
    } else if (nilai >= 55) {
        grade = 'C'; bobot = '2.0'; status = '✓ Lulus';
        bgColor = '#FEF3C7'; textColor = '#92400E';
    } else if (nilai >= 50) {
        grade = 'D'; bobot = '1.0'; status = '✗ Tidak Lulus';
        bgColor = '#FEE2E2'; textColor = '#991B1B';
    } else {
        grade = 'E'; bobot = '0.0'; status = '✗ Tidak Lulus';
        bgColor = '#FEE2E2'; textColor = '#991B1B';
    }
    
    input.style.backgroundColor = bgColor;
    input.style.color = textColor;
    gradeDisplay.innerHTML = `<strong>${grade}</strong> (${bobot}) ${status}`;
    gradeDisplay.style.color = textColor;
}

// Initialize existing values
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nilai-input').forEach(input => {
        if (input.value !== '') {
            calculateGrade(input);
        }
    });
});

// Confirm before submit
function confirmSubmit() {
    const filledCount = Array.from(document.querySelectorAll('.nilai-input'))
        .filter(input => input.value !== '').length;
    
    if (filledCount === 0) {
        alert('Belum ada nilai yang diinput!');
        return false;
    }
    
    return confirm(`Simpan ${filledCount} nilai?\n\nNilai akan disimpan dan KHS akan di-generate otomatis.`);
}

// Reset form
function resetForm() {
    if (confirm('Reset semua input nilai?')) {
        document.querySelectorAll('.nilai-input').forEach(input => {
            input.value = '';
            input.style.backgroundColor = '';
            input.style.color = '';
            input.parentElement.querySelector('.grade-display').innerHTML = '';
        });
    }
}
</script>
@endsection
