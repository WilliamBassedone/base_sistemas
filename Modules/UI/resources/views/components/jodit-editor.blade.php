@props([
    'name',
    'id' => null,
    'value' => null,
    'label' => null,
    'height' => 420,
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
])

@php
    $fieldId = $id ?: str_replace(['.', '[', ']'], ['_', '_', ''], $name);
    $fieldValue = old($name, $value);
    $isReadonly = filter_var($readonly, FILTER_VALIDATE_BOOLEAN);
    $isRequired = filter_var($required, FILTER_VALIDATE_BOOLEAN);
@endphp

<div class="tw:space-y-2">
    @if ($label)
        <label for="{{ $fieldId }}" class="ui-form-label tw:block tw:text-sm tw:font-medium">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        data-jodit-editor
        data-jodit-height="{{ (int) $height }}"
        data-jodit-placeholder="{{ $placeholder }}"
        data-jodit-readonly="{{ $isReadonly ? 'true' : 'false' }}"
        @required($isRequired)
        @readonly($isReadonly)
        {{ $attributes->merge([
            'class' => 'ui-form-control tw:block tw:min-h-[240px] tw:w-full tw:rounded-lg tw:px-3 tw:py-2 tw:text-sm',
        ]) }}
    >{{ $fieldValue }}</textarea>

    @error($name)
        <p class="tw:text-sm tw:text-rose-600">{{ $message }}</p>
    @enderror
</div>
