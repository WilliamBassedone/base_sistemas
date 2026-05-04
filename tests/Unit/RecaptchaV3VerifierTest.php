<?php

namespace Tests\Unit;

use App\Services\Security\RecaptchaV3Verifier;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaV3VerifierTest extends TestCase
{
    public function test_it_passes_without_http_call_when_disabled(): void
    {
        Config::set('recaptcha.enabled', false);

        $result = app(RecaptchaV3Verifier::class)->verify(null, 'login', '127.0.0.1');

        $this->assertTrue($result->valid);
        Http::assertNothingSent();
    }

    public function test_it_accepts_valid_google_response(): void
    {
        $this->enableRecaptcha();

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.9,
                'action' => 'login',
                'hostname' => 'localhost',
            ]),
        ]);

        $result = app(RecaptchaV3Verifier::class)->verify('token', 'login', '127.0.0.1');

        $this->assertTrue($result->valid);
        $this->assertSame(0.9, $result->score);
    }

    public function test_it_rejects_low_score(): void
    {
        $this->enableRecaptcha();

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.2,
                'action' => 'login',
            ]),
        ]);

        $result = app(RecaptchaV3Verifier::class)->verify('token', 'login', '127.0.0.1');

        $this->assertFalse($result->valid);
        $this->assertSame('Score reCAPTCHA abaixo do mínimo.', $result->reason);
    }

    public function test_it_rejects_invalid_action(): void
    {
        $this->enableRecaptcha();

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.9,
                'action' => 'different_action',
            ]),
        ]);

        $result = app(RecaptchaV3Verifier::class)->verify('token', 'login', '127.0.0.1');

        $this->assertFalse($result->valid);
        $this->assertSame('Ação reCAPTCHA inválida.', $result->reason);
    }

    protected function enableRecaptcha(): void
    {
        Config::set('recaptcha.enabled', true);
        Config::set('recaptcha.site_key', 'site-key');
        Config::set('recaptcha.secret_key', 'secret-key');
        Config::set('recaptcha.verify_url', 'https://www.google.com/recaptcha/api/siteverify');
        Config::set('recaptcha.min_score', 0.5);
        Config::set('recaptcha.actions.login.min_score', 0.7);
    }
}
