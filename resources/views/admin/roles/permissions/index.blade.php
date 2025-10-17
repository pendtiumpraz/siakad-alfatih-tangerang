@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Pengaturan Role & Permission" />

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-[#F4E5C3] to-[#D4AF37] rounded-lg shadow-md p-4 border-2 border-[#2D5F3F]">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-[#2D5F3F] text-2xl mr-3"></i>
            <p class="text-[#2D5F3F] text-sm">
                Kelola hak akses setiap role pada sistem. Pastikan setiap role memiliki permission yang sesuai dengan tugasnya.
            </p>
        </div>
    </div>

    <!-- Permission Matrix -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-table mr-2"></i>
                Matrix Permission
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#F4E5C3] text-[#2D5F3F]">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider w-1/5">Module</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Super Admin</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Operator</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Dosen</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Mahasiswa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Users Module -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-[#2D5F3F]">
                            <i class="fas fa-users mr-2"></i>
                            Users
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Mahasiswa Module -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-[#2D5F3F]">
                            <i class="fas fa-user-graduate mr-2"></i>
                            Mahasiswa
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Dosen Module -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-[#2D5F3F]">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            Dosen
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Mata Kuliah Module -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-[#2D5F3F]">
                            <i class="fas fa-book-open mr-2"></i>
                            Mata Kuliah
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Nilai Module -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-[#2D5F3F]">
                            <i class="fas fa-star mr-2"></i>
                            Nilai
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Pembayaran Module -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-[#2D5F3F]">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Pembayaran
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 justify-center">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">View</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Create</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Edit</span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-400 text-xs rounded">Delete</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Role Management Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Super Admin -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#2D5F3F] to-[#4A7C59] rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-[#2D5F3F]">Super Admin</h3>
                        <p class="text-xs text-gray-500">Full Access</p>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Memiliki akses penuh ke seluruh sistem</p>
            <a href="{{ route('admin.roles.permissions.edit', 'super_admin') }}" class="block text-center px-4 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white rounded-lg hover:shadow-lg transition text-sm">
                <i class="fas fa-edit mr-2"></i>
                Edit Permission
            </a>
        </div>

        <!-- Operator -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-[#2D5F3F]">Operator</h3>
                        <p class="text-xs text-gray-500">Limited Access</p>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Akses operasional sistem akademik</p>
            <a href="{{ route('admin.roles.permissions.edit', 'operator') }}" class="block text-center px-4 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] text-[#2D5F3F] rounded-lg hover:shadow-lg transition text-sm">
                <i class="fas fa-edit mr-2"></i>
                Edit Permission
            </a>
        </div>

        <!-- Dosen -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#6B9E78] to-[#4A7C59] rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-[#2D5F3F]">Dosen</h3>
                        <p class="text-xs text-gray-500">Teaching Access</p>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Akses untuk pengelolaan pembelajaran</p>
            <a href="{{ route('admin.roles.permissions.edit', 'dosen') }}" class="block text-center px-4 py-2 bg-gradient-to-r from-[#6B9E78] to-[#4A7C59] text-white rounded-lg hover:shadow-lg transition text-sm">
                <i class="fas fa-edit mr-2"></i>
                Edit Permission
            </a>
        </div>

        <!-- Mahasiswa -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-[#2D5F3F]">Mahasiswa</h3>
                        <p class="text-xs text-gray-500">Student Access</p>
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Akses untuk mahasiswa aktif</p>
            <a href="{{ route('admin.roles.permissions.edit', 'mahasiswa') }}" class="block text-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg hover:shadow-lg transition text-sm">
                <i class="fas fa-edit mr-2"></i>
                Edit Permission
            </a>
        </div>
    </div>
</div>
@endsection
