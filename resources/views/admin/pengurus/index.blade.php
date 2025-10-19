@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <i class="fas fa-user-tie text-[#4A7C59]"></i>
                <span>Manajemen Pengurus</span>
            </h1>
            <p class="text-gray-600 mt-1">Kelola Ketua Prodi dan Dosen Wali</p>
        </div>
        <a href="{{ route('admin.pengurus.dosen-wali') }}" class="bg-[#4A7C59] hover:bg-[#3d6849] text-white px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Kelola Dosen Wali</span>
        </a>
    </div>

    <div class="islamic-divider"></div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-islamic p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Program Studi</p>
                    <p class="text-3xl font-bold text-[#4A7C59]">{{ $programStudis->count() }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-[#4A7C59] to-[#5a9c6f] rounded-full flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="card-islamic p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Ketua Prodi Ditunjuk</p>
                    <p class="text-3xl font-bold text-[#D4AF37]">{{ $programStudis->whereNotNull('ketua_prodi_id')->count() }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] rounded-full flex items-center justify-center">
                    <i class="fas fa-user-tie text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="card-islamic p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Mahasiswa Tanpa Wali</p>
                    <p class="text-3xl font-bold text-red-600">{{ $mahasiswaTanpaWali }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-slash text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Ketua Prodi Management -->
    <div class="card-islamic">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                <i class="fas fa-crown text-[#D4AF37]"></i>
                <span>Ketua Program Studi</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($programStudis as $prodi)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $prodi->nama_prodi }}</h3>
                                <p class="text-sm text-gray-600">{{ $prodi->kode_prodi }} - {{ $prodi->jenjang }}</p>
                            </div>
                            @if($prodi->akreditasi)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    Akreditasi {{ $prodi->akreditasi }}
                                </span>
                            @endif
                        </div>

                        @if($prodi->ketuaProdi)
                            <!-- Display Current Ketua Prodi -->
                            <div class="bg-gradient-to-r from-[#F4E5C3] to-[#D4AF37]/20 p-4 rounded-lg mb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-[#D4AF37] rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($prodi->ketuaProdi->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $prodi->ketuaProdi->gelar_depan }} {{ $prodi->ketuaProdi->nama_lengkap }} {{ $prodi->ketuaProdi->gelar_belakang }}
                                            </p>
                                            <p class="text-sm text-gray-600">NIDN: {{ $prodi->ketuaProdi->nidn }}</p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('admin.pengurus.remove-ketua-prodi', $prodi->id) }}" onsubmit="return confirm('Yakin ingin menghapus ketua prodi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-times-circle text-xl"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Form to Assign Ketua Prodi -->
                            <form method="POST" action="{{ route('admin.pengurus.assign-ketua-prodi') }}">
                                @csrf
                                <input type="hidden" name="program_studi_id" value="{{ $prodi->id }}">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Dosen sebagai Ketua Prodi</label>
                                    <select name="dosen_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                                        <option value="">-- Pilih Dosen --</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->id }}">
                                                {{ $dosen->gelar_depan }} {{ $dosen->nama_lengkap }} {{ $dosen->gelar_belakang }} ({{ $dosen->nidn }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white py-2 rounded-lg font-semibold transition">
                                    <i class="fas fa-plus-circle mr-2"></i>Tunjuk Ketua Prodi
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Dosen Wali Statistics -->
    <div class="card-islamic">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                    <i class="fas fa-chalkboard-teacher text-[#4A7C59]"></i>
                    <span>Statistik Dosen Wali</span>
                </h2>
                <a href="{{ route('admin.pengurus.dosen-wali') }}" class="text-[#4A7C59] hover:text-[#3d6849] font-semibold">
                    Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dosen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIDN</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Mahasiswa Bimbingan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dosenWaliStats->sortByDesc('mahasiswa_bimbingan_count')->take(10) as $dosen)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $dosen->gelar_depan }} {{ $dosen->nama_lengkap }} {{ $dosen->gelar_belakang }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $dosen->nidn }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $dosen->mahasiswa_bimbingan_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $dosen->mahasiswa_bimbingan_count }} Mahasiswa
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Belum ada data dosen</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
