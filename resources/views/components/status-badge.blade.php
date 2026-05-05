@props(['status' => 'pending', 'type' => 'payment'])

@php
$classes = [
    'payment' => [
        'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
        'lunas' => 'bg-green-100 text-green-800 border border-green-300',
        'terlambat' => 'bg-red-100 text-red-800 border border-gold-400',
        'verified' => 'bg-green-100 text-green-800 border border-green-300',
    ],
    'spmb' => [
        'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
        'verified' => 'bg-blue-100 text-blue-800 border border-blue-300',
        'accepted' => 'bg-green-100 text-green-800 border border-green-300',
        'rejected' => 'bg-red-100 text-red-800 border border-red-300',
        'registered' => 'bg-purple-100 text-purple-800 border border-purple-300',
    ],
    'grade' => [
        'A' => 'text-white border-2',
        'B' => 'text-white border-2',
        'C' => 'text-white border-2',
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
// Skala penilaian: A 80-100 Sangat Baik, B 70-79 Baik, C 60-69 Cukup, D 50-59 / E 0-49 Tidak Lulus
$inlineStyles = [
    'A' => 'background-color: #15803d; border-color: #166534;',   // green-700 (Sangat Baik)
    'B' => 'background-color: #22c55e; border-color: #16a34a;',   // green-500 (Baik)
    'C' => 'background-color: #eab308; border-color: #ca8a04;',   // yellow-500 (Cukup)
    'D' => 'background-color: #f97316; border-color: #ea580c;',   // orange-500 (Tidak Lulus)
    'E' => 'background-color: #ef4444; border-color: #dc2626;',   // red-500 (Tidak Lulus)
];

$inlineStyle = isset($inlineStyles[$status]) && $type === 'grade' ? $inlineStyles[$status] : '';

$labels = [
    'payment' => [
        'pending' => 'Pending',
        'lunas' => 'Lunas',
        'terlambat' => 'Terlambat',
        'verified' => 'Terverifikasi',
    ],
    'spmb' => [
        'pending' => 'Pending',
        'verified' => 'Terverifikasi',
        'accepted' => 'Diterima',
        'rejected' => 'Ditolak',
        'registered' => 'Sudah Daftar Ulang',
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

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-xs font-semibold $badgeClass"]) }} @if($inlineStyle) style="{{ $inlineStyle }}" @endif>
    {{ $label }}
</span>
