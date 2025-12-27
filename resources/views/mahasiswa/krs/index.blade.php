@extends('layouts.mahasiswa')

@section('title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Kartu Rencana Studi (KRS)</h1>
        <p class="text-gray-600">Semester {{ $activeSemester->nama_semester }} - {{ $activeSemester->tahun_akademik }}</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Info & Stats -->
        <div class="lg:col-span-1">
            <!-- Mahasiswa Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Mahasiswa</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">NIM</p>
                        <p class="font-semibold text-gray-800">{{ $mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama</p>
                        <p class="font-semibold text-gray-800">{{ $mahasiswa->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Program Studi</p>
                        <p class="font-semibold text-gray-800">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- SKS Stats -->
            <div class="bg-gradient-to-br from-[#4A7C59] to-[#3d6849] rounded-lg shadow-md p-6 text-white mb-6">
                <h3 class="text-lg font-semibold mb-4">Total SKS</h3>
                <div class="text-center">
                    <div class="text-5xl font-bold mb-2">{{ $totalSks }}</div>
                    <div class="text-sm opacity-90">SKS yang diambil semester ini</div>
                    <p class="text-xs mt-3 opacity-75 bg-white/10 rounded px-3 py-2">
                        Tidak ada batas maksimal SKS. Anda wajib mengambil semua mata kuliah wajib semester ini.
                    </p>
                </div>
            </div>

            <!-- Status KRS -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Status KRS</h3>
                
                @if($krsStatus == 'draft')
                    <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                        📝 Draft (Belum Submit)
                    </span>
                    <p class="text-sm text-gray-600 mt-3">
                        KRS masih bisa diubah. Jangan lupa submit setelah selesai memilih mata kuliah.
                    </p>
                @elseif($krsStatus == 'submitted')
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                        ⏳ Menunggu Persetujuan
                    </span>
                    <p class="text-sm text-gray-600 mt-3">
                        KRS sudah disubmit dan menunggu persetujuan dari Dosen PA.
                    </p>
                @elseif($krsStatus == 'approved')
                    <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                        ✅ Disetujui
                    </span>
                    <p class="text-sm text-gray-600 mt-3">
                        KRS sudah disetujui. Anda bisa mencetak KRS.
                    </p>
                @elseif($krsStatus == 'rejected')
                    <span class="inline-block px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                        ❌ Ditolak
                    </span>
                    <p class="text-sm text-gray-600 mt-3">
                        KRS ditolak. Silakan edit dan submit ulang.
                    </p>
                @endif
            </div>
        </div>

        <!-- Right Column - KRS Form -->
        <div class="lg:col-span-2">
            <!-- Existing KRS -->
            @if($krsItems->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Mata Kuliah yang Diambil</h3>
                        @if(!$isSubmitted)
                            <form action="{{ route('mahasiswa.krs.submit') }}" method="POST" onsubmit="return confirm('Submit KRS? Setelah submit, KRS tidak bisa diubah lagi.')">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-[#4A7C59] text-white rounded-lg hover:bg-[#3d6849] transition font-semibold cursor-pointer" style="position: relative; z-index: 10;">
                                    📤 Submit KRS
                                </button>
                            </form>
                        @elseif($krsStatus == 'approved')
                            <a href="{{ route('mahasiswa.krs.print') }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                🖨️ Cetak KRS
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode MK</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mata Kuliah</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">SKS</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                    @if(!$isSubmitted)
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($krsItems as $index => $krs)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $krs->mataKuliah->kode_mk }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $krs->mataKuliah->nama_mk }}</td>
                                        <td class="px-4 py-3 text-sm text-center font-semibold text-gray-900">{{ $krs->mataKuliah->sks }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($krs->is_mengulang)
                                                <span class="inline-block px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs font-semibold">
                                                    Mengulang
                                                </span>
                                            @else
                                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                                    {{ ucfirst($krs->mataKuliah->jenis) }}
                                                </span>
                                            @endif
                                        </td>
                                        @if(!$isSubmitted)
                                            <td class="px-4 py-3 text-center">
                                                @if($krs->is_mengulang)
                                                    <form action="{{ route('mahasiswa.krs.destroy', $krs->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Hapus mata kuliah ini dari KRS?')"
                                                                class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 text-xs">Wajib</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-900 text-right">Total SKS:</td>
                                    <td class="px-4 py-3 text-sm font-bold text-center text-[#4A7C59]">{{ $totalSks }}</td>
                                    <td colspan="{{ !$isSubmitted ? '2' : '1' }}"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6 text-center">
                    <svg class="w-16 h-16 text-yellow-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-yellow-800 mb-2">KRS Masih Kosong</h4>
                    <p class="text-yellow-700">Silakan pilih mata kuliah di bawah untuk mengisi KRS Anda.</p>
                </div>
            @endif

            <!-- Add Mata Kuliah - Only if draft -->
            @if(!$isSubmitted)
                <!-- Info: Mata Kuliah Wajib sudah ditambahkan otomatis -->
                @if($mataKuliahWajibCount > 0 && $mataKuliahMengulangCount == 0)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-lg font-semibold text-blue-800 mb-2">✅ Mata Kuliah Wajib Sudah Ditambahkan</h4>
                                <p class="text-sm text-blue-700">
                                    Sistem telah <strong>otomatis menambahkan</strong> semua mata kuliah wajib untuk semester ini ke KRS Anda.
                                    Total <strong>{{ $mataKuliahWajibCount }} mata kuliah wajib</strong> telah ditambahkan.
                                </p>
                                <p class="text-sm text-blue-700 mt-2">
                                    Anda bisa menambahkan mata kuliah mengulang di bawah (jika ada), lalu klik <strong>"Submit KRS"</strong> setelah selesai.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Mata Kuliah Mengulang -->
                @if($failedMataKuliahs->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">🔄 Mata Kuliah Mengulang (Opsional)</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Mata kuliah berikut belum lulus dari <strong>semua semester sebelumnya</strong>. 
                            Anda dapat memilih untuk mengulang <strong>kapan saja</strong> (maksimal semester 14).
                        </p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4 text-sm text-yellow-800">
                            ⚠️ <strong>Perhatian:</strong> Jadwal mata kuliah mengulang tidak boleh bentrok dengan jadwal mata kuliah semester ini!
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($failedMataKuliahs as $mk)
                                <div class="flex items-center justify-between p-4 border border-orange-200 rounded-lg bg-orange-50 hover:bg-orange-100 transition">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-semibold text-gray-900">{{ $mk->kode_mk }}</span>
                                            <span class="px-2 py-0.5 bg-orange-100 text-orange-800 rounded text-xs font-semibold">Mengulang</span>
                                            <span class="px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-semibold">Tidak Lulus</span>
                                        </div>
                                        <p class="text-sm text-gray-800 font-medium">{{ $mk->nama_mk }}</p>
                                        <p class="text-xs text-gray-600 mt-1">SKS: {{ $mk->sks }} | Semester: {{ $mk->semester }}</p>
                                        
                                        @php
                                            $jadwal = $mk->jadwals->first();
                                        @endphp
                                        @if($jadwal)
                                            <p class="text-xs text-blue-600 mt-1">
                                                📅 Jadwal: {{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} | {{ $jadwal->ruangan->nama_ruangan ?? '-' }}
                                            </p>
                                        @else
                                            <p class="text-xs text-red-600 mt-1">
                                                ⚠️ Tidak ada jadwal untuk semester ini
                                            </p>
                                        @endif
                                    </div>
                                    
                                    @if($jadwal)
                                        <form action="{{ route('mahasiswa.krs.store') }}" method="POST" class="ml-4">
                                            @csrf
                                            <input type="hidden" name="mata_kuliah_id" value="{{ $mk->id }}">
                                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-sm font-semibold">
                                                + Ambil Mengulang
                                            </button>
                                        </form>
                                    @else
                                        <span class="ml-4 text-gray-400 text-xs">Tidak tersedia</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($mataKuliahWajibCount == 0 && $failedMataKuliahs->count() == 0)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Mata Kuliah Tersedia</h4>
                        <p class="text-gray-600">
                            Tidak ada mata kuliah wajib atau mata kuliah mengulang untuk semester ini.
                        </p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
