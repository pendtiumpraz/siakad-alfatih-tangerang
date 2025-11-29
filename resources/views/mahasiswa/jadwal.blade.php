@extends('layouts.mahasiswa')

@section('title', 'Jadwal Kuliah')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Jadwal Kuliah</h1>
        @if(isset($semester))
            <div class="text-right">
                <p class="text-sm text-gray-600">Semester Aktif</p>
                <p class="text-lg font-semibold text-[#D4AF37]">{{ $semester->tahun_akademik }} - {{ ucfirst($semester->jenis) }}</p>
            </div>
        @endif
    </div>

    <div class="islamic-divider"></div>

    @if(isset($mataKuliahKrs) && $mataKuliahKrs->count() > 0)
        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Menampilkan jadwal untuk <strong>{{ $mataKuliahKrs->count() }} mata kuliah</strong> yang sudah disetujui di KRS Anda.
                    </p>
                </div>
            </div>
        </div>
    @endif

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
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        @if(session('info'))
                            {{ session('info') }}
                        @elseif(isset($semester))
                            Belum ada jadwal yang tersedia. Pastikan KRS Anda sudah disetujui.
                        @else
                            Tidak ada semester aktif saat ini.
                        @endif
                    </p>
                    @if(!session('info') && isset($semester))
                        <p class="text-sm text-yellow-700 mt-2">
                            <a href="{{ route('mahasiswa.krs.index') }}" class="font-medium underline hover:text-yellow-800">
                                Klik di sini untuk mengisi KRS
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
