@extends('layouts.admin')

@section('title', 'Approval KRS - Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2" style="color: #1F2937;">Approval KRS</h1>
        <p style="color: #4B5563;">Dashboard overview approval KRS per program studi</p>
    </div>

    <!-- Semester Filter -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4">
        <form method="GET" action="{{ route('admin.krs-approval.index') }}" class="flex items-center gap-4">
            <label class="text-sm font-semibold" style="color: #374151;">Semester:</label>
            <select name="semester_id" onchange="this.form.submit()" 
                    class="px-4 py-2 border rounded-lg focus:ring-2" style="border-color: #D1D5DB; focus:ring-color: #3B82F6; focus:border-color: #3B82F6;">
                @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ $sem->id == $semester->id ? 'selected' : '' }}>
                        {{ $sem->nama_semester }} - {{ $sem->tahun_akademik }}
                    </option>
                @endforeach
            </select>
            <span class="text-sm" style="color: #4B5563;">
                @if($semester->is_active)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: #DCFCE7; color: #166534;">
                        ● Aktif
                    </span>
                @endif
            </span>
        </form>
    </div>

    @if(session('success'))
        <div class="border px-6 py-4 rounded-lg relative mb-6" role="alert" style="background-color: #DCFCE7; border-color: #4ADE80; color: #15803D;">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="border px-6 py-4 rounded-lg relative mb-6" role="alert" style="background-color: #FEE2E2; border-color: #F87171; color: #B91C1C;">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Program Studi Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($programStudis as $prodi)
            <div class="bg-white rounded-lg shadow-lg border overflow-hidden hover:shadow-xl transition-shadow duration-300" style="border-color: #D4AF37;">
                <!-- Header -->
                <div class="px-6 py-4" style="background: linear-gradient(to right, #2D5F3F, #4A7C59);">
                    <h3 class="text-xl font-bold text-white">{{ $prodi->nama_prodi }}</h3>
                    <p class="text-sm" style="color: #D1FAE5;">{{ $prodi->kode_prodi }}</p>
                </div>

                <!-- Statistics -->
                <div class="p-6">
                    <!-- Main Stats Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <!-- Total Mahasiswa -->
                        <div class="rounded-lg p-4" style="background-color: #F9FAFB;">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm" style="color: #4B5563;">Total Mahasiswa</span>
                                <svg class="w-5 h-5" style="color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold" style="color: #1F2937;">{{ $prodi->total_mahasiswa }}</div>
                        </div>

                        <!-- Submitted -->
                        <div class="rounded-lg p-4" style="background-color: #EFF6FF;">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm" style="color: #2563EB;">Sudah Submit</span>
                                <svg class="w-5 h-5" style="color: #60A5FA;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold" style="color: #1E40AF;">{{ $prodi->submitted_count }}</span>
                                <span class="text-sm" style="color: #2563EB;">({{ $prodi->submitted_percentage }}%)</span>
                            </div>
                        </div>

                        <!-- Belum Submit -->
                        <div class="rounded-lg p-4" style="background-color: #FEFCE8;">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm" style="color: #CA8A04;">Belum Submit</span>
                                <svg class="w-5 h-5" style="color: #FACC15;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold" style="color: #854D0E;">{{ $prodi->not_submitted_count }}</div>
                        </div>

                        <!-- Belum Bayar SPP -->
                        <div class="rounded-lg p-4" style="background-color: #FEF2F2;">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm" style="color: #DC2626;">Belum Bayar SPP</span>
                                <svg class="w-5 h-5" style="color: #F87171;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold" style="color: #991B1B;">{{ $prodi->unpaid_spp_count }}</div>
                        </div>
                    </div>

                    <!-- Approval Status -->
                    <div class="border-t pt-4" style="border-color: #E5E7EB;">
                        <h4 class="text-sm font-semibold mb-3" style="color: #374151;">Status Approval:</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm" style="color: #4B5563;">⏳ Pending Approval:</span>
                                <span class="font-bold" style="color: #EA580C;">{{ $prodi->pending_approval_count }} KRS</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm" style="color: #4B5563;">✅ Sudah Approved:</span>
                                <span class="font-bold" style="color: #16A34A;">{{ $prodi->approved_count }} KRS</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm" style="color: #4B5563;">❌ Rejected:</span>
                                <span class="font-bold" style="color: #DC2626;">{{ $prodi->rejected_count }} KRS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex gap-3">
                        @if($prodi->pending_approval_count > 0)
                            <form action="{{ route('admin.krs-approval.mass-approve-prodi', $prodi->id) }}" 
                                  method="POST" 
                                  class="flex-1"
                                  onsubmit="return confirm('Approve SEMUA KRS yang sudah bayar SPP untuk {{ $prodi->nama_prodi }}?\n\nHanya mahasiswa yang sudah bayar SPP yang akan di-approve.\nYang belum bayar akan di-skip otomatis.')">
                                @csrf
                                <input type="hidden" name="semester_id" value="{{ $semester->id }}">
                                <button type="submit" 
                                        class="w-full px-4 py-3 text-white rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                                        style="background: linear-gradient(to right, #16A34A, #15803D);"
                                        onmouseover="this.style.background='linear-gradient(to right, #15803D, #166534)'"
                                        onmouseout="this.style.background='linear-gradient(to right, #16A34A, #15803D)'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Approve Semua ({{ $prodi->pending_approval_count }})
                                </button>
                            </form>
                        @else
                            <div class="flex-1 px-4 py-3 rounded-lg text-center font-semibold" style="background-color: #F3F4F6; color: #6B7280;">
                                Tidak ada KRS pending
                            </div>
                        @endif

                        <a href="{{ route('admin.krs-approval.detail', ['prodiId' => $prodi->id, 'semester_id' => $semester->id]) }}" 
                           class="px-6 py-3 text-white rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg flex items-center gap-2"
                           style="background: linear-gradient(to right, #2D5F3F, #4A7C59);"
                           onmouseover="this.style.background='linear-gradient(to right, #1F4530, #2D5F3F)'"
                           onmouseout="this.style.background='linear-gradient(to right, #2D5F3F, #4A7C59)'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 border rounded-lg p-12 text-center" style="background-color: #F9FAFB; border-color: #E5E7EB;">
                <svg class="w-16 h-16 mx-auto mb-4" style="color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-semibold mb-2" style="color: #374151;">Tidak Ada Program Studi</h3>
                <p style="color: #6B7280;">Belum ada program studi yang terdaftar.</p>
            </div>
        @endforelse
    </div>

    <!-- Info Box -->
    <div class="mt-8 border-2 rounded-lg p-6" style="background-color: #ECFDF5; border-color: #D4AF37;">
        <div class="flex items-start">
            <svg class="w-6 h-6 mr-3 mt-0.5" style="color: #2D5F3F;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-lg font-semibold mb-2" style="color: #2D5F3F;">ℹ️ Informasi Approval KRS</h4>
                <ul class="text-sm space-y-1" style="color: #065F46;">
                    <li>• <strong>Mass Approve:</strong> Hanya akan approve mahasiswa yang <strong>sudah bayar SPP</strong></li>
                    <li>• Mahasiswa yang belum bayar SPP akan di-skip otomatis</li>
                    <li>• Untuk approve mahasiswa yang belum bayar (kasus khusus), gunakan <strong>Force Approve</strong> di detail individual</li>
                    <li>• Jadwal mahasiswa akan muncul HANYA setelah KRS di-approve</li>
                    <li>• Klik "Detail" untuk melihat daftar mahasiswa per prodi dengan filter dan pencarian</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
