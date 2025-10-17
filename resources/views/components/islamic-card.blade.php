@props(['title' => '', 'headerClass' => ''])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-green-600 relative']) }}>
    <!-- Islamic Pattern Overlay -->
    <div class="absolute top-0 right-0 w-32 h-32 opacity-5 pointer-events-none">
        <svg viewBox="0 0 100 100" class="w-full h-full">
            <pattern id="islamic-pattern-{{ Str::random(6) }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle cx="10" cy="10" r="2" fill="#4A7C59"/>
                <circle cx="0" cy="0" r="2" fill="#D4AF37"/>
                <circle cx="20" cy="20" r="2" fill="#D4AF37"/>
            </pattern>
            <rect width="100" height="100" fill="url(#islamic-pattern-{{ Str::random(6) }})"/>
        </svg>
    </div>

    @if($title)
    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-yellow-50 border-b border-green-100 {{ $headerClass }}">
        <h3 class="text-lg font-semibold text-green-800">{{ $title }}</h3>
    </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>
</div>
