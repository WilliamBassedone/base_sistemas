@props([
    'name' => 'cpf',
    'id' => null,
    'label' => 'CPF',
    'value' => null,
    'required' => false,
    'placeholder' => '000.000.000-00',
    'disabled' => false,
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
        @disabled($disabled) @required($required) placeholder="{{ $placeholder }}" maxlength="14" inputmode="numeric"
        autocomplete="off" data-ui-cpf-input
        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
    @error($name)
        <p class="tw:text-xs tw:text-rose-700">{{ $message }}</p>
    @enderror
</div>
