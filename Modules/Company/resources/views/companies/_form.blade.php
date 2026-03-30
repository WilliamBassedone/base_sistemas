@php
    $isEdit = !empty($company);
    $submitRoute = $isEdit ? route('companies.update', $company['code']) : route('companies.store');
    $title = $isEdit ? 'Edição de Empresa' : 'Cadastro de Empresa';
    $description = $isEdit
        ? 'Atualize os dados principais, contato responsável e endereço da empresa.'
        : 'Defina os dados principais, contato responsável e endereço da empresa.';
@endphp

<form action="{{ $submitRoute }}" method="POST" class="tw:bg-white tw:shadow-sm tw:space-y-6 tw:p-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="tw:flex tw:items-start tw:justify-between tw:gap-4">
        <div>
            <h2 class="tw:text-base tw:font-semibold tw:text-slate-800">{{ $title }}</h2>
            <p class="tw:text-sm tw:text-slate-500 tw:mt-1">{{ $description }}</p>
        </div>
        <a href="{{ route('companies.index') }}"
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
                <h3 class="tw:text-sm tw:font-semibold tw:text-[var(--panel-table-head-text)]">Dados da Empresa</h3>
            </div>

            <div class="tw:p-4" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div class="tw:space-y-1">
                    <label for="company_name" class="ui-form-label tw:text-sm tw:font-medium">Razão Social *</label>
                    <input id="company_name" name="company_name" type="text"
                        value="{{ old('company_name', $company['company_name'] ?? '') }}" required
                        placeholder="Ex.: Empresa Exemplo Ltda"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="trade_name" class="ui-form-label tw:text-sm tw:font-medium">Nome Fantasia *</label>
                    <input id="trade_name" name="trade_name" type="text"
                        value="{{ old('trade_name', $company['trade_name'] ?? '') }}" required
                        placeholder="Ex.: Empresa Exemplo"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="cnpj" class="ui-form-label tw:text-sm tw:font-medium">CNPJ *</label>
                    <input id="cnpj" name="cnpj" type="text" value="{{ old('cnpj', $company['cnpj'] ?? '') }}"
                        required placeholder="Ex.: 00.000.000/0001-00"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="state_registration" class="ui-form-label tw:text-sm tw:font-medium">Inscrição Estadual</label>
                    <input id="state_registration" name="state_registration" type="text"
                        value="{{ old('state_registration', $company['state_registration'] ?? '') }}"
                        placeholder="Ex.: 123.456.789.000"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="municipal_registration" class="ui-form-label tw:text-sm tw:font-medium">Inscrição Municipal</label>
                    <input id="municipal_registration" name="municipal_registration" type="text"
                        value="{{ old('municipal_registration', $company['municipal_registration'] ?? '') }}"
                        placeholder="Ex.: 998877"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="is_active" class="ui-form-label tw:text-sm tw:font-medium">Status *</label>
                    <select id="is_active" name="is_active" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="1" @selected(old('is_active', $company['is_active'] ?? '1') === '1')>Ativo</option>
                        <option value="0" @selected(old('is_active', $company['is_active'] ?? '1') === '0')>Inativo</option>
                    </select>
                </div>
            </div>
        </div>

        <div
            class="tw:border tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm">
            <div class="tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-2">
                <h3 class="tw:text-sm tw:font-semibold tw:text-[var(--panel-table-head-text)]">Contato Responsável</h3>
            </div>

            <div class="tw:p-4" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div class="tw:space-y-1">
                    <label for="responsible_name" class="ui-form-label tw:text-sm tw:font-medium">Responsável *</label>
                    <input id="responsible_name" name="responsible_name" type="text"
                        value="{{ old('responsible_name', $company['responsible_name'] ?? '') }}" required
                        placeholder="Ex.: Maria Silva"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="responsible_email" class="ui-form-label tw:text-sm tw:font-medium">E-mail do Responsável *</label>
                    <input id="responsible_email" name="responsible_email" type="email"
                        value="{{ old('responsible_email', $company['responsible_email'] ?? '') }}" required
                        placeholder="Ex.: contato@empresa.com"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <x-ui::phone-input name="phone" label="Telefone" variant="fixed" :required="true"
                    :value="$company['phone'] ?? ''" />

                <x-ui::phone-input name="mobile" label="Celular" variant="mobile" :value="$company['mobile'] ?? ''" />
            </div>
        </div>

        <div
            class="tw:border tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm">
            <div class="tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-2">
                <h3 class="tw:text-sm tw:font-semibold tw:text-[var(--panel-table-head-text)]">Endereço</h3>
            </div>

            <div class="tw:p-4" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div class="tw:space-y-1">
                    <label for="address" class="ui-form-label tw:text-sm tw:font-medium">Logradouro *</label>
                    <input id="address" name="address" type="text"
                        value="{{ old('address', $company['address'] ?? '') }}" required
                        placeholder="Rua, avenida, alameda..."
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="address_number" class="ui-form-label tw:text-sm tw:font-medium">Número *</label>
                    <input id="address_number" name="address_number" type="text"
                        value="{{ old('address_number', $company['address_number'] ?? '') }}" required
                        placeholder="Ex.: 123"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="complement" class="ui-form-label tw:text-sm tw:font-medium">Complemento</label>
                    <input id="complement" name="complement" type="text"
                        value="{{ old('complement', $company['complement'] ?? '') }}" placeholder="Ex.: Sala 42"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="neighborhood" class="ui-form-label tw:text-sm tw:font-medium">Bairro *</label>
                    <input id="neighborhood" name="neighborhood" type="text"
                        value="{{ old('neighborhood', $company['neighborhood'] ?? '') }}" required placeholder="Ex.: Centro"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="state" class="ui-form-label tw:text-sm tw:font-medium">Estado *</label>
                    <select id="state" name="state" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="">Selecione</option>
                        @foreach ($states as $stateKey => $stateLabel)
                            <option value="{{ $stateKey }}"
                                @selected(old('state', $company['state'] ?? '') === $stateKey)>{{ $stateLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tw:space-y-1">
                    <label for="city" class="ui-form-label tw:text-sm tw:font-medium">Cidade *</label>
                    <select id="city" name="city" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="">Selecione</option>
                        @foreach ($cities as $cityValue => $cityLabel)
                            <option value="{{ $cityValue }}"
                                @selected(old('city', $company['city'] ?? '') === $cityValue)>{{ $cityLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <x-ui::cep-input name="postal_code" label="CEP" :required="true" :value="$company['postal_code'] ?? ''"
                    address-target="#address" neighborhood-target="#neighborhood" city-target="#city"
                    state-target="#state" complement-target="#complement" />

                <div class="tw:space-y-1 md:tw:col-span-2">
                    <label for="notes" class="ui-form-label tw:text-sm tw:font-medium">Observações</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Informações complementares sobre a empresa"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">{{ old('notes', $company['notes'] ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </section>

    <div class="tw:flex tw:items-center tw:justify-end tw:gap-3 tw:pt-4">
        <x-ui::button type="submit" variant="success">
            {{ $isEdit ? 'Salvar Alterações' : 'Salvar Empresa' }}
        </x-ui::button>
        <x-ui::button as="a" href="{{ route('companies.index') }}" variant="danger" data-turbo-frame="main"
            data-turbo-action="advance">
            Cancelar
        </x-ui::button>
    </div>
</form>
