@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Nilai Mahasiswa</h1>
            <p class="text-gray-600 mt-1">{{ $mataKuliah->kode_mk }} - {{ $mataKuliah->nama_mk }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('dosen.nilai.create', $mataKuliah->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Input Nilai Baru</span>
            </a>
            <a href="{{ route('dosen.nilai.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-lg shadow-lg border border-green-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Mahasiswa</p>
                    <p class="text-white text-3xl font-bold mt-2">{{ $nilais->total() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow-lg border border-blue-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Rata-rata Nilai</p>
                    <p class="text-white text-3xl font-bold mt-2">{{ $nilais->count() > 0 ? number_format($nilais->avg('nilai_akhir'), 2) : '0.00' }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-6 rounded-lg shadow-lg border border-yellow-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Lulus</p>
                    <p class="text-white text-3xl font-bold mt-2">{{ $nilais->where('status', 'lulus')->count() }}</p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 p-6 rounded-lg shadow-lg border border-red-400">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">Tidak Lulus</p>
                    <p class="text-white text-3xl font-bold mt-2">{{ $nilais->where('status', 'tidak_lulus')->count() }}</p>
                </div>
                <div class="bg-red-400 bg-opacity-30 p-3 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Table -->
    <x-islamic-card title="Daftar Nilai">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-green-200 border border-green-200 rounded-lg">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase w-16">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Nama Mahasiswa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Semester</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-20">Tugas</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-20">UTS</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-20">UAS</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">Nilai Akhir</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-20">Grade</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nilais as $index => $nilai)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700 text-center">{{ $nilais->firstItem() + $index }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $nilai->mahasiswa->nim }}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $nilai->mahasiswa->nama_lengkap }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $nilai->semester ? $nilai->semester->tahun_akademik . ' - ' . ucfirst($nilai->semester->jenis) : '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center font-medium text-gray-900">{{ number_format($nilai->nilai_tugas, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-center font-medium text-gray-900">{{ number_format($nilai->nilai_uts, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-center font-medium text-gray-900">{{ number_format($nilai->nilai_uas, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-gray-800 text-lg">{{ number_format($nilai->nilai_akhir, 2) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <x-status-badge :status="$nilai->grade" type="grade" />
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $nilai->status == 'lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($nilai->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('dosen.nilai.edit', $nilai->id) }}" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('dosen.nilai.destroy', $nilai->id) }}" method="POST" onsubmit="return confirmDelete(this, 'nilai ini')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada nilai yang diinput</p>
                                <p class="text-sm">Klik tombol "Input Nilai Baru" untuk menambahkan nilai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($nilais->hasPages())
        <div class="mt-6">
            {{ $nilais->links() }}
        </div>
        @endif
    </x-islamic-card>

    <!-- Grade Distribution -->
    @if($nilais->count() > 0)
    <x-islamic-card title="Distribusi Nilai">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-4 rounded-lg text-white" style="background: #15803d; border: 2px solid #166534;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'A')->count() }}</p>
                <p class="text-sm mt-1">Grade A</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #16a34a; border: 2px solid #15803d;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'A-')->count() }}</p>
                <p class="text-sm mt-1">Grade A-</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #22c55e; border: 2px solid #16a34a;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'B+')->count() }}</p>
                <p class="text-sm mt-1">Grade B+</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #4ade80; border: 2px solid #22c55e;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'B')->count() }}</p>
                <p class="text-sm mt-1">Grade B</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #14b8a6; border: 2px solid #0d9488;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'B-')->count() }}</p>
                <p class="text-sm mt-1">Grade B-</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #eab308; border: 2px solid #ca8a04;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'C+')->count() }}</p>
                <p class="text-sm mt-1">Grade C+</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #ca8a04; border: 2px solid #a16207;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'C')->count() }}</p>
                <p class="text-sm mt-1">Grade C</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #f97316; border: 2px solid #ea580c;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'C-')->count() }}</p>
                <p class="text-sm mt-1">Grade C-</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #ea580c; border: 2px solid #c2410c;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'D')->count() }}</p>
                <p class="text-sm mt-1">Grade D</p>
            </div>
            <div class="text-center p-4 rounded-lg text-white" style="background: #ef4444; border: 2px solid #dc2626;">
                <p class="text-3xl font-bold">{{ $nilais->where('grade', 'E')->count() }}</p>
                <p class="text-sm mt-1">Grade E</p>
            </div>
        </div>
    </x-islamic-card>
    @endif
</div>
@endsection
