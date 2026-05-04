<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->validateMutableRequestOrigin($request);

        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()');
        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($request));

        if ($this->shouldDisableClientCache($request)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }

    protected function validateMutableRequestOrigin(Request $request): void
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return;
        }

        $origin = $this->normalizeOrigin($request->headers->get('Origin'));

        if ($origin === null && $request->headers->has('Referer')) {
            $origin = $this->normalizeOrigin($request->headers->get('Referer'));
        }

        if ($origin === null) {
            return;
        }

        abort_unless($origin === $request->getSchemeAndHttpHost(), 403, 'Origem da requisicao nao autorizada.');
    }

    protected function normalizeOrigin(?string $origin): ?string
    {
        $origin = trim((string) $origin);

        if ($origin === '' || preg_match('/[\r\n]/', $origin)) {
            return null;
        }

        $parts = parse_url($origin);

        if (! is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
            return null;
        }

        $scheme = strtolower($parts['scheme']);

        if (! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower($parts['host']);
        $port = isset($parts['port']) ? (int) $parts['port'] : null;
        $defaultPort = $scheme === 'https' ? 443 : 80;
        $portSegment = $port !== null && $port !== $defaultPort ? ':'.$port : '';

        return $scheme.'://'.$host.$portSegment;
    }

    protected function contentSecurityPolicy(Request $request): string
    {
        $viteOrigin = $this->viteDevServerOrigin();
        $scriptSrc = ["'self'", "'unsafe-inline'"];
        $styleSrc = ["'self'", "'unsafe-inline'", 'fonts.googleapis.com'];
        $fontSrc = ["'self'", 'data:', 'fonts.gstatic.com'];
        $connectSrc = ["'self'"];
        $frameSrc = ["'self'"];

        if (filter_var(config('recaptcha.enabled'), FILTER_VALIDATE_BOOLEAN)) {
            array_push($scriptSrc, 'www.google.com', 'www.gstatic.com', 'www.recaptcha.net');
            array_push($connectSrc, 'www.google.com', 'www.gstatic.com', 'www.recaptcha.net');
            array_push($frameSrc, 'www.google.com', 'www.recaptcha.net');
        }

        if ($viteOrigin !== null) {
            $scriptSrc[] = $viteOrigin;
            $styleSrc[] = $viteOrigin;
            $fontSrc[] = $viteOrigin;
            $connectSrc[] = $viteOrigin;
            $connectSrc[] = preg_replace('/^http/', 'ws', $viteOrigin);
        }

        $directives = [
            "default-src 'self'",
            'script-src '.implode(' ', $scriptSrc),
            'style-src '.implode(' ', $styleSrc),
            'font-src '.implode(' ', $fontSrc),
            "img-src 'self' data: blob:",
            'connect-src '.implode(' ', $connectSrc),
            'frame-src '.implode(' ', $frameSrc),
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        if ($request->isSecure()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }

    protected function shouldDisableClientCache(Request $request): bool
    {
        return $request->user() !== null
            || $request->routeIs('logout')
            || $request->is('dashboard')
            || $request->is('configuracoes*')
            || $request->is('conteudos*')
            || $request->is('desenvolvimento*')
            || $request->is('inventories*')
            || $request->is('uis*')
            || $request->is('testes*');
    }

    protected function viteDevServerOrigin(): ?string
    {
        if (! app()->isLocal() && ! config('app.debug')) {
            return null;
        }

        $hotFile = public_path('hot');

        if (! is_file($hotFile)) {
            return null;
        }

        $origin = $this->normalizeOrigin(file_get_contents($hotFile));

        return $origin ?: null;
    }
}
