<?php

namespace App\Http\Requests\Concerns;

use App\Services\Security\RecaptchaV3Verifier;
use Illuminate\Validation\ValidationException;

trait ValidatesRecaptchaV3
{
    protected function validateRecaptchaV3(string $action, ?string $token = null): void
    {
        $result = app(RecaptchaV3Verifier::class)->verify(
            $token ?? $this->input('recaptcha_token'),
            $action,
            $this->ip(),
        );

        if (! $result->valid) {
            throw ValidationException::withMessages([
                'recaptcha_token' => __('validation.recaptcha', ['reason' => $result->reason]),
            ]);
        }
    }
}
