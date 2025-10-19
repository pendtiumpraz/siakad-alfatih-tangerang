<div class="py-2">
    <div class="px-4 py-3 border-b border-gray-200">
        <h3 class="text-sm font-bold text-gray-800">Pengumuman</h3>
        <p class="text-xs text-gray-500">{{ $unreadCount ?? 0 }} pengumuman baru</p>
    </div>

    <div class="max-h-96 overflow-y-auto">
        @if(isset($recentPengumumans) && count($recentPengumumans) > 0)
            @foreach($recentPengumumans as $pengumuman)
                @php
                    $isUnread = $pengumuman->reads->isEmpty();
                    $iconBgColor = match($pengumuman->tipe) {
                        'info' => 'bg-blue-100',
                        'penting' => 'bg-red-100',
                        'pengingat' => 'bg-yellow-100',
                        'kegiatan' => 'bg-green-100',
                        default => 'bg-gray-100'
                    };
                    $iconColor = match($pengumuman->tipe) {
                        'info' => 'text-blue-600',
                        'penting' => 'text-red-600',
                        'pengingat' => 'text-yellow-600',
                        'kegiatan' => 'text-green-600',
                        default => 'text-gray-600'
                    };
                    $dotColor = match($pengumuman->tipe) {
                        'info' => 'bg-blue-500',
                        'penting' => 'bg-red-500',
                        'pengingat' => 'bg-yellow-500',
                        'kegiatan' => 'bg-green-500',
                        default => 'bg-gray-500'
                    };
                @endphp

                <a href="{{ route('mahasiswa.notifications.index') }}" class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100">
                    <div class="flex items-start space-x-3">
                        <div class="{{ $iconBgColor }} p-2 rounded-full">
                            @if($pengumuman->tipe === 'info')
                                <svg class="w-4 h-4 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($pengumuman->tipe === 'penting')
                                <svg class="w-4 h-4 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($pengumuman->tipe === 'pengingat')
                                <svg class="w-4 h-4 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($pengumuman->tipe === 'kegiatan')
                                <svg class="w-4 h-4 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $pengumuman->judul }}</p>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ Str::limit($pengumuman->isi, 80) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $pengumuman->created_at->diffForHumans() }}</p>
                        </div>
                        @if($isUnread)
                            <div class="w-2 h-2 {{ $dotColor }} rounded-full flex-shrink-0"></div>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <div class="px-4 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm">Tidak ada pengumuman</p>
            </div>
        @endif
    </div>

    <div class="px-4 py-3 border-t border-gray-200">
        <a href="{{ route('mahasiswa.notifications.index') }}" class="text-sm text-[#4A7C59] hover:text-[#D4AF37] font-semibold flex items-center justify-center space-x-2">
            <span>Lihat Semua Pengumuman</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>
