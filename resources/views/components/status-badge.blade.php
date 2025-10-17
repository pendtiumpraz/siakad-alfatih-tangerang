@props(['status' => 'pending', 'type' => 'payment'])

@php
$classes = [
    'payment' => [
        'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
        'lunas' => 'bg-green-100 text-green-800 border border-green-300',
        'terlambat' => 'bg-red-100 text-red-800 border border-gold-400',
        'verified' => 'bg-green-100 text-green-800 border border-green-300',
    ],
    'grade' => [
        'A' => 'bg-green-700 text-white border border-green-800',
        'B' => 'bg-green-500 text-white border border-green-600',
        'C' => 'bg-yellow-500 text-white border border-yellow-600',
        'D' => 'bg-orange-500 text-white border border-orange-600',
        'E' => 'bg-red-500 text-white border border-red-600',
    ],
    'status' => [
        'active' => 'bg-green-100 text-green-800 border border-green-300',
        'inactive' => 'bg-gray-100 text-gray-800 border border-gray-300',
        'lulus' => 'bg-blue-100 text-blue-800 border border-blue-300',
    ]
];

$badgeClass = $classes[$type][$status] ?? 'bg-gray-100 text-gray-800 border border-gray-300';

$labels = [
    'payment' => [
        'pending' => 'Pending',
        'lunas' => 'Lunas',
        'terlambat' => 'Terlambat',
        'verified' => 'Terverifikasi',
    ],
    'grade' => [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'E' => 'E',
    ],
    'status' => [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        'lulus' => 'Lulus',
    ]
];

$label = $labels[$type][$status] ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-semibold $badgeClass"]) }}>
    {{ $label }}
</span>
