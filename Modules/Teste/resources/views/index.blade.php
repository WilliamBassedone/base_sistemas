<turbo-frame id="main">
    <section class="tw:space-y-6">
        <header class="tw:flex tw:items-center tw:justify-between">
            <div>
                <h1 class="tw:text-2xl tw:font-semibold tw:text-slate-900">Configurações</h1>
                <p class="tw:text-sm tw:text-slate-600">Conteúdo vindo do módulo Teste dentro do Dashboard.</p>
            </div>
            <button
                class="tw:inline-flex tw:items-center tw:rounded-md tw:bg-slate-900 tw:text-white tw:text-sm tw:px-4 tw:py-2 hover:tw:bg-slate-800">
                Salvar
            </button>
        </header>

        <div class="tw:grid tw:grid-cols-1 lg:tw:grid-cols-2 tw:gap-6">
            <div class="tw:bg-white tw:rounded-lg tw:border tw:border-slate-200 tw:shadow-sm">
                <div class="tw:px-4 tw:py-3 tw:border-b tw:border-slate-200">
                    <h2 class="tw:text-sm tw:font-semibold tw:text-slate-700">Perfil</h2>
                </div>
                <div class="tw:p-4 tw:space-y-4">
                    <div>
                        <label class="tw:block tw:text-xs tw:uppercase tw:tracking-wide tw:text-slate-500">Nome</label>
                        <input type="text"
                            class="tw:mt-2 tw:w-full tw:rounded-md tw:border tw:border-slate-200 tw:px-3 tw:py-2 tw:text-sm"
                            value="Administrador" />
                    </div>
                    <div>
                        <label class="tw:block tw:text-xs tw:uppercase tw:tracking-wide tw:text-slate-500">Email</label>
                        <input type="email"
                            class="tw:mt-2 tw:w-full tw:rounded-md tw:border tw:border-slate-200 tw:px-3 tw:py-2 tw:text-sm"
                            value="root@exemplo.com" />
                    </div>
                </div>
            </div>

            <div class="tw:bg-white tw:rounded-lg tw:border tw:border-slate-200 tw:shadow-sm">
                <div class="tw:px-4 tw:py-3 tw:border-b tw:border-slate-200">
                    <h2 class="tw:text-sm tw:font-semibold tw:text-slate-700">Preferências</h2>
                </div>
                <div class="tw:p-4 tw:space-y-4">
                    <label class="tw:flex tw:items-center tw:gap-3">
                        <input type="checkbox" checked class="tw:h-4 tw:w-4" />
                        <span class="tw:text-sm tw:text-slate-700">Receber notificações por email</span>
                    </label>
                    <label class="tw:flex tw:items-center tw:gap-3">
                        <input type="checkbox" class="tw:h-4 tw:w-4" />
                        <span class="tw:text-sm tw:text-slate-700">Ativar modo compacto</span>
                    </label>
                    <label class="tw:flex tw:items-center tw:gap-3">
                        <input type="checkbox" checked class="tw:h-4 tw:w-4" />
                        <span class="tw:text-sm tw:text-slate-700">Avisar sobre atualizações</span>
                    </label>
                </div>
            </div>
        </div>
    </section>

</turbo-frame>
