@php
    $isEdit = !empty($token);
    $submitRoute = $isEdit ? route('tokens.update', $token['code']) : route('tokens.store');
    $hasMonthlyLimit = old('has_monthly_limit', $token['has_monthly_limit'] ?? '0') === '1';
@endphp

<form action="{{ $submitRoute }}" method="POST" class="tw:bg-white tw:shadow-sm tw:space-y-6 tw:p-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="tw:flex tw:items-start tw:justify-between tw:gap-4">
        <div>
            <h2 class="tw:text-base tw:font-semibold tw:text-slate-800">
                {{ $isEdit ? 'Edição de Token' : 'Cadastro de Token' }}
            </h2>
            <p class="tw:mt-1 tw:text-sm tw:text-slate-500">
                {{ $isEdit ? 'Atualize nome, empresa vinculada e regras de uso deste token.' : 'Defina nome, empresa vinculada e regras de uso deste token.' }}
            </p>
        </div>
        <a href="{{ route('tokens.index') }}"
            class="tw:inline-flex tw:items-center tw:justify-center tw:gap-2 tw:bg-white tw:px-3 tw:py-2 tw:text-xs tw:font-medium tw:leading-none tw:text-slate-700 hover:tw:bg-slate-100"
            data-turbo-frame="main" data-turbo-action="advance">
            <i class="fa-solid fa-arrow-left tw:text-[11px]"></i>
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="tw:bg-rose-50 tw:px-4 tw:py-3 tw:text-sm tw:text-rose-800">
            <p class="tw:font-medium">Revise os campos abaixo:</p>
            <ul class="tw:mt-2 tw:list-disc tw:list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="tw:space-y-4">
        <div
            class="tw:border tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm">
            <div class="tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-2">
                <h3 class="tw:text-sm tw:font-semibold tw:text-[var(--panel-table-head-text)]">Dados do Token</h3>
            </div>

            <div class="tw:p-4" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div class="tw:space-y-1">
                    <label for="company_id" class="ui-form-label tw:text-sm tw:font-medium">Empresa *</label>
                    <select id="company_id" name="company_id" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="">Selecione</option>
                        @foreach ($companies as $companyId => $companyLabel)
                            <option value="{{ $companyId }}"
                                @selected(old('company_id', $token['company_id'] ?? '') === $companyId)>{{ $companyLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tw:space-y-1">
                    <label for="name" class="ui-form-label tw:text-sm tw:font-medium">Nome do Token *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $token['name'] ?? '') }}" required
                        placeholder="Ex.: ERP Principal"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="is_active" class="ui-form-label tw:text-sm tw:font-medium">Status *</label>
                    <select id="is_active" name="is_active" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="1" @selected(old('is_active', $token['is_active'] ?? '1') === '1')>Ativo</option>
                        <option value="0" @selected(old('is_active', $token['is_active'] ?? '1') === '0')>Inativo</option>
                    </select>
                </div>

                @if ($isEdit)
                    <div class="tw:space-y-1">
                        <label for="token_secret_preview" class="ui-form-label tw:text-sm tw:font-medium">Token Secreto</label>
                        <input id="token_secret_preview" type="text" value="{{ $token['token_secret'] ?? '' }}" readonly
                            class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none tw:font-mono tw:bg-slate-50">
                    </div>
                @endif

                <div class="{{ $isEdit ? 'md:tw:col-span-2' : 'tw:col-span-2' }} tw:space-y-3">
                    <label class="tw:inline-flex tw:items-center tw:gap-2 tw:text-sm tw:font-medium tw:text-[var(--panel-form-label-text)]">
                        <input id="has_monthly_limit" name="has_monthly_limit" type="checkbox" value="1"
                            class="ui-form-checkbox tw:h-4 tw:w-4 tw:rounded-none" data-token-limit-toggle
                            @checked($hasMonthlyLimit)>
                        <span>Possui limite mensal?</span>
                    </label>

                    <div data-token-limit-wrapper class="{{ $hasMonthlyLimit ? '' : 'tw:hidden' }}">
                        <div class="tw:max-w-sm tw:space-y-1">
                            <label for="monthly_limit" class="ui-form-label tw:text-sm tw:font-medium">Limite mensal</label>
                            <input id="monthly_limit" name="monthly_limit" type="number" min="0" step="0.01"
                                value="{{ old('monthly_limit', $token['monthly_limit'] ?? '') }}"
                                placeholder="Ex.: 500.00"
                                class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        </div>
                    </div>
                </div>

                <div class="tw:col-span-2 tw:space-y-1">
                    <label for="notes" class="ui-form-label tw:text-sm tw:font-medium">Observações</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Anotações sobre a finalidade do token"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">{{ old('notes', $token['notes'] ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </section>

    <div class="tw:flex tw:items-center tw:justify-end tw:gap-3 tw:pt-4">
        <x-ui::button type="submit" variant="success">
            {{ $isEdit ? 'Salvar Alterações' : 'Criar Token' }}
        </x-ui::button>
        <x-ui::button as="a" href="{{ route('tokens.index') }}" variant="danger" data-turbo-frame="main"
            data-turbo-action="advance">
            Cancelar
        </x-ui::button>
    </div>
</form>

@push('scripts')
    <script>
        window.initializeTokenLimitToggle = window.initializeTokenLimitToggle || function() {
            const toggle = document.querySelector('[data-token-limit-toggle]');
            const wrapper = document.querySelector('[data-token-limit-wrapper]');

            if (!toggle || !wrapper) {
                return;
            }

            const sync = () => {
                wrapper.classList.toggle('tw:hidden', !toggle.checked);
            };

            if (toggle.dataset.bound === '1') {
                sync();
                return;
            }

            toggle.dataset.bound = '1';
            toggle.addEventListener('change', sync);
            sync();
        };

        document.addEventListener('DOMContentLoaded', window.initializeTokenLimitToggle);
        document.addEventListener('turbo:load', window.initializeTokenLimitToggle);
        document.addEventListener('turbo:frame-load', window.initializeTokenLimitToggle);
    </script>
@endpush
