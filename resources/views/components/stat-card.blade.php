@props(['title', 'value', 'icon' => '', 'color' => 'green', 'subtext' => ''])

@php
$colorClasses = [
    'green' => 'border-green-500 bg-gradient-to-br from-green-50 to-green-100',
    'yellow' => 'border-yellow-500 bg-gradient-to-br from-yellow-50 to-yellow-100',
    'red' => 'border-red-500 bg-gradient-to-br from-red-50 to-red-100',
    'gold' => 'border-yellow-600 bg-gradient-to-br from-yellow-100 to-yellow-200',
    'blue' => 'border-blue-500 bg-gradient-to-br from-blue-50 to-blue-100',
];

$iconColorClasses = [
    'green' => 'text-green-600',
    'yellow' => 'text-yellow-600',
    'red' => 'text-red-600',
    'gold' => 'text-yellow-700',
    'blue' => 'text-blue-600',
];

$cardClass = $colorClasses[$color] ?? $colorClasses['green'];
$iconClass = $iconColorClasses[$color] ?? $iconColorClasses['green'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg shadow-md border-l-4 p-6 transition-all duration-300 hover:shadow-lg $cardClass"]) }}>
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
            @if($subtext)
            <p class="text-xs text-gray-500 mt-1">{{ $subtext }}</p>
            @endif
        </div>
        @if($icon)
        <div class="ml-4">
            <div class="p-3 rounded-full bg-white bg-opacity-50">
                {!! $icon !!}
            </div>
        </div>
        @endif
    </div>

    <!-- Islamic decorative element -->
    <div class="mt-4 pt-4 border-t border-gray-300 border-opacity-50">
        <div class="flex justify-center space-x-2">
            <div class="w-2 h-2 rounded-full bg-gradient-to-r from-green-400 to-yellow-400"></div>
            <div class="w-2 h-2 rounded-full bg-gradient-to-r from-yellow-400 to-green-400"></div>
            <div class="w-2 h-2 rounded-full bg-gradient-to-r from-green-400 to-yellow-400"></div>
        </div>
    </div>
</div>
