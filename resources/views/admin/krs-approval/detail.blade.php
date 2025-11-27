@extends('layouts.admin')

@section('title', 'Approval KRS - ' . $prodi->nama_prodi)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-gray-600 mb-2">
            <a href="{{ route('admin.krs-approval.index', ['semester_id' => $semester->id]) }}" class="hover:text-blue-600">
                Approval KRS
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-semibold">{{ $prodi->nama_prodi }}</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">{{ $prodi->nama_prodi }}</h1>
        <p class="text-gray-600">{{ $semester->nama_semester }} - {{ $semester->tahun_akademik }}</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg relative mb-6" role="alert">
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg relative mb-6" role="alert">
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-gray-500">
            <div class="text-sm text-gray-600 mb-1">Total Mahasiswa</div>
            <div class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-orange-500">
            <div class="text-sm text-orange-600 mb-1">Pending Approval</div>
            <div class="text-2xl font-bold text-orange-800">{{ $summary['submitted'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="text-sm text-green-600 mb-1">Sudah Approved</div>
            <div class="text-2xl font-bold text-green-800">{{ $summary['approved'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
            <div class="text-sm text-red-600 mb-1">Rejected</div>
            <div class="text-2xl font-bold text-red-800">{{ $summary['rejected'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.krs-approval.detail', $prodi->id) }}" class="space-y-4">
            <input type="hidden" name="semester_id" value="{{ $semester->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- KRS Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status KRS:</label>
                    <select name="krs_status" onchange="this.form.submit()" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ request('krs_status') == 'submitted' ? 'selected' : '' }}>⏳ Submitted (Pending)</option>
                        <option value="approved" {{ request('krs_status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                        <option value="rejected" {{ request('krs_status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                        <option value="not_submitted" {{ request('krs_status') == 'not_submitted' ? 'selected' : '' }}>📝 Belum Submit</option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status SPP:</label>
                    <select name="payment_status" onchange="this.form.submit()" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>✅ Sudah Bayar</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>❌ Belum Bayar</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Mahasiswa:</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="NIM atau Nama..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 pr-10">
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            @if(request()->hasAny(['krs_status', 'payment_status', 'search']))
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Filter aktif:</span>
                    <a href="{{ route('admin.krs-approval.detail', ['prodiId' => $prodi->id, 'semester_id' => $semester->id]) }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                        Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Mass Approve Selected Form -->
    <form id="massApproveForm" action="{{ route('admin.krs-approval.mass-approve-selected') }}" method="POST">
        @csrf
        <input type="hidden" name="semester_id" value="{{ $semester->id }}">
        
        <!-- Action Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="selectAll" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Pilih Semua</span>
                    </label>
                    <span class="text-sm text-gray-600" id="selectedCount">0 dipilih</span>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" 
                            id="approveSelectedBtn"
                            disabled
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition font-semibold flex items-center gap-2"
                            onclick="return confirm('Approve KRS yang dipilih?\n\nHanya mahasiswa yang sudah bayar SPP yang akan di-approve.')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                <!-- Checkbox column -->
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIM
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Mahasiswa
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Semester
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total SKS
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status KRS
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status SPP
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mahasiswas as $mhs)
                            <tr class="hover:bg-gray-50 {{ !$mhs->can_approve ? 'bg-gray-50' : '' }}">
                                <!-- Checkbox -->
                                <td class="px-6 py-4 text-center">
                                    @if($mhs->krs_status === 'submitted')
                                        <input type="checkbox" 
                                               name="mahasiswa_ids[]" 
                                               value="{{ $mhs->id }}" 
                                               class="mahasiswa-checkbox w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                                               data-can-approve="{{ $mhs->can_approve ? 'true' : 'false' }}">
                                    @endif
                                </td>

                                <!-- NIM -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $mhs->nim }}</div>
                                </td>

                                <!-- Nama -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $mhs->nama_lengkap }}</div>
                                </td>

                                <!-- Semester -->
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-semibold text-gray-700">{{ $mhs->semester_aktif ?? '-' }}</span>
                                </td>

                                <!-- Total SKS -->
                                <td class="px-6 py-4 text-center">
                                    @if($mhs->krs_count > 0)
                                        <span class="text-sm font-bold text-blue-600">{{ $mhs->total_sks }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status KRS -->
                                <td class="px-6 py-4 text-center">
                                    @if($mhs->krs_status === 'submitted')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                            ⏳ Pending
                                        </span>
                                    @elseif($mhs->krs_status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            ✅ Approved
                                        </span>
                                    @elseif($mhs->krs_status === 'rejected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            ❌ Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                            📝 Belum Submit
                                        </span>
                                    @endif
                                </td>

                                <!-- Status SPP -->
                                <td class="px-6 py-4 text-center">
                                    @if($mhs->spp_paid)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            ✅ Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            ❌ Belum Bayar
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('admin.krs-approval.show', ['mahasiswaId' => $mhs->id, 'semester_id' => $semester->id]) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-semibold text-sm inline-flex items-center gap-1"
                                       title="Lihat Detail KRS">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Tidak Ada Data</h3>
                                    <p class="text-gray-500">Tidak ada mahasiswa yang sesuai dengan filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($mahasiswas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $mahasiswas->links() }}
                </div>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const mahasiswaCheckboxes = document.querySelectorAll('.mahasiswa-checkbox');
    const approveBtn = document.getElementById('approveSelectedBtn');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.mahasiswa-checkbox:checked').length;
        selectedCountSpan.textContent = `${checkedCount} dipilih`;
        approveBtn.disabled = checkedCount === 0;
    }

    selectAllCheckbox.addEventListener('change', function() {
        mahasiswaCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    mahasiswaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            
            // Update select all checkbox
            const allChecked = Array.from(mahasiswaCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(mahasiswaCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        });
    });
});
</script>
@endpush
@endsection
