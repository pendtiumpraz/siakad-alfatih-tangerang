@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Dashboard Admin" />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.stat-card
            icon="fas fa-users"
            number="{{ $totalUsers ?? 150 }}"
            label="Total Users"
            color="blue"
        />

        <x-admin.stat-card
            icon="fas fa-user-graduate"
            number="{{ $totalMahasiswa ?? 1250 }}"
            label="Total Mahasiswa"
            color="green"
        />

        <x-admin.stat-card
            icon="fas fa-chalkboard-teacher"
            number="{{ $totalDosen ?? 85 }}"
            label="Total Dosen"
            color="purple"
        />

        <x-admin.stat-card
            icon="fas fa-user-tie"
            number="{{ $totalOperator ?? 12 }}"
            label="Total Operator"
            color="gold"
        />
    </div>

    <!-- Islamic Divider -->
    <div class="islamic-divider"></div>

    <!-- Active Semester Card -->
    <div class="bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] rounded-lg shadow-md p-6 border-2 border-[#2D5F3F]">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-2">Semester Aktif</h3>
                <p class="text-2xl font-bold text-[#2D5F3F]">{{ $activeSemester?->nama ?? 'Belum ada semester aktif' }}</p>
                <p class="text-sm text-[#2D5F3F] mt-1">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Periode: {{ $activeSemester?->tanggal_mulai?->format('d M Y') ?? '-' }} - {{ $activeSemester?->tanggal_selesai?->format('d M Y') ?? '-' }}
                </p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-calendar-check text-6xl text-[#2D5F3F] opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Users Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
                <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        User Terbaru
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#F4E5C3] text-[#2D5F3F]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Last Login</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentUsers ?? [] as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $user->username }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <x-admin.badge :type="$user->role" :label="$user->role" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <x-admin.badge
                                            :type="$user->is_active ? 'active' : 'inactive'"
                                            :label="$user->is_active ? 'Active' : 'Inactive'"
                                        />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $user->last_login ? $user->last_login->format('d M Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                                        <p>Belum ada user terbaru</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    <a href="{{ route('admin.users.index') }}" class="text-[#2D5F3F] hover:text-[#D4AF37] text-sm font-semibold transition">
                        Lihat Semua User <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Statistics -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 flex items-center">
                    <i class="fas fa-bolt mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    {{-- Temporary hidden until user management implemented
                    <a href="{{ route('admin.users.create') }}" class="flex items-center justify-between p-3 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white rounded-lg hover:shadow-lg transition">
                        <span class="text-sm font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            Tambah User Baru
                        </span>
                        <i class="fas fa-chevron-right"></i>
                    </a>

                    <a href="{{ route('admin.roles.permissions.index') }}" class="flex items-center justify-between p-3 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] text-[#2D5F3F] rounded-lg hover:shadow-lg transition">
                        <span class="text-sm font-medium">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Kelola Permission
                        </span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    --}}

                    <a href="#" class="flex items-center justify-between p-3 bg-gradient-to-r from-[#6B9E78] to-[#4A7C59] text-white rounded-lg hover:shadow-lg transition">
                        <span class="text-sm font-medium">
                            <i class="fas fa-file-alt mr-2"></i>
                            Lihat Laporan
                        </span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Sistem
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Versi Aplikasi:</span>
                        <span class="font-semibold text-[#2D5F3F]">1.0.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Login Hari Ini:</span>
                        <span class="font-semibold text-[#2D5F3F]">{{ $todayLogins ?? 45 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">User Online:</span>
                        <span class="font-semibold text-green-600">{{ $onlineUsers ?? 12 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Backup:</span>
                        <span class="font-semibold text-[#2D5F3F]">{{ $lastBackup ?? 'Hari ini' }}</span>
                    </div>
                </div>
            </div>

            <!-- Islamic Quote -->
            <div class="bg-gradient-to-br from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 text-white text-center">
                <i class="fas fa-quote-right text-3xl mb-3 text-[#D4AF37]"></i>
                <p class="text-sm italic mb-2">"Barangsiapa menempuh suatu jalan untuk mencari ilmu, maka Allah akan memudahkan baginya jalan menuju surga"</p>
                <p class="text-xs text-emerald-50">- HR. Muslim -</p>
            </div>
        </div>
    </div>
</div>
@endsection
