@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }} style="color: #2D5F3F;">
    {{ $value ?? $slot }}
</label>
