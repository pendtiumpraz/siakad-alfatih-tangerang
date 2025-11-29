@extends('layouts.mahasiswa')

@section('title', 'Jadwal Kuliah')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Kuliah</h1>
    </div>

    <div class="islamic-divider"></div>

    <!-- Filter Semester -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('mahasiswa.jadwal.index') }}" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester_id" id="semester_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent">
                    <option value="">-- Pilih Semester --</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ request('semester_id') == $sem->id || (!request('semester_id') && isset($semester) && $semester->id == $sem->id) ? 'selected' : '' }}>
                            {{ $sem->tahun_akademik }} - {{ ucfirst($sem->jenis) }}{{ $sem->is_active ? ' ⭐ Aktif' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#C4A137] transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    @if(isset($jadwals) && $jadwals->count() > 0)
        <!-- Jadwal Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jadwals as $jadwal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $jadwal->hari }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $jadwal->mataKuliah->nama_mk }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $jadwal->dosen->nama_lengkap ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $jadwal->kelas }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $jadwal->ruangan->nama_ruangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-yellow-700">
                @if(isset($semester))
                    Tidak ada jadwal untuk semester {{ $semester->nama_semester }}.
                @else
                    Tidak ada semester aktif. Silakan pilih semester.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
