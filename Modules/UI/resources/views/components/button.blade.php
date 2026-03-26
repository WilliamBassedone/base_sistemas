{{-- 
Componente reutilizavel de botao.
Uso basico:
<x-ui::button type="submit" variant="success">Salvar</x-ui::button>
<x-ui::button as="a" href="..." variant="danger">Cancelar</x-ui::button>
--}}
@props([
    // Cor/estilo visual do botao.
    'variant' => 'primary',
    // Tamanho pre-definido.
    'size' => 'md',
    // Tipo do <button> (submit, button, reset). Ignorado quando as="a".
    'type' => 'button',
    // Define qual tag sera renderizada: <button> ou <a>.
    'as' => 'button',
    // Se true, ocupa 100% da largura.
    'block' => false,
    // Desabilita interacao.
    'disabled' => false,
])

@php
    // Aceita disabled como booleano ou atributo HTML.
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN) || $attributes->has('disabled');

    // Classes base comuns a qualquer variante/tamanho.
    $base = 'tw:inline-flex tw:items-center tw:justify-center tw:gap-2 tw:font-semibold tw:transition-colors tw:duration-150';

    // Escala de tamanhos do componente.
    $sizes = [
        'sm' => 'tw:min-w-[96px] tw:px-3 tw:py-1.5 tw:text-xs',
        'md' => 'tw:min-w-[128px] tw:px-4 tw:py-2 tw:text-sm',
        'lg' => 'tw:min-w-[160px] tw:px-5 tw:py-2.5 tw:text-base',
    ];

    // Variantes de cor.
    $variants = [
        'primary' => 'tw:bg-sky-600 tw:text-white hover:tw:bg-sky-700',
        'secondary' => 'tw:bg-white tw:text-slate-700 tw:border tw:border-slate-300 hover:tw:bg-slate-100',
        'success' => 'tw:bg-emerald-600 tw:text-white hover:tw:bg-emerald-700',
        'danger' => 'tw:bg-rose-600 tw:text-white hover:tw:bg-rose-700',
        'ghost' => 'tw:bg-transparent tw:text-slate-700 tw:border tw:border-transparent hover:tw:bg-slate-100',
    ];

    // Combina tudo em uma string final de classes.
    $classes = implode(' ', [
        $base,
        $sizes[$size] ?? $sizes['md'],
        $variants[$variant] ?? $variants['primary'],
        $block ? 'tw:w-full' : '',
        'disabled:tw:opacity-60 disabled:tw:cursor-not-allowed',
        $isDisabled ? 'tw:pointer-events-none tw:opacity-60' : '',
    ]);
@endphp

{{-- Quando as="a", renderiza link estilizado como botao. --}}
@if ($as === 'a')
    <a {{ $attributes->merge(['class' => $classes]) }}
        @if ($isDisabled) aria-disabled="true" tabindex="-1" @endif>
        {{ $slot }}
    </a>
@else
    {{-- Caso contrario, renderiza botao nativo HTML. --}}
    <button type="{{ $type }}" @disabled($isDisabled) {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
