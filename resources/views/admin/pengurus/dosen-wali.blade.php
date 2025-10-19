@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <i class="fas fa-chalkboard-teacher text-[#4A7C59]"></i>
                <span>Manajemen Dosen Wali</span>
            </h1>
            <p class="text-gray-600 mt-1">Kelola penugasan dosen wali untuk mahasiswa</p>
        </div>
        <a href="{{ route('admin.pengurus.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="islamic-divider"></div>

    <!-- Bulk Assignment Card -->
    <div class="card-islamic p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center space-x-2">
            <i class="fas fa-users-cog text-[#D4AF37]"></i>
            <span>Penugasan Dosen Wali Massal</span>
        </h3>
        <form method="POST" action="{{ route('admin.pengurus.bulk-assign-dosen-wali') }}" class="flex gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                <select name="program_studi_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Dosen Wali</label>
                <select name="dosen_wali_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}">
                            {{ $dosen->gelar_depan }} {{ $dosen->nama_lengkap }} {{ $dosen->gelar_belakang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-[#4A7C59] hover:bg-[#3d6849] text-white px-6 py-2 rounded-lg font-semibold transition h-fit whitespace-nowrap">
                    <i class="fas fa-check-double mr-2"></i>Tunjuk Massal
                </button>
            </div>
        </form>
        <p class="text-sm text-gray-600 mt-3">
            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
            Penugasan massal akan menunjuk dosen wali untuk semua mahasiswa di prodi yang dipilih yang belum memiliki dosen wali.
        </p>
    </div>

    <!-- Filters -->
    <div class="card-islamic p-6">
        <form method="GET" action="{{ route('admin.pengurus.dosen-wali') }}" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Program Studi</label>
                <select name="program_studi_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    <option value="">Semua Program Studi</option>
                    @foreach($programStudis as $prodi)
                        <option value="{{ $prodi->id }}" {{ request('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Dosen Wali</label>
                <select name="dosen_wali_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    <option value="">Semua Dosen</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ request('dosen_wali_id') == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->gelar_depan }} {{ $dosen->nama_lengkap }} {{ $dosen->gelar_belakang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="tanpa_wali" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    <option value="">Semua Mahasiswa</option>
                    <option value="1" {{ request('tanpa_wali') == '1' ? 'selected' : '' }}>Tanpa Wali</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-[#4A7C59] hover:bg-[#3d6849] text-white px-6 py-2 rounded-lg font-semibold transition h-fit">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.pengurus.dosen-wali') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition h-fit">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Mahasiswa List -->
    <div class="card-islamic">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Daftar Mahasiswa</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Wali</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mahasiswas as $mahasiswa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                {{ $mahasiswa->nim }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">Angkatan {{ $mahasiswa->angkatan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $mahasiswa->programStudi->nama_prodi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($mahasiswa->dosenWali)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-[#4A7C59] rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr($mahasiswa->dosenWali->nama_lengkap, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $mahasiswa->dosenWali->gelar_depan }} {{ $mahasiswa->dosenWali->nama_lengkap }} {{ $mahasiswa->dosenWali->gelar_belakang }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $mahasiswa->dosenWali->nidn }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Belum Ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($mahasiswa->dosenWali)
                                    <form method="POST" action="{{ route('admin.pengurus.remove-dosen-wali', $mahasiswa->id) }}" onsubmit="return confirm('Yakin ingin menghapus dosen wali?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 mr-3" title="Hapus Dosen Wali">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                                <button type="button" onclick="showAssignModal({{ $mahasiswa->id }}, '{{ $mahasiswa->nama_lengkap }}')" class="text-[#4A7C59] hover:text-[#3d6849]" title="Tunjuk Dosen Wali">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Tidak ada data mahasiswa</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mahasiswas->hasPages())
            <div class="p-6 border-t border-gray-200">
                {{ $mahasiswas->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" onclick="if(event.target === this) hideAssignModal()">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tunjuk Dosen Wali</h3>
        <form method="POST" action="{{ route('admin.pengurus.assign-dosen-wali') }}">
            @csrf
            <input type="hidden" name="mahasiswa_id" id="modal_mahasiswa_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mahasiswa</label>
                <input type="text" id="modal_mahasiswa_nama" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Dosen Wali</label>
                <select name="dosen_wali_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosens as $dosen)
                        <option value="{{ $dosen->id }}">
                            {{ $dosen->gelar_depan }} {{ $dosen->nama_lengkap }} {{ $dosen->gelar_belakang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="hideAssignModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-semibold transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-[#4A7C59] hover:bg-[#3d6849] text-white py-2 rounded-lg font-semibold transition">
                    Tunjuk
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAssignModal(mahasiswaId, mahasiswaNama) {
    document.getElementById('modal_mahasiswa_id').value = mahasiswaId;
    document.getElementById('modal_mahasiswa_nama').value = mahasiswaNama;
    document.getElementById('assignModal').classList.remove('hidden');
}

function hideAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}
</script>
@endsection
