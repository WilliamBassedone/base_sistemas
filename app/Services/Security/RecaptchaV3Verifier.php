<?php

namespace App\Services\Security;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class RecaptchaV3Verifier
{
    public function verify(?string $token, string $action, ?string $remoteIp = null): RecaptchaVerificationResult
    {
        if (! $this->enabled()) {
            return RecaptchaVerificationResult::valid();
        }

        if (! $this->configured()) {
            return RecaptchaVerificationResult::invalid('reCAPTCHA não está configurado.');
        }

        $token = trim((string) $token);

        if ($token === '') {
            return RecaptchaVerificationResult::invalid('Token reCAPTCHA ausente.');
        }

        try {
            $response = Http::asForm()
                ->timeout((int) config('recaptcha.timeout', 5))
                ->post((string) config('recaptcha.verify_url'), array_filter([
                    'secret' => config('recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $remoteIp,
                ]));
        } catch (ConnectionException) {
            return RecaptchaVerificationResult::invalid('Falha ao validar o reCAPTCHA.');
        }

        if (! $response->ok()) {
            return RecaptchaVerificationResult::invalid('Falha ao validar o reCAPTCHA.');
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            return RecaptchaVerificationResult::invalid('Resposta inválida do reCAPTCHA.');
        }

        $score = isset($payload['score']) ? (float) $payload['score'] : null;

        if (($payload['success'] ?? false) !== true) {
            return RecaptchaVerificationResult::invalid('reCAPTCHA recusado.', $score, $payload);
        }

        if (($payload['action'] ?? null) !== $action) {
            return RecaptchaVerificationResult::invalid('Ação reCAPTCHA inválida.', $score, $payload);
        }

        $expectedHostname = config('recaptcha.expected_hostname');

        if (filled($expectedHostname) && ($payload['hostname'] ?? null) !== $expectedHostname) {
            return RecaptchaVerificationResult::invalid('Origem reCAPTCHA inválida.', $score, $payload);
        }

        if ($score !== null && $score < $this->minScore($action)) {
            return RecaptchaVerificationResult::invalid('Score reCAPTCHA abaixo do mínimo.', $score, $payload);
        }

        return RecaptchaVerificationResult::valid($score, $payload);
    }

    public function enabled(): bool
    {
        return filter_var(config('recaptcha.enabled'), FILTER_VALIDATE_BOOLEAN);
    }

    public function configured(): bool
    {
        return filled(config('recaptcha.site_key')) && filled(config('recaptcha.secret_key'));
    }

    public function minScore(string $action): float
    {
        return (float) data_get(config('recaptcha.actions', []), "{$action}.min_score", config('recaptcha.min_score', 0.5));
    }
}
