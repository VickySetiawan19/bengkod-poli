@props([
    'variant' => 'neutral', // neutral, primary, success, info
    'size' => 'xs',        // xs, sm
    'class' => ''
])

@php
    $sizeClasses = match($size) {
        'sm' => 'px-2.5 py-1 text-xs gap-1.5',
        default => 'px-2 py-0.5 text-[11px] gap-1',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-primary/10 text-primary border-primary/20',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'info' => 'bg-sky-50 text-sky-700 border-sky-200/80',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200/80',
        default => 'bg-base-200/80 text-base-content/75 border-base-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center {$variantClasses} {$sizeClasses} font-medium border rounded-md whitespace-nowrap shadow-2xs {$class}"]) }}>
    {{ $slot }}
</span>
