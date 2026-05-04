<?php

namespace App\Rules;

use App\Services\Security\RecaptchaV3Verifier;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RecaptchaV3 implements ValidationRule
{
    public function __construct(
        protected string $action,
        protected ?string $remoteIp = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = app(RecaptchaV3Verifier::class)->verify(
            is_string($value) ? $value : null,
            $this->action,
            $this->remoteIp ?? request()->ip(),
        );

        if (! $result->valid) {
            $fail('validation.recaptcha')->translate([
                'reason' => $result->reason,
            ]);
        }
    }
}
