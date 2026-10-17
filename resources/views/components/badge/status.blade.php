@props([
    'status' => 'belum_bayar',
    'size' => 'sm',
    'class' => ''
])

@php
    $normalized = strtolower(trim($status));
    
    // Size variants
    $sizeClasses = match($size) {
        'xs' => 'px-2 py-0.5 text-[11px] gap-1',
        'md' => 'px-3.5 py-1 text-sm gap-2',
        default => 'px-2.5 py-1 text-xs gap-1.5', // 'sm'
    };

    // Status config: label, icon, background/text/border styling
    $config = match($normalized) {
        'lunas' => [
            'label' => 'Lunas',
            'icon' => 'fa-check-circle',
            'style' => 'badge-success text-white',
        ],
        'menunggu_verifikasi', 'verifikasi' => [
            'label' => 'Menunggu Verifikasi',
            'icon' => 'fa-clock',
            'style' => 'badge-warning text-slate-900 animate-pulse',
        ],
        'belum_bayar', 'unpaid' => [
            'label' => 'Belum Bayar',
            'icon' => 'fa-circle-exclamation',
            'style' => 'badge-error text-white',
        ],
        'selesai', 'sudah_diperiksa' => [
            'label' => 'Selesai',
            'icon' => 'fa-check-circle',
            'style' => 'badge-success text-white',
        ],
        'sedang_diperiksa' => [
            'label' => 'Sedang Diperiksa',
            'icon' => 'fa-stethoscope',
            'style' => 'badge-info text-white animate-pulse',
        ],
        'menunggu', 'menunggu_panggilan' => [
            'label' => 'Menunggu Panggilan',
            'icon' => 'fa-hourglass-half',
            'style' => 'badge-warning text-slate-900',
        ],
        'aktif' => [
            'label' => 'Aktif',
            'icon' => 'fa-circle-check',
            'style' => 'badge-success text-white',
        ],
        'tidak_aktif' => [
            'label' => 'Tidak Aktif',
            'icon' => 'fa-circle-xmark',
            'style' => 'badge-ghost text-base-content/60 border-base-300',
        ],
        default => [
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
            'icon' => 'fa-circle-info',
            'style' => 'badge-ghost text-base-content/70',
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => "badge {$config['style']} {$sizeClasses} font-semibold whitespace-nowrap shadow-xs rounded-full inline-flex items-center {$class}"]) }}>
    @if(!empty($config['icon']))
        <i class="fas {{ $config['icon'] }} text-[10px] shrink-0"></i>
    @endif
    <span>{{ $slot->isEmpty() ? $config['label'] : $slot }}</span>
</span>
