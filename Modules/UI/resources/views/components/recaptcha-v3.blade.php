@props([
    'action',
])

@if (config('recaptcha.enabled') && filled(config('recaptcha.site_key')))
    <div data-recaptcha-v3 data-recaptcha-v3-action="{{ $action }}"
        data-recaptcha-v3-site-key="{{ config('recaptcha.site_key') }}">
        <input type="hidden" name="recaptcha_token" data-recaptcha-v3-token>
        <input type="hidden" name="recaptcha_action" value="{{ $action }}">
    </div>

    @error('recaptcha_token')
        <p class="tw:text-xs tw:text-rose-700">{{ $message }}</p>
    @enderror
@endif
