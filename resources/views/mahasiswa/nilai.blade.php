@extends('layouts.mahasiswa')

@section('title', 'Nilai')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Nilai</h1>
    </div>

    <div class="islamic-divider"></div>

    <!-- Filter Semester -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('mahasiswa.nilai.index') }}" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester_id" id="semester_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->nama_semester }} - {{ $semester->tahun_akademik }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#C4A137] transition-colors">
                Filter
            </button>
        </form>
    </div>

    @if($nilais->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Angka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Huruf</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($nilais as $nilai)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $nilai->semester->nama_semester ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $nilai->nilai_akhir ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 rounded font-medium {{ in_array($nilai->grade, ['A+', 'A']) ? 'bg-green-100 text-green-800' : (in_array($nilai->grade, ['B+', 'B']) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $nilai->grade ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-yellow-700">Belum ada data nilai.</p>
        </div>
    @endif
</div>
@endsection
