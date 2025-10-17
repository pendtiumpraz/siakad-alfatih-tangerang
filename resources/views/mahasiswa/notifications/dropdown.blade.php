<div class="py-2">
    <div class="px-4 py-3 border-b border-gray-200">
        <h3 class="text-sm font-bold text-gray-800">Pengumuman</h3>
        <p class="text-xs text-gray-500">3 pengumuman baru</p>
    </div>

    <div class="max-h-96 overflow-y-auto">
        <!-- Notification Item 1 -->
        <a href="{{ route('mahasiswa.notifications.index') }}" class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100">
            <div class="flex items-start space-x-3">
                <div class="bg-blue-100 p-2 rounded-full">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">Pembayaran UTS Semester Genap</p>
                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">Batas pembayaran UTS 30 Oktober 2025. Segera lakukan pembayaran.</p>
                    <p class="text-xs text-gray-500 mt-1">3 hari yang lalu</p>
                </div>
                <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
            </div>
        </a>

        <!-- Notification Item 2 -->
        <a href="{{ route('mahasiswa.notifications.index') }}" class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100">
            <div class="flex items-start space-x-3">
                <div class="bg-yellow-100 p-2 rounded-full">
                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">Perubahan Jadwal Kuliah</p>
                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">Mata kuliah Fiqih Muamalah dipindah ke Ruang C-204</p>
                    <p class="text-xs text-gray-500 mt-1">5 hari yang lalu</p>
                </div>
                <div class="w-2 h-2 bg-yellow-500 rounded-full flex-shrink-0"></div>
            </div>
        </a>

        <!-- Notification Item 3 -->
        <a href="{{ route('mahasiswa.notifications.index') }}" class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100">
            <div class="flex items-start space-x-3">
                <div class="bg-green-100 p-2 rounded-full">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">Nilai UAS Telah Keluar</p>
                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">Nilai UAS Semester Ganjil sudah dapat dilihat</p>
                    <p class="text-xs text-gray-500 mt-1">1 minggu yang lalu</p>
                </div>
            </div>
        </a>

        <!-- Notification Item 4 -->
        <a href="{{ route('mahasiswa.notifications.index') }}" class="block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-100">
            <div class="flex items-start space-x-3">
                <div class="bg-purple-100 p-2 rounded-full">
                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">Seminar Nasional Pendidikan Islam</p>
                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">Pendaftaran dibuka hingga 15 November 2025</p>
                    <p class="text-xs text-gray-500 mt-1">2 minggu yang lalu</p>
                </div>
            </div>
        </a>
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
