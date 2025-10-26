@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Manajemen User" />

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari username atau email..."
                        class="w-full pl-10 pr-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Role Filter -->
            <div class="w-full md:w-48">
                <select name="role" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                    <option value="">Semua Role</option>
                    <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                    <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>

            <!-- Alumni Filter -->
            <div class="w-full md:w-48 flex items-center">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="show_alumni" value="1" {{ request('show_alumni') ? 'checked' : '' }} class="rounded border-gray-300 text-[#2D5F3F] shadow-sm focus:ring-[#D4AF37]">
                    <span class="ml-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-graduation-cap mr-1 text-[#D4AF37]"></i>
                        Tampilkan Alumni
                    </span>
                </label>
            </div>

            <!-- Include Deleted Filter -->
            <div class="w-full md:w-48 flex items-center">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="include_deleted" value="1" {{ request('include_deleted') ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-trash-restore mr-1 text-red-500"></i>
                        Tampilkan Terhapus
                    </span>
                </label>
            </div>

            <!-- Filter Button -->
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] text-[#2D5F3F] font-semibold rounded-lg hover:shadow-lg transition">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>

            <!-- Add User Button -->
            <a href="{{ route('admin.users.create') }}" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah User
            </a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users ?? [] as $index => $user)
                        <tr class="hover:bg-[#F4E5C3] hover:bg-opacity-30 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-admin.badge :type="$user->role" :label="ucfirst($user->role)" />
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-[#D4AF37] hover:text-[#b8941f] transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($users) && $users->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data
                    </div>
                    <div class="flex space-x-1">
                        {{-- Previous Page Link --}}
                        @if ($users->onFirstPage())
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $current = $users->currentPage();
                            $last = $users->lastPage();
                            $delta = 2;
                            $left = $current - $delta;
                            $right = $current + $delta + 1;
                            $range = [];
                            $rangeWithDots = [];
                            $l = null;

                            for ($i = 1; $i <= $last; $i++) {
                                if ($i == 1 || $i == $last || ($i >= $left && $i < $right)) {
                                    $range[] = $i;
                                }
                            }

                            foreach ($range as $i) {
                                if ($l) {
                                    if ($i - $l === 2) {
                                        $rangeWithDots[] = $l + 1;
                                    } else if ($i - $l !== 1) {
                                        $rangeWithDots[] = '...';
                                    }
                                }
                                $rangeWithDots[] = $i;
                                $l = $i;
                            }
                        @endphp

                        @foreach ($rangeWithDots as $page)
                            @if ($page === '...')
                                <span class="px-4 py-2 bg-white border border-gray-300 text-gray-400 rounded cursor-default">...</span>
                            @elseif ($page == $current)
                                <span class="px-4 py-2 bg-[#2D5F3F] text-white rounded font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $users->url($page) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded hover:bg-[#D4AF37] hover:text-white transition">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
