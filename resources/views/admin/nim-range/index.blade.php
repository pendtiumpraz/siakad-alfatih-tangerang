@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Manajemen NIM Range" />

    <!-- Filter & Search Section -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6">
        <form method="GET" action="{{ route('admin.nim-ranges.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Tahun Masuk Filter -->
            <div class="w-full md:w-48">
                <select name="tahun_masuk" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('tahun_masuk') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Program Studi Filter -->
            <div class="flex-1">
                <select name="program_studi_id" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition">
                    <option value="">Semua Program Studi</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ request('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] text-[#2D5F3F] font-semibold rounded-lg hover:shadow-lg transition">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>

            <!-- Reset Button -->
            <a href="{{ route('admin.nim-ranges.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                <i class="fas fa-redo mr-2"></i>
                Reset
            </a>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <div class="flex gap-2">
            <a href="{{ route('admin.nim-ranges.create') }}" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition text-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah NIM Range
            </a>

            <button
                x-data
                @click="$dispatch('open-bulk-modal')"
                class="px-6 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] text-[#2D5F3F] font-semibold rounded-lg hover:shadow-lg transition text-center"
            >
                <i class="fas fa-layer-group mr-2"></i>
                Bulk Create
            </button>
        </div>
    </div>

    <!-- Batch Delete Actions -->
    @include('components.batch-delete-actions', ['routeName' => route('admin.nim-ranges.batch-delete')])

    <!-- NIM Range Table by Year -->
    @forelse($nimRanges as $year => $ranges)
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
            <!-- Year Header -->
            <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
                <h2 class="text-xl font-semibold text-white">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Tahun Masuk {{ $year }}
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-[#D4AF37]">
                        <tr>
                            <th class="px-4 py-4 text-left">
                                <input type="checkbox" class="select-year-{{ $year }} w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" onchange="toggleSelectYear(this, {{ $year }})">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Program Studi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Prefix</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Format NIM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Penggunaan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Sisa Kuota</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($ranges as $index => $range)
                            <tr class="hover:bg-[#F4E5C3] hover:bg-opacity-30 transition">
                                <td class="px-4 py-4">
                                    <input type="checkbox" class="row-checkbox year-{{ $year }} w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" value="{{ $range->id }}" onchange="updateSelectedIds()" {{ $range->current_number > 0 ? 'disabled title="Tidak dapat dihapus karena sudah digunakan"' : '' }}>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <div>
                                        <div class="font-semibold text-[#2D5F3F]">{{ $range->programStudi->nama_prodi }}</div>
                                        <div class="text-xs text-gray-500">{{ $range->programStudi->jenjang }} - Kode: {{ $range->programStudi->kode_prodi }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-mono font-semibold rounded">
                                        {{ $range->prefix }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded font-mono">
                                        {{ $range->prefix }}XXXX
                                    </code>
                                    <p class="text-xs text-gray-500 mt-1">Contoh: {{ $range->prefix }}0001</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-semibold">{{ $range->current_number }}</span>
                                            <span class="text-gray-500">/</span>
                                            <span class="text-gray-700">{{ $range->max_number }}</span>
                                        </div>
                                        <!-- Progress Bar -->
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            @php
                                                $percentage = $range->max_number > 0 ? ($range->current_number / $range->max_number) * 100 : 0;
                                                $barColor = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                            @endphp
                                            <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ number_format($percentage, 1) }}% terpakai</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $remaining = $range->remaining_quota ?? 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                                            {{ $remaining == 0 ? 'bg-red-100 text-red-800' : ($remaining < 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $remaining }}
                                        </span>
                                        @if($remaining < 10 && $remaining > 0)
                                            <i class="fas fa-exclamation-triangle text-yellow-500 ml-2" title="Kuota hampir habis"></i>
                                        @elseif($remaining == 0)
                                            <i class="fas fa-ban text-red-500 ml-2" title="Kuota habis"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.nim-ranges.edit', $range->id) }}"
                                           class="text-[#D4AF37] hover:text-[#b8941f] transition"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if($range->current_number == 0)
                                            <form action="{{ route('admin.nim-ranges.destroy', $range->id) }}"
                                                  method="POST"
                                                  class="inline"
                                                  x-data
                                                  @submit.prevent="window.swalConfirmDelete($event, 'NIM Range ini')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 transition"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled
                                                    class="text-gray-300 cursor-not-allowed"
                                                    title="Tidak dapat dihapus karena sudah digunakan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada NIM Range yang terdaftar</p>
            <p class="text-gray-400 text-sm mt-2">Klik tombol "Tambah NIM Range" atau "Bulk Create" untuk memulai</p>
        </div>
    @endforelse
</div>

<!-- Bulk Create Modal -->
<div x-data="{ open: false }"
     @open-bulk-modal.window="open = true"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Backdrop -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="open = false"
             class="fixed inset-0 bg-black bg-opacity-50"></div>

        <!-- Modal -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6 z-50">

            <div class="mb-4">
                <h3 class="text-xl font-semibold text-[#2D5F3F]">
                    <i class="fas fa-layer-group mr-2"></i>
                    Bulk Create NIM Range
                </h3>
                <p class="text-sm text-gray-500 mt-1">Buat NIM Range untuk semua Program Studi dalam satu tahun</p>
            </div>

            <form action="{{ route('admin.nim-ranges.bulk-create') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="bulk_tahun_masuk" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Masuk <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="tahun_masuk"
                            id="bulk_tahun_masuk"
                            min="2000"
                            max="2100"
                            value="{{ date('Y') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition"
                            required
                        >
                    </div>

                    <div>
                        <label for="bulk_max_number" class="block text-sm font-semibold text-gray-700 mb-2">
                            Maksimal Nomor (per Prodi) <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="max_number"
                            id="bulk_max_number"
                            min="1"
                            max="9999"
                            value="999"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition"
                            required
                        >
                        <p class="text-xs text-gray-500 mt-1">Akan diterapkan untuk semua Program Studi</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button"
                            @click="open = false"
                            class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-save mr-2"></i>
                        Buat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@push('scripts')
<script>
    function toggleSelectYear(checkbox, year) {
        const checkboxes = document.querySelectorAll('.year-' + year + ':not(:disabled)');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        updateSelectedIds();
    }
</script>
@endpush
@endsection
