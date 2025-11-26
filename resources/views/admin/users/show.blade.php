@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <x-admin.page-header title="Detail User" />
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-[#D4AF37] text-white rounded-lg hover:bg-[#b8941f] transition">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] p-6 text-center">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        @php
                            $displayName = $user->username;
                            if ($user->role === 'mahasiswa' && $user->mahasiswa) {
                                $displayName = $user->mahasiswa->nama_lengkap ?? $user->username;
                            } elseif ($user->role === 'dosen' && $user->dosen) {
                                $displayName = $user->dosen->nama_lengkap ?? $user->username;
                            } elseif ($user->role === 'operator' && $user->operator) {
                                $displayName = $user->operator->nama_lengkap ?? $user->username;
                            }
                        @endphp
                        {{ substr($displayName, 0, 2) }}
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-white">{{ $displayName }}</h2>
                    <p class="text-emerald-50 text-sm">
                        <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                    </p>
                    <div class="mt-3">
                        <x-admin.badge :type="$user->role" :label="ucfirst($user->role)" />
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Status:</span>
                        <x-admin.badge
                            :type="$user->is_active ? 'active' : 'inactive'"
                            :label="$user->is_active ? 'Active' : 'Inactive'"
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Member Since:</span>
                        <span class="font-semibold text-[#2D5F3F]">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Last Login:</span>
                        <span class="font-semibold text-[#2D5F3F]">{{ $user->last_login ? $user->last_login->format('d M Y H:i') : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-semibold text-[#2D5F3F]">{{ $user->updated_at->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-gray-200 p-6">
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus User
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Dasar
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-gray-600">
                            <i class="fas fa-user mr-1 text-[#D4AF37]"></i>Nama Lengkap
                        </label>
                        <p class="font-semibold text-[#2D5F3F]">
                            @if($user->role === 'mahasiswa' && $user->mahasiswa)
                                {{ $user->mahasiswa->nama_lengkap }}
                            @elseif($user->role === 'dosen' && $user->dosen)
                                {{ $user->dosen->nama_lengkap }}
                            @elseif($user->role === 'operator' && $user->operator)
                                {{ $user->operator->nama_lengkap }}
                            @else
                                {{ $user->username }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">
                            <i class="fas fa-id-card mr-1 text-[#D4AF37]"></i>
                            @if($user->role === 'mahasiswa')
                                NIM
                            @elseif($user->role === 'dosen')
                                NIP
                            @elseif($user->role === 'operator')
                                Employee ID
                            @else
                                ID
                            @endif
                        </label>
                        <p class="font-semibold text-[#2D5F3F]">
                            @if($user->role === 'mahasiswa' && $user->mahasiswa)
                                {{ $user->mahasiswa->nim }}
                            @elseif($user->role === 'dosen' && $user->dosen)
                                {{ $user->dosen->nidn }}
                            @elseif($user->role === 'operator' && $user->operator)
                                {{ $user->operator->employee_id }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">
                            <i class="fas fa-user-circle mr-1 text-[#D4AF37]"></i>Username
                        </label>
                        <p class="font-semibold text-[#2D5F3F]">{{ $user->username }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">
                            <i class="fas fa-envelope mr-1 text-[#D4AF37]"></i>Email
                        </label>
                        <p class="font-semibold text-[#2D5F3F]">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-1 text-[#D4AF37]"></i>Nomor Telepon
                        </label>
                        <p class="font-semibold text-[#2D5F3F]">
                            @if($user->role === 'mahasiswa' && $user->mahasiswa)
                                {{ $user->mahasiswa->no_telepon ?? '-' }}
                            @elseif($user->role === 'dosen' && $user->dosen)
                                {{ $user->dosen->no_telepon ?? '-' }}
                            @elseif($user->role === 'operator' && $user->operator)
                                {{ $user->operator->no_telepon ?? '-' }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">
                            <i class="fas fa-user-tag mr-1 text-[#D4AF37]"></i>Role
                        </label>
                        <p class="font-semibold text-[#2D5F3F]">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">
                            <i class="fas fa-toggle-on mr-1 text-[#D4AF37]"></i>Status Akun
                        </label>
                        <p class="font-semibold {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Role-Specific Information -->
            @if($user->role === 'mahasiswa' && $user->mahasiswa)
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Informasi Mahasiswa
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-id-badge mr-1 text-[#D4AF37]"></i>NIM
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-user mr-1 text-[#D4AF37]"></i>Nama Lengkap
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->nama_lengkap }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-graduation-cap mr-1 text-[#D4AF37]"></i>Program Studi
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->programStudi->nama_prodi ?? '-' }} @if($user->mahasiswa->programStudi)({{ $user->mahasiswa->programStudi->jenjang ?? '' }})@endif</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-calendar mr-1 text-[#D4AF37]"></i>Angkatan
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->angkatan }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-layer-group mr-1 text-[#D4AF37]"></i>Semester Aktif
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->semester_aktif ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-1 text-[#D4AF37]"></i>Tempat Lahir
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->tempat_lahir ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-birthday-cake mr-1 text-[#D4AF37]"></i>Tanggal Lahir
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($user->mahasiswa->tanggal_lahir)->format('d F Y') : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-venus-mars mr-1 text-[#D4AF37]"></i>Jenis Kelamin
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($user->mahasiswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-phone mr-1 text-[#D4AF37]"></i>Nomor Telepon
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->no_telepon ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-map-marked-alt mr-1 text-[#D4AF37]"></i>Alamat
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->alamat ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-check-circle mr-1 text-[#D4AF37]"></i>Status Akademik
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ ucfirst($user->mahasiswa->status ?? 'Aktif') }}</p>
                        </div>

                        @if($user->mahasiswa->status === 'lulus')
                            <div>
                                <label class="text-sm text-gray-600">
                                    <i class="fas fa-calendar-check mr-1 text-[#D4AF37]"></i>Tanggal Lulus
                                </label>
                                <p class="font-semibold text-green-600">{{ $user->mahasiswa->tanggal_lulus ? $user->mahasiswa->tanggal_lulus->format('d F Y') : '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">
                                    <i class="fas fa-layer-group mr-1 text-[#D4AF37]"></i>Lulus Semester
                                </label>
                                <p class="font-semibold text-green-600">{{ $user->mahasiswa->semester_terakhir ?? '-' }}</p>
                            </div>
                        @endif

                        @if($user->mahasiswa->status === 'dropout')
                            <div>
                                <label class="text-sm text-gray-600">
                                    <i class="fas fa-calendar-times mr-1 text-[#D4AF37]"></i>Tanggal Dropout
                                </label>
                                <p class="font-semibold text-red-600">{{ $user->mahasiswa->tanggal_dropout ? $user->mahasiswa->tanggal_dropout->format('d F Y') : '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">
                                    <i class="fas fa-layer-group mr-1 text-[#D4AF37]"></i>Dropout di Semester
                                </label>
                                <p class="font-semibold text-red-600">{{ $user->mahasiswa->semester_terakhir ?? '-' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($user->role === 'dosen' && $user->dosen)
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Informasi Dosen
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-id-badge mr-1 text-[#D4AF37]"></i>NIP
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->nidn }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-user mr-1 text-[#D4AF37]"></i>Nama Lengkap
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->nama_lengkap }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-award mr-1 text-[#D4AF37]"></i>Gelar Depan
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->gelar_depan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-award mr-1 text-[#D4AF37]"></i>Gelar Belakang
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->gelar_belakang ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-graduation-cap mr-1 text-[#D4AF37]"></i>Program Studi
                            </label>
                            @php
                                try {
                                    $programStudis = $user->dosen->relationLoaded('programStudis') 
                                        ? $user->dosen->programStudis 
                                        : collect();
                                } catch (\Throwable $e) {
                                    $programStudis = collect();
                                }
                            @endphp
                            @if($programStudis->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($programStudis as $prodi)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="font-semibold text-red-600 mt-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Belum di-assign ke program studi
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Dosen harus di-assign minimal 1 program studi untuk dapat mengakses sistem.</p>
                            @endif
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-envelope mr-1 text-[#D4AF37]"></i>Email Dosen
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->email_dosen ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-phone mr-1 text-[#D4AF37]"></i>Nomor Telepon
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->no_telepon ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($user->role === 'operator' && $user->operator)
                <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-user-tie mr-2"></i>
                        Informasi Operator
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-id-badge mr-1 text-[#D4AF37]"></i>Employee ID
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->operator->employee_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-user mr-1 text-[#D4AF37]"></i>Nama Lengkap
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->operator->nama_lengkap }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-building mr-1 text-[#D4AF37]"></i>Departemen
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->operator->department ?? 'Akademik' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">
                                <i class="fas fa-phone mr-1 text-[#D4AF37]"></i>Nomor Telepon
                            </label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->operator->no_telepon ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Activity Log -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                    <i class="fas fa-history mr-2"></i>
                    Aktivitas Terakhir
                </h3>
                <div class="space-y-4">
                    @forelse($user->activities ?? [] as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 bg-[#D4AF37] rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Belum ada aktivitas tercatat</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
