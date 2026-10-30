@props([
    'title',
    'value',
    'subtitle' => null,
    'color' => 'cream',
    'badge' => null,
])

@php
    $bgClasses = match($color) {
        'lilac' => 'bg-taby-lilac border-taby-lilac-border text-taby-charcoal',
        'yellow' => 'bg-taby-yellow border-taby-yellow-border text-taby-charcoal',
        'mint' => 'bg-taby-mint border-taby-mint-border text-taby-charcoal',
        'rose' => 'bg-taby-rose border-taby-rose-border text-taby-charcoal',
        default => 'bg-white border-taby-border text-taby-charcoal',
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl border-2 p-3 sm:p-5 shadow-pastel hover:translate-y-[-2px] transition duration-200 $bgClasses flex flex-col justify-between min-w-0 overflow-hidden"]) }}>
    <div class="min-w-0">
        <div class="flex items-center justify-between gap-1 mb-1.5 sm:mb-2">
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-taby-muted font-display truncate">{{ $title }}</span>
            @if($badge)
                <span class="text-[9px] sm:text-xs font-extrabold px-1.5 sm:px-2 py-0.5 rounded-full bg-white/80 border border-taby-charcoal/10 shrink-0">
                    {{ $badge }}
                </span>
            @endif
        </div>

        <div class="text-sm min-[360px]:text-base sm:text-xl lg:text-2xl font-extrabold tracking-tight text-taby-charcoal tabular-nums font-sans leading-tight truncate" title="{{ $value }}">
            {{ $value }}
        </div>
    </div>

    @if($subtitle)
        <div class="mt-2 text-[10px] sm:text-xs text-taby-muted flex items-center gap-1 font-medium truncate" title="{{ $subtitle }}">
            {{ $subtitle }}
        </div>
    @endif

    {{ $slot }}
</div>
