@props([
    'column',
    'label',
    'currentSort' => null,
    'currentDirection' => 'asc',
    'class' => ''
])

@php
    $isActive = $currentSort === $column;
    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
    $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection]);
@endphp

<th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider {{ $class }}">
    <a href="{{ $url }}" class="flex items-center space-x-1 hover:text-[#D4AF37] transition-colors group">
        <span>{{ $label }}</span>
        <span class="flex flex-col">
            @if($isActive)
                @if($currentDirection === 'asc')
                    <i class="fas fa-sort-up text-[#D4AF37]"></i>
                @else
                    <i class="fas fa-sort-down text-[#D4AF37]"></i>
                @endif
            @else
                <i class="fas fa-sort opacity-50 group-hover:opacity-100"></i>
            @endif
        </span>
    </a>
</th>
