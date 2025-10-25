@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Detail Program Studi" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('admin.program-studi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.program-studi.edit', $programStudi->id) }}" class="px-4 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#b8941f] transition">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
        </div>
    </div>

    <!-- Main Info Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden islamic-border">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-[#D4AF37] rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-graduation-cap text-3xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $programStudi->nama }}</h2>
                        <p class="text-emerald-50">{{ $programStudi->jenjang }} - {{ $programStudi->kode }}</p>
                    </div>
                </div>
                <div>
                    @if($programStudi->is_active)
                        <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>
                            Aktif
                        </span>
                    @else
                        <span class="px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-full">
                            <i class="fas fa-times-circle mr-1"></i>
                            Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Detail Information -->
                <div class="md:col-span-2 space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Program Studi
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Kode Program Studi</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $programStudi->kode }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenjang</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $programStudi->jenjang }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Nama Program Studi</p>
                            <p class="text-lg font-semibold text-[#2D5F3F]">{{ $programStudi->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-lg font-semibold {{ $programStudi->is_active ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $programStudi->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dibuat Pada</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $programStudi->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] border-b-2 border-[#D4AF37] pb-2">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Statistik
                    </h3>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600">Total Mahasiswa</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $programStudi->mahasiswa_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-graduate text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600">Total Kurikulum</p>
                                <p class="text-3xl font-bold text-green-700">{{ $programStudi->kurikulum_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-book text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#F4E5C3] to-[#D4AF37] rounded-lg p-4 border border-[#D4AF37]">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-[#2D5F3F]">Kelas Aktif</p>
                                <p class="text-3xl font-bold text-[#2D5F3F]">{{ $programStudi->kelas_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-[#2D5F3F] rounded-full flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Kurikulum -->
    @if(isset($kurikulums) && $kurikulums->count() > 0)
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-book mr-2"></i>
                Daftar Kurikulum
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($kurikulums as $kurikulum)
                <div class="border-2 border-[#D4AF37] rounded-lg p-4 hover:shadow-lg transition {{ $kurikulum->is_active ? 'bg-green-50' : 'bg-gray-50' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-[#2D5F3F] mb-2">{{ $kurikulum->nama }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $kurikulum->tahun_mulai }} - {{ $kurikulum->tahun_selesai }}
                                </p>
                                <p>
                                    <i class="fas fa-book-open mr-2"></i>
                                    Total SKS: {{ $kurikulum->total_sks }}
                                </p>
                            </div>
                        </div>
                        <div>
                            @if($kurikulum->is_active)
                                <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-gray-400 text-white text-xs rounded-full">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-[#D4AF37]">
                        <a href="{{ route('admin.kurikulum.show', $kurikulum->id) }}" class="text-[#D4AF37] hover:text-[#b8941f] text-sm font-semibold">
                            Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Islamic Quote -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 text-center">
        <i class="fas fa-quote-left text-[#D4AF37] text-2xl mb-2"></i>
        <p class="text-white text-lg italic mb-2">"Barang siapa menginginkan kebaikan di dunia maka dengan ilmu, barang siapa menginginkan kebaikan di akhirat maka dengan ilmu"</p>
        <i class="fas fa-quote-right text-[#D4AF37] text-2xl mt-2"></i>
    </div>
</div>
@endsection
