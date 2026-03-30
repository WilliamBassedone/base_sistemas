@props([
    'name' => 'postal_code',
    'id' => null,
    'label' => 'CEP',
    'value' => null,
    'required' => false,
    'placeholder' => '00000-000',
    'disabled' => false,
    'addressTarget' => null,
    'neighborhoodTarget' => null,
    'cityTarget' => null,
    'stateTarget' => null,
    'complementTarget' => null,
])

@php
    $fieldId = $id ?? $name;
    $fieldValue = old($name, $value);
@endphp

<div {{ $attributes->class(['tw:space-y-1']) }}>
    <label for="{{ $fieldId }}" class="ui-form-label tw:text-sm tw:font-medium">
        {{ $label }}@if ($required)
            *
        @endif
    </label>
    <input id="{{ $fieldId }}" name="{{ $name }}" type="text" value="{{ $fieldValue }}"
        @disabled($disabled) @required($required) placeholder="{{ $placeholder }}" maxlength="9" inputmode="numeric"
        autocomplete="off" data-ui-cep-input
        @if ($addressTarget) data-address-target="{{ $addressTarget }}" @endif
        @if ($neighborhoodTarget) data-neighborhood-target="{{ $neighborhoodTarget }}" @endif
        @if ($cityTarget) data-city-target="{{ $cityTarget }}" @endif
        @if ($stateTarget) data-state-target="{{ $stateTarget }}" @endif
        @if ($complementTarget) data-complement-target="{{ $complementTarget }}" @endif
        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
    <p class="tw:text-xs tw:text-[var(--panel-table-muted-text)]" data-ui-cep-feedback></p>
    @error($name)
        <p class="tw:text-xs tw:text-rose-700">{{ $message }}</p>
    @enderror
</div>
