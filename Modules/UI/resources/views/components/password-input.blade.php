@props([
    'name' => 'password',
    'id' => null,
    'label' => 'Senha',
    'required' => false,
    'placeholder' => 'Digite a senha',
    'disabled' => false,
    'showStrength' => true,
    'autocomplete' => 'new-password',
])

@php
    $fieldId = $id ?? $name;
    $strengthEnabled = filter_var($showStrength, FILTER_VALIDATE_BOOLEAN);
@endphp

<div data-ui-password-field {{ $attributes->class(['tw:space-y-1']) }}>
    <label for="{{ $fieldId }}" class="ui-form-label tw:text-sm tw:font-medium">
        {{ $label }}@if ($required) * @endif
    </label>
    <div class="tw:relative">
        <input id="{{ $fieldId }}" name="{{ $name }}" type="password" value="" @disabled($disabled)
            @required($required) placeholder="{{ $placeholder }}" autocomplete="{{ $autocomplete }}"
            data-ui-password-input @if ($strengthEnabled) data-password-strength-source @endif
            class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:pr-14 tw:text-sm tw:rounded-none tw:appearance-none">
        <button type="button"
            class="tw:absolute tw:right-4 tw:top-1/2 tw:inline-flex tw:h-5 tw:w-5 tw:-translate-y-1/2 tw:items-center tw:justify-center tw:border-0 tw:bg-transparent tw:p-0 tw:leading-none tw:text-[var(--panel-table-muted-text)] hover:tw:text-[var(--panel-body-text)] focus:tw:outline-none"
            data-ui-password-toggle aria-label="Mostrar senha">
            <i class="fa-regular fa-eye tw:text-base tw:leading-none"></i>
        </button>
    </div>

    @if ($strengthEnabled)
        <div class="tw:space-y-1" data-password-strength>
            <div class="tw:h-2 tw:w-full tw:bg-[var(--panel-table-surface-muted)]">
                <div class="tw:h-full tw:w-0 tw:bg-[var(--panel-table-grid-border)] tw:transition-all"
                    data-password-strength-bar></div>
            </div>
            <p class="tw:text-xs tw:text-[var(--panel-table-muted-text)]" data-password-strength-label>
                Use letras maiúsculas, minúsculas, números e símbolos.
            </p>
        </div>
    @endif

    @error($name)
        <p class="tw:text-xs tw:text-rose-700">{{ $message }}</p>
    @enderror
</div>
