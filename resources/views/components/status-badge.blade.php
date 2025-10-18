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
        'A' => 'text-white border-2',
        'A-' => 'text-white border-2',
        'AB' => 'text-white border-2', // Old format backward compatibility
        'B+' => 'text-white border-2',
        'B' => 'text-white border-2',
        'B-' => 'text-white border-2',
        'BC' => 'text-white border-2', // Old format backward compatibility
        'C+' => 'text-white border-2',
        'C' => 'text-white border-2',
        'C-' => 'text-white border-2',
        'D' => 'text-white border-2',
        'E' => 'text-white border-2',
    ],
    'status' => [
        'active' => 'bg-green-100 text-green-800 border border-green-300',
        'inactive' => 'bg-gray-100 text-gray-800 border border-gray-300',
        'lulus' => 'bg-blue-100 text-blue-800 border border-blue-300',
    ]
];

$badgeClass = $classes[$type][$status] ?? 'bg-gray-100 text-gray-800 border border-gray-300';

// Inline styles for all grades - using hex colors to avoid Tailwind purge issues
$inlineStyles = [
    'A' => 'background-color: #15803d; border-color: #166534;',   // green-700 (hijau tua)
    'A-' => 'background-color: #16a34a; border-color: #15803d;',  // green-600 (hijau medium)
    'AB' => 'background-color: #16a34a; border-color: #15803d;',  // green-600 (hijau medium) - backward compatibility
    'B+' => 'background-color: #22c55e; border-color: #16a34a;',  // green-500 (hijau terang)
    'B' => 'background-color: #4ade80; border-color: #22c55e;',   // green-400 (hijau sangat terang)
    'B-' => 'background-color: #14b8a6; border-color: #0d9488;',  // teal-500 (cyan/transisi)
    'BC' => 'background-color: #14b8a6; border-color: #0d9488;',  // teal-500 - backward compatibility
    'C+' => 'background-color: #eab308; border-color: #ca8a04;',  // yellow-500 (kuning terang)
    'C' => 'background-color: #ca8a04; border-color: #a16207;',   // yellow-600 (kuning tua)
    'C-' => 'background-color: #f97316; border-color: #ea580c;',  // orange-500 (orange terang)
    'D' => 'background-color: #ea580c; border-color: #c2410c;',   // orange-600 (orange tua)
    'E' => 'background-color: #ef4444; border-color: #dc2626;',   // red-500 (merah)
];

$inlineStyle = isset($inlineStyles[$status]) && $type === 'grade' ? $inlineStyles[$status] : '';

$labels = [
    'payment' => [
        'pending' => 'Pending',
        'lunas' => 'Lunas',
        'terlambat' => 'Terlambat',
        'verified' => 'Terverifikasi',
    ],
    'grade' => [
        'A' => 'A',
        'A-' => 'A-',
        'AB' => 'AB', // Old format
        'B+' => 'B+',
        'B' => 'B',
        'B-' => 'B-',
        'BC' => 'BC', // Old format
        'C+' => 'C+',
        'C' => 'C',
        'C-' => 'C-',
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

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-semibold $badgeClass"]) }} @if($inlineStyle) style="{{ $inlineStyle }}" @endif>
    {{ $label }}
</span>
