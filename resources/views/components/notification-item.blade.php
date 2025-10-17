@props(['title', 'message', 'time', 'icon' => '', 'read' => false, 'url' => '#'])

<a href="{{ $url }}" {{ $attributes->merge(['class' => 'block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 ' . ($read ? '' : 'bg-green-50')]) }}>
    <div class="flex items-start">
        @if($icon)
        <div class="flex-shrink-0 mt-1">
            {!! $icon !!}
        </div>
        @endif
        <div class="flex-1 {{ $icon ? 'ml-3' : '' }}">
            <p class="text-sm font-medium text-gray-900">{{ $title }}</p>
            <p class="text-sm text-gray-600 mt-1">{{ $message }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $time }}</p>
        </div>
        @if(!$read)
        <div class="flex-shrink-0 ml-2">
            <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
        </div>
        @endif
    </div>
</a>
