@props(['title' => 'Page Title'])

<div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-md p-6 mb-6 border-2 border-[#D4AF37]">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-[#D4AF37] rounded-full flex items-center justify-center">
                <i class="fas fa-folder text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                <p class="text-emerald-50 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        {{ $slot }}
    </div>
</div>
