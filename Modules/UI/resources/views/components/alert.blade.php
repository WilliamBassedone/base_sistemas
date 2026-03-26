{{-- Modules/UI/resources/components/alert.blade.php --}}

@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $variants = [
        'primary' => 'tw:bg-sky-50 tw:border-sky-200 tw:text-sky-800',
        'secondary' => 'tw:bg-slate-50 tw:border-slate-200 tw:text-slate-800',
        'success' => 'tw:bg-emerald-50 tw:border-emerald-200 tw:text-emerald-800',
        'danger' => 'tw:bg-rose-50 tw:border-rose-200 tw:text-rose-800',
        'warning' => 'tw:bg-amber-50 tw:border-amber-200 tw:text-amber-800',
        'info' => 'tw:bg-cyan-50 tw:border-cyan-200 tw:text-cyan-800',
    ];

    $classes = $variants[$variant] ?? $variants['info'];
@endphp

<div
    {{ $attributes->merge([
        'class' => "tw:rounded-lg tw:border tw:px-4 tw:py-3 tw:text-sm {$classes}",
        'role' => 'alert',
    ]) }}>

    <div class="tw:flex tw:items-start tw:justify-between tw:gap-3">
        <div class="tw:flex-1">
            @if ($title)
                <div class="tw:mb-1 tw:font-semibold">
                    {{ $title }}
                </div>
            @endif

            <div>
                {{ $slot }}
            </div>
        </div>
        @if ($dismissible)
            <button type="button" class="tw:text-current tw:opacity-70 hover:tw:opacity-100"
                onclick="this.closest('[role=alert]').remove()" aria-label="Fechar alerta">
                ×
            </button>
        @endif
    </div>
</div>
