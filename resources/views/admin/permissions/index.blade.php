@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold" style="color: #2D5F3F;">Pengaturan Role & Permission</h2>
        <p class="text-gray-600 mt-2">Kelola hak akses untuk setiap role</p>
        <div class="gold-divider mt-4"></div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Permission Matrix Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200" style="background: linear-gradient(135deg, #4A7C59 0%, #2D5F3F 100%);">
            <h3 class="text-xl font-semibold text-white">Permission Matrix</h3>
            <p class="text-white/80 text-sm mt-1">Centang untuk memberikan akses</p>
        </div>

        <form method="POST" action="{{ route('admin.permissions.update') }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                Module
                            </th>
                            @foreach($roles as $role)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="4">
                                    <div class="font-bold mb-1">{{ ucfirst(str_replace('_', ' ', $role)) }}</div>
                                    <div class="flex justify-around text-xs">
                                        <span>View</span>
                                        <span>Create</span>
                                        <span>Edit</span>
                                        <span>Delete</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($modules as $module)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white">
                                    {{ ucfirst(str_replace('_', ' ', $module)) }}
                                </td>
                                @foreach($roles as $role)
                                    @php
                                        $perms = $permissionMatrix[$role][$module];
                                    @endphp
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox"
                                               name="permissions[{{ $loop->parent->index * count($roles) + $loop->index }}][can_view]"
                                               value="1"
                                               {{ $perms['can_view'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                        <input type="hidden" name="permissions[{{ $loop->parent->index * count($roles) + $loop->index }}][role]" value="{{ $role }}">
                                        <input type="hidden" name="permissions[{{ $loop->parent->index * count($roles) + $loop->index }}][module]" value="{{ $module }}">
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox"
                                               name="permissions[{{ $loop->parent->index * count($roles) + $loop->index }}][can_create]"
                                               value="1"
                                               {{ $perms['can_create'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox"
                                               name="permissions[{{ $loop->parent->index * count($roles) + $loop->index }}][can_edit]"
                                               value="1"
                                               {{ $perms['can_edit'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox"
                                               name="permissions[{{ $loop->parent->index * count($roles) + $loop->index }}][can_delete]"
                                               value="1"
                                               {{ $perms['can_delete'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <button type="submit" class="px-6 py-2 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-300" style="background-color: #4A7C59; hover:background-color: #2D5F3F;">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Catatan:</strong> Perubahan permission akan berlaku setelah user logout dan login kembali.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.gold-divider {
    height: 2px;
    background: linear-gradient(to right, #D4AF37 0%, #F4E5C3 50%, #D4AF37 100%);
    width: 100px;
}
</style>
@endsection
