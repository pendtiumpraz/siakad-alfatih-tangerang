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
                        {{ substr($user->username, 0, 2) }}
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-white">{{ $user->username }}</h2>
                    <p class="text-emerald-50 text-sm">{{ $user->email }}</p>
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
                        <label class="text-sm text-gray-600">Username</label>
                        <p class="font-semibold text-[#2D5F3F]">{{ $user->username }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <p class="font-semibold text-[#2D5F3F]">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Role</label>
                        <p class="font-semibold text-[#2D5F3F]">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Status Akun</label>
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
                            <label class="text-sm text-gray-600">NIM</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Program Studi</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->programStudi->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Angkatan</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->angkatan }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Status Akademik</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->mahasiswa->status_akademik ?? 'Aktif' }}</p>
                        </div>
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
                            <label class="text-sm text-gray-600">NIDN</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->nidn }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Gelar</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->gelar ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Status Dosen</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->dosen->status ?? 'Aktif' }}</p>
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
                            <label class="text-sm text-gray-600">Employee ID</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->operator->employee_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Departemen</label>
                            <p class="font-semibold text-[#2D5F3F]">{{ $user->operator->department ?? 'Akademik' }}</p>
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
