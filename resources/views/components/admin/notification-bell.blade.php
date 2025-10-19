@props(['pengumumans' => [], 'count' => 0])

<div x-data="{ notificationOpen: false }" class="relative">
    <button @click="notificationOpen = !notificationOpen" class="relative text-gray-600 hover:text-[#2D5F3F] transition">
        <i class="fas fa-bell text-xl"></i>
        @if($count > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                {{ $count > 9 ? '9+' : $count }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    <div
        x-show="notificationOpen"
        @click.away="notificationOpen = false"
        x-transition
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
        style="display: none;"
    >
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-4 py-3 rounded-t-lg">
            <h3 class="text-white font-semibold">Pengumuman Terbaru</h3>
        </div>
        <div class="max-h-96 overflow-y-auto">
            @if($count > 0 && count($pengumumans) > 0)
                @foreach($pengumumans as $pengumuman)
                    <div class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if($pengumuman->tipe === 'info')
                                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                @elseif($pengumuman->tipe === 'penting')
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                @elseif($pengumuman->tipe === 'pengingat')
                                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                                @elseif($pengumuman->tipe === 'kegiatan')
                                    <i class="fas fa-calendar text-green-500 text-xl"></i>
                                @else
                                    <i class="fas fa-bell text-[#D4AF37] text-xl"></i>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-800 font-medium">{{ Str::limit($pengumuman->judul, 50) }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($pengumuman->isi, 80) }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $pengumuman->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-4xl mb-2 text-gray-300"></i>
                    <p class="text-sm">Tidak ada pengumuman</p>
                </div>
            @endif
        </div>
        <div class="bg-gray-50 px-4 py-3 rounded-b-lg text-center border-t border-gray-200">
            <a href="{{ auth()->user()->role === 'super_admin' ? route('admin.pengumuman.index') : (auth()->user()->role === 'dosen' ? route('dosen.pengumuman.index') : route('operator.pengumuman.index')) }}" class="text-sm text-[#2D5F3F] hover:text-[#D4AF37] font-semibold">
                Lihat Semua Pengumuman
            </a>
        </div>
    </div>
</div>
