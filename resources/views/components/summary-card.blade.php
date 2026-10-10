@props([
    'title' => '',
    'value' => '',
    'icon' => null,
    'color' => 'lime',  // lime, blue, purple, amber, red, emerald
    'hint' => null,
])

@php
    $colorMap = [
        'lime'    => ['bg' => 'bg-lime-100 dark:bg-lime-900/30',       'text' => 'text-lime-600 dark:text-lime-400'],
        'blue'    => ['bg' => 'bg-blue-100 dark:bg-blue-900/30',       'text' => 'text-blue-600 dark:text-blue-400'],
        'purple'  => ['bg' => 'bg-purple-100 dark:bg-purple-900/30',   'text' => 'text-purple-600 dark:text-purple-400'],
        'amber'   => ['bg' => 'bg-amber-100 dark:bg-amber-900/30',     'text' => 'text-amber-600 dark:text-amber-400'],
        'red'     => ['bg' => 'bg-red-100 dark:bg-red-900/30',         'text' => 'text-red-600 dark:text-red-400'],
        'emerald' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/30', 'text' => 'text-emerald-600 dark:text-emerald-400'],
    ];

    $style = $colorMap[$color] ?? $colorMap['lime'];
@endphp

<div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
    <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
            {{ $title }}
        </p>

        @if($icon)
            <div class="w-8 h-8 rounded-lg {{ $style['bg'] }} flex items-center justify-center">
                <svg class="w-4 h-4 {{ $style['text'] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
        @endif
    </div>

    <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $value }}</p>

    @if($hint)
        <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">{{ $hint }}</p>
    @endif
</div>
