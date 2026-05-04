<!doctype html>
<html lang="pt-BR" data-app-name="{{ config('app.name', 'Laravel') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Autenticação - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --panel-table-muted-text: #64748b;
            --panel-table-surface-muted: #e2e8f0;
            --panel-table-grid-border: #94a3b8;
            --panel-body-text: #0f172a;
        }
    </style>
</head>

<body class="tw:min-h-screen tw:bg-[#eef4f8] tw:text-slate-950">
    <main
        class="tw:grid tw:min-h-screen tw:grid-cols-1 tw:overflow-hidden lg:tw:grid-cols-[minmax(0,1fr)_520px]">
        <section
            class="tw:relative tw:hidden tw:min-h-screen tw:bg-slate-950 tw:px-10 tw:py-10 tw:text-white lg:tw:flex lg:tw:flex-col lg:tw:justify-between">
            <div class="tw:absolute tw:inset-0 tw:bg-[linear-gradient(135deg,#0f172a_0%,#115e59_42%,#111827_100%)]">
            </div>
            <div class="tw:absolute tw:inset-0 tw:bg-[linear-gradient(90deg,rgba(255,255,255,0.08)_1px,transparent_1px),linear-gradient(180deg,rgba(255,255,255,0.08)_1px,transparent_1px)] tw:bg-[size:56px_56px] tw:opacity-30">
            </div>
            <div class="tw:absolute tw:inset-x-0 tw:bottom-0 tw:h-72 tw:bg-[linear-gradient(180deg,transparent,#020617)]">
            </div>

            <div class="tw:relative tw:z-[1] tw:flex tw:items-center tw:gap-3">
                <div
                    class="tw:flex tw:h-11 tw:w-11 tw:items-center tw:justify-center tw:border tw:border-white/30 tw:bg-white/10">
                    <i class="fa-solid fa-shield-halved tw:text-lg"></i>
                </div>
                <div>
                    <p class="tw:text-sm tw:font-semibold">{{ config('app.name', 'CMS') }}</p>
                    <p class="tw:text-xs tw:text-slate-300">Ambiente administrativo</p>
                </div>
            </div>

            <div class="tw:relative tw:z-[1] tw:max-w-2xl">
                <div class="tw:mb-6 tw:inline-flex tw:items-center tw:gap-2 tw:border tw:border-white/20 tw:bg-white/10 tw:px-3 tw:py-1.5 tw:text-xs tw:font-semibold tw:uppercase tw:text-cyan-100">
                    <i class="fa-solid fa-lock tw:text-[11px]"></i>
                    Acesso restrito
                </div>
                <h1 class="tw:max-w-xl tw:text-5xl tw:font-semibold tw:leading-tight">
                    Controle central com entrada protegida.
                </h1>
                <p class="tw:mt-5 tw:max-w-lg tw:text-base tw:leading-7 tw:text-slate-200">
                    Operação administrativa com credenciais centralizadas e trilha de acesso do usuário root.
                </p>
            </div>

            <div class="tw:relative tw:z-[1] tw:grid tw:grid-cols-3 tw:gap-3 tw:text-sm">
                <div class="tw:border tw:border-white/15 tw:bg-white/10 tw:p-4">
                    <i class="fa-solid fa-key tw:mb-3 tw:text-cyan-200"></i>
                    <p class="tw:font-semibold">Sessão</p>
                </div>
                <div class="tw:border tw:border-white/15 tw:bg-white/10 tw:p-4">
                    <i class="fa-solid fa-user-shield tw:mb-3 tw:text-cyan-200"></i>
                    <p class="tw:font-semibold">Root</p>
                </div>
                <div class="tw:border tw:border-white/15 tw:bg-white/10 tw:p-4">
                    <i class="fa-solid fa-table-columns tw:mb-3 tw:text-cyan-200"></i>
                    <p class="tw:font-semibold">Painel</p>
                </div>
            </div>
        </section>

        <section class="tw:flex tw:min-h-screen tw:items-center tw:justify-center tw:px-5 tw:py-8 sm:tw:px-8">
            <div class="tw:w-full tw:max-w-[420px]">
                <div class="tw:mb-8 tw:flex tw:items-center tw:gap-3 lg:tw:hidden">
                    <div class="tw:flex tw:h-10 tw:w-10 tw:items-center tw:justify-center tw:bg-slate-950 tw:text-white">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <p class="tw:text-sm tw:font-semibold">{{ config('app.name', 'CMS') }}</p>
                        <p class="tw:text-xs tw:text-slate-500">Ambiente administrativo</p>
                    </div>
                </div>

                <div class="tw:border tw:border-slate-200 tw:bg-white tw:p-6 tw:shadow-sm sm:tw:p-8">
                    <div class="tw:mb-7">
                        <p class="tw:text-xs tw:font-semibold tw:uppercase tw:text-cyan-700">Autenticação</p>
                        <h2 class="tw:mt-2 tw:text-2xl tw:font-semibold tw:text-slate-950">Entrar no painel</h2>
                    </div>

                    @if ($errors->any())
                        <div class="tw:mb-5 tw:border tw:border-rose-200 tw:bg-rose-50 tw:px-4 tw:py-3 tw:text-sm tw:text-rose-800">
                            Credenciais inválidas ou acesso não autorizado.
                        </div>
                    @endif

                    <form method="post" action="{{ route('authentication.store') }}" class="tw:space-y-5"
                        data-turbo="false">
                        @csrf

                        <div class="tw:space-y-1.5">
                            <label for="email" class="tw:text-sm tw:font-medium tw:text-slate-700">E-mail</label>
                            <div class="tw:relative">
                                <i class="fa-regular fa-envelope tw:absolute tw:left-3 tw:top-1/2 tw:-translate-y-1/2 tw:text-slate-400"></i>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                    autofocus autocomplete="username" inputmode="email"
                                    class="tw:h-11 tw:w-full tw:border tw:border-slate-300 tw:bg-white tw:pl-10 tw:pr-3 tw:text-sm tw:text-slate-950 tw:outline-none tw:transition focus:tw:border-cyan-600 focus:tw:ring-2 focus:tw:ring-cyan-100"
                                    placeholder="root@empresa.com.br">
                            </div>
                            @error('email')
                                <p class="tw:text-xs tw:text-rose-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-ui::password-input name="password" id="password" label="Senha" required
                            placeholder="Digite sua senha" autocomplete="current-password" :show-strength="false" />

                        <x-ui::recaptcha-v3 action="login" />

                        <button type="submit"
                            class="tw:flex tw:h-11 tw:w-full tw:items-center tw:justify-center tw:gap-2 tw:bg-slate-950 tw:px-4 tw:text-sm tw:font-semibold tw:text-white tw:transition hover:tw:bg-cyan-950 focus:tw:outline-none focus:tw:ring-2 focus:tw:ring-cyan-200 tw:cursor-pointer">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            Entrar
                        </button>
                    </form>
                </div>

                <p class="tw:mt-5 tw:text-center tw:text-xs tw:text-slate-500">
                    {{ now()->format('Y') }} - {{ config('app.name', 'CMS') }}
                </p>
            </div>
        </section>
    </main>
</body>

</html>
