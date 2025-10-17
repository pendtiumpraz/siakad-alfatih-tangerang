@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 rounded-md shadow-sm islamic-input w-full']) }} style="border-color: #d1d5db;" />
