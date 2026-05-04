<?php

namespace App\Services\Security;

class RecaptchaVerificationResult
{
    public function __construct(
        public readonly bool $valid,
        public readonly string $reason = 'OK',
        public readonly ?float $score = null,
        public readonly array $payload = [],
    ) {}

    public static function valid(?float $score = null, array $payload = []): self
    {
        return new self(true, 'OK', $score, $payload);
    }

    public static function invalid(string $reason, ?float $score = null, array $payload = []): self
    {
        return new self(false, $reason, $score, $payload);
    }
}
