@props([
    'name' => 'phone',
    'id' => null,
    'label' => 'Telefone',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'disabled' => false,
    'variant' => 'mobile',
])

@php
    $fieldId = $id ?? $name;
    $fieldValue = old($name, $value);
    $resolvedPlaceholder = $placeholder ?? ($variant === 'fixed' ? 'Ex.: (11) 3333-4444' : 'Ex.: (11) 99999-0000');
@endphp

<div {{ $attributes->class(['tw:space-y-1']) }}>
    <label for="{{ $fieldId }}" class="ui-form-label tw:text-sm tw:font-medium">
        {{ $label }}@if ($required) * @endif
    </label>
    <input id="{{ $fieldId }}" name="{{ $name }}" type="text" value="{{ $fieldValue }}" @disabled($disabled)
        @required($required) placeholder="{{ $resolvedPlaceholder }}" inputmode="tel" autocomplete="off"
        data-ui-phone-input data-phone-variant="{{ $variant }}"
        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
    @error($name)
        <p class="tw:text-xs tw:text-rose-700">{{ $message }}</p>
    @enderror
</div>
