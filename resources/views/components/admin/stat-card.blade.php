@props(['icon', 'number', 'label', 'color' => 'green'])

@php
    $colorClasses = [
        'blue' => 'from-blue-500 to-blue-700',
        'green' => 'from-[#2D5F3F] to-[#4A7C59]',
        'purple' => 'from-purple-500 to-purple-700',
        'gold' => 'from-[#D4AF37] to-[#F4E5C3]',
    ];

    $gradientClass = $colorClasses[$color] ?? $colorClasses['green'];
@endphp

<div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6 hover:shadow-lg transition-shadow">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-gray-600 text-sm mb-2">{{ $label }}</p>
            <p class="text-3xl font-bold text-[#2D5F3F]">{{ $number }}</p>
        </div>
        <div class="w-16 h-16 bg-gradient-to-br {{ $gradientClass }} rounded-lg flex items-center justify-center text-white shadow-lg">
            <i class="{{ $icon }} text-2xl"></i>
        </div>
    </div>
    <div class="mt-4 pt-4 border-t border-gray-200">
        <p class="text-xs text-gray-500">
            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
            Data terkini
        </p>
    </div>
</div>
