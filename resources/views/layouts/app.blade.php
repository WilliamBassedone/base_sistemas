<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Turbo Test</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div style="padding: 2rem; font-family: sans-serif;">
        <nav style="margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
            <a href="/" style="margin-right: 15px;">Home</a>
            <a href="/sobre">Sobre (Teste de Reload)</a>
            <a href="/blog/turbo" style="margin-left: 15px;">Blog Turbo (Module)</a>
            <a href="/novo/turbo" style="margin-left: 15px;">Novo Turbo (Module)</a>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
