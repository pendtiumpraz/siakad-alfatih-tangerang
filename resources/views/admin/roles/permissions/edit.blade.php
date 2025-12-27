@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <x-admin.page-header :title="'Edit Permission - ' . ucfirst($role)" />
        <a href="{{ route('admin.roles.permissions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-[#F4E5C3] to-[#D4AF37] rounded-lg shadow-md p-4 border-2 border-[#2D5F3F]">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-[#2D5F3F] text-2xl mr-3"></i>
            <p class="text-[#2D5F3F] text-sm">
                Pilih permission yang diizinkan untuk role <strong>{{ ucfirst($role) }}</strong>. Perubahan akan berlaku langsung setelah disimpan.
            </p>
        </div>
    </div>

    <!-- Permission Form -->
    <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Users Module -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                <i class="fas fa-users mr-2"></i>
                Users Management
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="users.view"
                        {{ in_array('users.view', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">View</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="users.create"
                        {{ in_array('users.create', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Create</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="users.edit"
                        {{ in_array('users.edit', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Edit</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="users.delete"
                        {{ in_array('users.delete', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Delete</span>
                </label>
            </div>
        </div>

        <!-- Mahasiswa Module -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                <i class="fas fa-user-graduate mr-2"></i>
                Mahasiswa Management
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mahasiswa.view"
                        {{ in_array('mahasiswa.view', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">View</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mahasiswa.create"
                        {{ in_array('mahasiswa.create', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Create</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mahasiswa.edit"
                        {{ in_array('mahasiswa.edit', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Edit</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mahasiswa.delete"
                        {{ in_array('mahasiswa.delete', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Delete</span>
                </label>
            </div>
        </div>

        <!-- Dosen Module -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                <i class="fas fa-chalkboard-teacher mr-2"></i>
                Dosen Management
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="dosen.view"
                        {{ in_array('dosen.view', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">View</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="dosen.create"
                        {{ in_array('dosen.create', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Create</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="dosen.edit"
                        {{ in_array('dosen.edit', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Edit</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="dosen.delete"
                        {{ in_array('dosen.delete', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Delete</span>
                </label>
            </div>
        </div>

        <!-- Mata Kuliah Module -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                <i class="fas fa-book-open mr-2"></i>
                Mata Kuliah Management
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mata_kuliah.view"
                        {{ in_array('mata_kuliah.view', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">View</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mata_kuliah.create"
                        {{ in_array('mata_kuliah.create', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Create</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mata_kuliah.edit"
                        {{ in_array('mata_kuliah.edit', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Edit</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="mata_kuliah.delete"
                        {{ in_array('mata_kuliah.delete', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Delete</span>
                </label>
            </div>
        </div>

        <!-- Nilai Module -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                <i class="fas fa-star mr-2"></i>
                Nilai Management
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="nilai.view"
                        {{ in_array('nilai.view', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">View</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="nilai.create"
                        {{ in_array('nilai.create', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Create</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="nilai.edit"
                        {{ in_array('nilai.edit', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Edit</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="nilai.delete"
                        {{ in_array('nilai.delete', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Delete</span>
                </label>
            </div>
        </div>

        <!-- Pembayaran Module -->
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                <i class="fas fa-money-bill-wave mr-2"></i>
                Pembayaran Management
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="pembayaran.view"
                        {{ in_array('pembayaran.view', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">View</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="pembayaran.create"
                        {{ in_array('pembayaran.create', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Create</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="pembayaran.edit"
                        {{ in_array('pembayaran.edit', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Edit</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="pembayaran.delete"
                        {{ in_array('pembayaran.delete', $permissions ?? []) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#2D5F3F] bg-gray-100 border-gray-300 rounded focus:ring-[#D4AF37] focus:ring-2"
                    >
                    <span class="text-sm text-gray-700">Delete</span>
                </label>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
            <button
                type="button"
                onclick="Swal.fire({
                    icon: 'warning',
                    title: 'Reset Permission?',
                    text: 'Apakah Anda yakin ingin reset permission ke default?',
                    showCancelButton: true,
                    confirmButtonColor: '#D4AF37',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ icon: 'info', title: 'Info', text: 'Fitur reset akan segera ditambahkan', confirmButtonColor: '#1B4D3E' });
                    }
                });"
                class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
            >
                <i class="fas fa-undo mr-2"></i>
                Reset ke Default
            </button>
            <div class="flex space-x-4">
                <a href="{{ route('admin.roles.permissions.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Permission
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
