@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Import Master Data</h1>
            <p class="text-gray-600 mt-1">Import data master dari file CSV</p>
        </div>
    </div>

    <!-- Import Result -->
    @if(session('import_result'))
        @php $result = session('import_result'); @endphp
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4">Hasil Import</h3>
            
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Data</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $result['total'] }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Berhasil</p>
                    <p class="text-2xl font-bold text-green-600">{{ $result['successCount'] }}</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Gagal</p>
                    <p class="text-2xl font-bold text-red-600">{{ $result['errorCount'] }}</p>
                </div>
            </div>

            @if($result['errorCount'] > 0)
                <div class="mt-4">
                    <h4 class="font-semibold text-red-700 mb-2">Detail Error:</h4>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 max-h-96 overflow-y-auto">
                        @foreach($result['errors'] as $error)
                            <div class="mb-3 pb-3 border-b border-red-200 last:border-0">
                                <p class="font-semibold text-sm text-red-800">Baris {{ $error['row'] }}:</p>
                                <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                                    @foreach($error['errors'] as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                                <p class="text-xs text-gray-600 mt-1">Data: {{ json_encode($error['data']) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Import Forms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- 1. Program Studi -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-[#2D5F3F] to-[#4A7C59] rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-university text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Program Studi</h3>
                    <p class="text-xs text-gray-600">Import data program studi</p>
                </div>
            </div>
            
            <form action="{{ route('admin.import.program-studi') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <input type="file" name="file" accept=".csv" required
                        class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-[#2D5F3F]">
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-[#2D5F3F] text-white px-4 py-2 rounded-lg hover:bg-[#1f4429] transition text-sm font-semibold">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                    <a href="{{ route('admin.template.program-studi') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-semibold">
                        <i class="fas fa-download mr-1"></i> Template
                    </a>
                </div>
            </form>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-600 font-semibold mb-1">Format CSV:</p>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded block overflow-x-auto">kode_prodi, nama_prodi, jenjang, akreditasi, is_active</code>
            </div>
        </div>

        <!-- 2. Kurikulum -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-book-open text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Kurikulum</h3>
                    <p class="text-xs text-gray-600">Import data kurikulum</p>
                </div>
            </div>
            
            <form action="{{ route('admin.import.kurikulum') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <input type="file" name="file" accept=".csv" required
                        class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm font-semibold">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                    <a href="{{ route('admin.template.kurikulum') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-semibold">
                        <i class="fas fa-download mr-1"></i> Template
                    </a>
                </div>
            </form>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-600 font-semibold mb-1">Format CSV:</p>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded block overflow-x-auto">kode_prodi, nama_kurikulum, tahun_mulai, tahun_selesai, total_sks, is_active</code>
            </div>
        </div>

        <!-- 3. Mata Kuliah -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-3" style="background: linear-gradient(to bottom right, #7C3AED, #5B21B6);">
                    <i class="fas fa-book text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Mata Kuliah</h3>
                    <p class="text-xs text-gray-600">Import data mata kuliah</p>
                </div>
            </div>
            
            <form action="{{ route('admin.import.mata-kuliah') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <input type="file" name="file" accept=".csv" required
                        class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:ring-2" style="--tw-ring-color: #7C3AED;">
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 text-white px-4 py-2 rounded-lg transition text-sm font-semibold" style="background-color: #7C3AED;" onmouseover="this.style.backgroundColor='#6D28D9'" onmouseout="this.style.backgroundColor='#7C3AED'">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                    <a href="{{ route('admin.template.mata-kuliah') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-semibold">
                        <i class="fas fa-download mr-1"></i> Template
                    </a>
                </div>
            </form>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-600 font-semibold mb-1">Format CSV:</p>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded block overflow-x-auto">kurikulum_nama, kode_mk, nama_mk, sks, semester, jenis, deskripsi</code>
            </div>
        </div>

        <!-- 4. Ruangan -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-3" style="background: linear-gradient(to bottom right, #F97316, #C2410C);">
                    <i class="fas fa-door-open text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Ruangan</h3>
                    <p class="text-xs text-gray-600">Import data ruangan</p>
                </div>
            </div>
            
            <form action="{{ route('admin.import.ruangan') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <input type="file" name="file" accept=".csv" required
                        class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:ring-2" style="--tw-ring-color: #F97316;">
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 text-white px-4 py-2 rounded-lg transition text-sm font-semibold" style="background-color: #F97316;" onmouseover="this.style.backgroundColor='#EA580C'" onmouseout="this.style.backgroundColor='#F97316'">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                    <a href="{{ route('admin.template.ruangan') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-semibold">
                        <i class="fas fa-download mr-1"></i> Template
                    </a>
                </div>
            </form>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-600 font-semibold mb-1">Format CSV:</p>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded block overflow-x-auto">kode_ruangan, nama_ruangan, kapasitas, jenis, fasilitas, is_available</code>
            </div>
        </div>

        <!-- 5. Semester -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-3" style="background: linear-gradient(to bottom right, #0891B2, #0E7490);">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Semester</h3>
                    <p class="text-xs text-gray-600">Import data semester</p>
                </div>
            </div>
            
            <form action="{{ route('admin.import.semester') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <input type="file" name="file" accept=".csv" required
                        class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:ring-2" style="--tw-ring-color: #0891B2;">
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 text-white px-4 py-2 rounded-lg transition text-sm font-semibold" style="background-color: #0891B2;" onmouseover="this.style.backgroundColor='#0E7490'" onmouseout="this.style.backgroundColor='#0891B2'">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                    <a href="{{ route('admin.template.semester') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-semibold">
                        <i class="fas fa-download mr-1"></i> Template
                    </a>
                </div>
            </form>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-600 font-semibold mb-1">Format CSV:</p>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded block overflow-x-auto">nama_semester, tahun_akademik, jenis, tanggal_mulai, tanggal_selesai, is_active</code>
            </div>
        </div>

        <!-- 6. Jadwal -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Jadwal Perkuliahan</h3>
                    <p class="text-xs text-gray-600">Import data jadwal</p>
                </div>
            </div>
            
            <form action="{{ route('admin.import.jadwal') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <input type="file" name="file" accept=".csv" required
                        class="w-full text-sm border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-red-500">
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition text-sm font-semibold">
                        <i class="fas fa-upload mr-1"></i> Import
                    </button>
                    <a href="{{ route('admin.template.jadwal') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center text-sm font-semibold">
                        <i class="fas fa-download mr-1"></i> Template
                    </a>
                </div>
            </form>
            
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-xs text-gray-600 font-semibold mb-1">Format CSV:</p>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded block overflow-x-auto">jenis_semester, kode_mk, nidn_dosen, kode_ruangan, hari, jam_mulai, jam_selesai, kelas</code>
            </div>
        </div>

    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 mb-2">Petunjuk Import CSV:</h3>
                <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                    <li>Download template terlebih dahulu untuk memastikan format yang benar</li>
                    <li>Isi data sesuai dengan kolom yang tersedia</li>
                    <li>File harus dalam format CSV (Comma Separated Values)</li>
                    <li>Maksimal ukuran file: 2MB</li>
                    <li>Pastikan encoding file adalah UTF-8</li>
                    <li>Import dilakukan per tabel (tidak bisa sekaligus)</li>
                    <li>Urutan import yang disarankan: Program Studi → Kurikulum → Mata Kuliah → Ruangan → Semester → Jadwal</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
