@props([
    'type' => 'paid',
])

@php
    $classes = match(strtoupper($type)) {
        'PAID' => 'bg-taby-mint text-taby-mint-dark border-taby-mint-border',
        'PENDING' => 'bg-taby-yellow text-taby-yellow-dark border-taby-yellow-border',
        'FULLTIME' => 'bg-taby-lilac text-taby-lilac-dark border-taby-lilac-border',
        'FREELANCE' => 'bg-taby-yellow text-taby-yellow-dark border-taby-yellow-border',
        'RETAINER' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        default => 'bg-gray-100 text-gray-800 border-gray-300',
    };

    $label = match(strtoupper($type)) {
        'PAID' => 'Paid 🐾',
        'PENDING' => 'Pending ⏳',
        'FULLTIME' => 'WFO Kemang',
        'FREELANCE' => 'Freelance Sesi',
        'RETAINER' => 'Retainer Ads',
        default => $type,
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border $classes font-display"]) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</span>
