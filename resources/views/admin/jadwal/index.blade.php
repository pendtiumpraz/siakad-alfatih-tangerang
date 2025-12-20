@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Jadwal Perkuliahan</h1>
            <p class="text-gray-600 mt-1">Klik pada cell untuk edit langsung</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.jadwal.calendar') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Calendar View</span>
            </a>
            <a href="{{ route('admin.jadwal.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Jadwal</span>
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mata Kuliah</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode/Nama MK..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Jenis Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Semester</label>
                    <select name="jenis_semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Semester</option>
                        <option value="ganjil" {{ request('jenis_semester') == 'ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                        <option value="genap" {{ request('jenis_semester') == 'genap' ? 'selected' : '' }}>Semester Genap</option>
                    </select>
                </div>

                <!-- Hari -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                    <select name="hari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Hari</option>
                        <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ request('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Ahad" {{ request('hari') == 'Ahad' ? 'selected' : '' }}>Ahad</option>
                    </select>
                </div>

                <!-- Dosen -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dosen</label>
                    <select name="dosen_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Dosen</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ request('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mata Kuliah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                    <select name="mata_kuliah_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Mata Kuliah</option>
                        @foreach($mataKuliahs as $mk)
                            <option value="{{ $mk->id }}" {{ request('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ruangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                    <select name="ruangan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ request('ruangan_id') == $ruangan->id ? 'selected' : '' }}>{{ $ruangan->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="flex items-end space-x-2 lg:col-span-2">
                    <button type="submit" class="flex-1 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-filter mr-2"></i>Terapkan
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </x-islamic-card>

    <!-- Batch Delete Actions -->
    @include('components.batch-delete-actions', ['routeName' => route('admin.jadwal.batch-delete')])

    <!-- Jadwal Table (EDITABLE) -->
    <x-islamic-card title="Daftar Jadwal (Klik untuk Edit)">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-green-200 border border-green-200 rounded-lg" id="jadwalTable">
                <thead class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase w-16">No</th>
                        <x-sortable-header column="jenis_semester" label="Jenis Semester" :currentSort="$sortColumn ?? 'updated_at'" :currentDirection="$sortDirection ?? 'desc'" />
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Dosen</th>
                        <x-sortable-header column="hari" label="Hari" :currentSort="$sortColumn ?? 'updated_at'" :currentDirection="$sortDirection ?? 'desc'" />
                        <x-sortable-header column="jam_mulai" label="Jam Mulai" :currentSort="$sortColumn ?? 'updated_at'" :currentDirection="$sortDirection ?? 'desc'" />
                        <x-sortable-header column="jam_selesai" label="Jam Selesai" :currentSort="$sortColumn ?? 'updated_at'" :currentDirection="$sortDirection ?? 'desc'" />
                        <x-sortable-header column="kelas" label="Kelas" :currentSort="$sortColumn ?? 'updated_at'" :currentDirection="$sortDirection ?? 'desc'" />
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Ruangan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase w-24">Hapus</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwals as $index => $jadwal)
                    <tr class="hover:bg-green-50 transition-colors" data-id="{{ $jadwal->id }}">
                        <td class="px-4 py-3">
                            <input type="checkbox" class="row-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" value="{{ $jadwal->id }}" onchange="updateSelectedIds()">
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 text-center">{{ $jadwals->firstItem() + $index }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $jadwal->jenis_semester == 'ganjil' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ ucfirst($jadwal->jenis_semester) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $jadwal->mataKuliah->kode_mk }} - {{ $jadwal->mataKuliah->nama_mk }}</td>
                        
                        <!-- EDITABLE: Dosen -->
                        <td class="px-4 py-3 text-sm editable-cell cursor-pointer hover:bg-yellow-50" 
                            data-field="dosen_id" 
                            data-value="{{ $jadwal->dosen_id }}"
                            data-type="dropdown"
                            title="Klik untuk edit">
                            <span class="display-value">{{ $jadwal->dosen->nama_lengkap ?? '-' }}</span>
                            <select class="edit-input hidden w-full px-2 py-1 border border-green-500 rounded focus:ring-2 focus:ring-green-500">
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ $jadwal->dosen_id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama_lengkap }} (NIDN: {{ $dosen->nidn }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        
                        <!-- EDITABLE: Hari -->
                        <td class="px-4 py-3 text-sm editable-cell cursor-pointer hover:bg-yellow-50" 
                            data-field="hari" 
                            data-value="{{ $jadwal->hari }}"
                            data-type="dropdown"
                            title="Klik untuk edit">
                            <span class="display-value">{{ $jadwal->hari }}</span>
                            <select class="edit-input hidden w-full px-2 py-1 border border-green-500 rounded focus:ring-2 focus:ring-green-500">
                                <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                <option value="Sabtu" {{ $jadwal->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                <option value="Ahad" {{ $jadwal->hari == 'Ahad' ? 'selected' : '' }}>Ahad</option>
                            </select>
                        </td>
                        
                        <!-- EDITABLE: Jam Mulai -->
                        <td class="px-4 py-3 text-sm editable-cell cursor-pointer hover:bg-yellow-50" 
                            data-field="jam_mulai" 
                            data-value="{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}"
                            data-type="text"
                            title="Klik untuk edit (Format: HH:MM)">
                            <span class="display-value">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</span>
                            <input type="text" class="edit-input hidden w-20 px-2 py-1 border border-green-500 rounded focus:ring-2 focus:ring-green-500" 
                                   placeholder="HH:MM" 
                                   value="{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}">
                        </td>
                        
                        <!-- EDITABLE: Jam Selesai -->
                        <td class="px-4 py-3 text-sm editable-cell cursor-pointer hover:bg-yellow-50" 
                            data-field="jam_selesai" 
                            data-value="{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}"
                            data-type="text"
                            title="Klik untuk edit (Format: HH:MM)">
                            <span class="display-value">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                            <input type="text" class="edit-input hidden w-20 px-2 py-1 border border-green-500 rounded focus:ring-2 focus:ring-green-500" 
                                   placeholder="HH:MM"
                                   value="{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}">
                        </td>
                        
                        <!-- EDITABLE: Kelas -->
                        <td class="px-4 py-3 text-sm editable-cell cursor-pointer hover:bg-yellow-50" 
                            data-field="kelas" 
                            data-value="{{ $jadwal->kelas }}"
                            data-type="text"
                            title="Klik untuk edit">
                            <span class="display-value">{{ $jadwal->kelas }}</span>
                            <input type="text" class="edit-input hidden w-16 px-2 py-1 border border-green-500 rounded focus:ring-2 focus:ring-green-500" 
                                   value="{{ $jadwal->kelas }}">
                        </td>
                        
                        <!-- EDITABLE: Ruangan -->
                        <td class="px-4 py-3 text-sm editable-cell cursor-pointer hover:bg-yellow-50" 
                            data-field="ruangan_id" 
                            data-value="{{ $jadwal->ruangan_id }}"
                            data-type="dropdown"
                            title="Klik untuk edit">
                            <span class="display-value">{{ $jadwal->ruangan->nama_ruangan }}</span>
                            <select class="edit-input hidden w-full px-2 py-1 border border-green-500 rounded focus:ring-2 focus:ring-green-500">
                                @foreach($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id }}" {{ $jadwal->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                        {{ $ruangan->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">Belum ada jadwal</p>
                                <p class="text-sm">Klik tombol "Tambah Jadwal" untuk menambahkan jadwal baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jadwals->hasPages())
        <div class="mt-6">
            {{ $jadwals->links() }}
        </div>
        @endif
    </x-islamic-card>
</div>

<!-- AJAX Auto-Save Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editableCells = document.querySelectorAll('.editable-cell');
    
    editableCells.forEach(cell => {
        // Click to edit
        cell.addEventListener('click', function() {
            if (this.classList.contains('editing')) return; // Already editing
            
            const displayValue = this.querySelector('.display-value');
            const editInput = this.querySelector('.edit-input');
            const type = this.dataset.type;
            
            // Hide display, show input
            displayValue.classList.add('hidden');
            editInput.classList.remove('hidden');
            editInput.focus();
            
            if (type === 'text') {
                editInput.select();
            }
            
            this.classList.add('editing', 'bg-yellow-100');
        });
    });
    
    // Handle blur/change events for saving
    document.querySelectorAll('.edit-input').forEach(input => {
        const saveValue = function() {
            const cell = input.closest('.editable-cell');
            if (!cell.classList.contains('editing')) return;
            
            const jadwalId = cell.closest('tr').dataset.id;
            const field = cell.dataset.field;
            const newValue = input.value;
            const oldValue = cell.dataset.value;
            
            // No change, just hide
            if (newValue == oldValue) {
                cancelEdit(cell);
                return;
            }
            
            // Save via AJAX
            saveField(jadwalId, field, newValue, cell);
        };
        
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', saveValue);
        } else {
            input.addEventListener('blur', saveValue);
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveValue();
                }
            });
        }
    });
    
    function cancelEdit(cell) {
        const displayValue = cell.querySelector('.display-value');
        const editInput = cell.querySelector('.edit-input');
        
        displayValue.classList.remove('hidden');
        editInput.classList.add('hidden');
        cell.classList.remove('editing', 'bg-yellow-100');
    }
    
    function saveField(jadwalId, field, value, cell) {
        // Show loading
        cell.classList.add('opacity-50');
        
        fetch(`/admin/jadwal/${jadwalId}/update-field`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                field: field,
                value: value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update display value
                const displayValue = cell.querySelector('.display-value');
                displayValue.textContent = data.display_value;
                
                // Update data-value
                cell.dataset.value = value;
                
                // Hide input, show display
                cancelEdit(cell);
                
                // Show success indicator
                cell.classList.remove('opacity-50');
                cell.classList.add('bg-green-100');
                setTimeout(() => {
                    cell.classList.remove('bg-green-100');
                }, 1000);
                
                // Show toast notification
                showToast('Berhasil diupdate!', 'success');
            } else {
                alert('Error: ' + data.message);
                cell.classList.remove('opacity-50');
                cancelEdit(cell);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan.');
            cell.classList.remove('opacity-50');
            cancelEdit(cell);
        });
    }
    
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>

<style>
.editable-cell {
    position: relative;
}

.editable-cell:hover::after {
    content: '✎';
    position: absolute;
    right: 4px;
    top: 50%;
    transform: translateY(-50%);
    color: #059669;
    font-size: 14px;
}

.editable-cell.editing:hover::after {
    display: none;
}
</style>
@endsection
