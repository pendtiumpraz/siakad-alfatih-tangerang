@props(['type', 'label'])

@php
    $classes = [
        'super_admin' => 'bg-purple-100 text-purple-800',
        'operator' => 'bg-yellow-100 text-yellow-800',
        'dosen' => 'bg-blue-100 text-blue-800',
        'mahasiswa' => 'bg-green-100 text-green-800',
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-red-100 text-red-800',
    ];

    $badgeClass = $classes[$type] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="px-3 py-1 {{ $badgeClass }} text-xs font-semibold rounded-full">
    {{ $label }}
</span>
