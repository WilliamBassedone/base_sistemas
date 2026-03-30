@php
    $isEdit = !empty($user);
    $mode = $mode ?? ($isEdit ? 'edit' : 'create');
    $isReadOnly = $mode === 'show';
    $submitRoute = $isEdit ? route('users.update', $user['code']) : route('users.store');
    $title = $isReadOnly ? 'Visualização de Usuário' : ($isEdit ? 'Edição de Usuário' : 'Cadastro de Usuário');
    $description = $isReadOnly
        ? 'Consulte os dados principais e o grupo de acesso do usuário.'
        : ($isEdit
            ? 'Atualize os dados principais e o grupo de acesso do usuário.'
            : 'Defina os dados principais e o grupo de acesso do usuário.');
@endphp

<form action="{{ $isReadOnly ? '#' : $submitRoute }}" method="POST" class="tw:bg-white tw:shadow-sm tw:space-y-6 tw:p-6">
    @if (!$isReadOnly)
        @csrf
    @endif
    @if ($isEdit && !$isReadOnly)
        @method('PUT')
    @endif

    <div class="tw:flex tw:items-start tw:justify-between tw:gap-4">
        <div>
            <h2 class="tw:text-base tw:font-semibold tw:text-slate-800">{{ $title }}</h2>
            <p class="tw:text-sm tw:text-slate-500 tw:mt-1">{{ $description }}</p>
        </div>
        <a href="{{ route('users.index') }}"
            class="tw:inline-flex tw:items-center tw:justify-center tw:gap-2 tw:bg-white tw:px-3 tw:py-2 tw:text-xs tw:font-medium tw:leading-none tw:text-slate-700 hover:tw:bg-slate-100"
            data-turbo-frame="main" data-turbo-action="advance">
            <i class="fa-solid fa-arrow-left tw:text-[11px]"></i>
            Voltar
        </a>
    </div>

    @if (!$isReadOnly && $errors->any())
        <div class="tw:bg-rose-50 tw:px-4 tw:py-3 tw:text-sm tw:text-rose-800">
            <p class="tw:font-medium">Revise os campos abaixo:</p>
            <ul class="tw:mt-2 tw:list-disc tw:list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <fieldset @disabled($isReadOnly) class="tw:space-y-4">
        <div
            class="tw:border tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm">
            <div class="tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-2">
                <h3 class="tw:text-sm tw:font-semibold tw:text-[var(--panel-table-head-text)]">Dados Cadastrais</h3>
            </div>

            <div class="tw:p-4" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div class="tw:space-y-1">
                    <label for="name" class="ui-form-label tw:text-sm tw:font-medium">Nome do Usuário *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user['name'] ?? '') }}" required
                        placeholder="Ex.: Maria da Silva"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <x-ui::cpf-input name="cpf" label="CPF" :required="true" :value="$user['cpf'] ?? ''" />

                <div class="tw:space-y-1">
                    <label for="rg" class="ui-form-label tw:text-sm tw:font-medium">RG</label>
                    <input id="rg" name="rg" type="text" value="{{ old('rg', $user['rg'] ?? '') }}"
                        placeholder="Ex.: 12.345.678-9"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="issuing_agency" class="ui-form-label tw:text-sm tw:font-medium">Órgão Expedidor</label>
                    <input id="issuing_agency" name="issuing_agency" type="text"
                        value="{{ old('issuing_agency', $user['issuing_agency'] ?? '') }}" placeholder="Ex.: SSP"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="birth_date" class="ui-form-label tw:text-sm tw:font-medium">Data de Nascimento</label>
                    <input id="birth_date" name="birth_date" type="date"
                        value="{{ old('birth_date', $user['birth_date'] ?? '') }}"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <x-ui::phone-input name="phone" label="Telefone Fixo" variant="fixed"
                    :value="$user['phone'] ?? ''" />

                <x-ui::phone-input name="mobile" label="Telefone Celular" variant="mobile" :required="true"
                    :value="$user['mobile'] ?? ''" />
            </div>
        </div>

        <div
            class="tw:border tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm">
            <div class="tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-2">
                <h3 class="tw:text-sm tw:font-semibold tw:text-[var(--panel-table-head-text)]">Endereço</h3>
            </div>

            <div class="tw:p-4" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div class="tw:space-y-1">
                    <label for="address" class="ui-form-label tw:text-sm tw:font-medium">Endereço Residencial *</label>
                    <input id="address" name="address" type="text" value="{{ old('address', $user['address'] ?? '') }}"
                        required placeholder="Rua, avenida, alameda..."
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="address_number" class="ui-form-label tw:text-sm tw:font-medium">Número *</label>
                    <input id="address_number" name="address_number" type="text"
                        value="{{ old('address_number', $user['address_number'] ?? '') }}" required placeholder="Ex.: 123"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="complement" class="ui-form-label tw:text-sm tw:font-medium">Complemento</label>
                    <input id="complement" name="complement" type="text"
                        value="{{ old('complement', $user['complement'] ?? '') }}" placeholder="Ex.: Apto 42"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="neighborhood" class="ui-form-label tw:text-sm tw:font-medium">Bairro *</label>
                    <input id="neighborhood" name="neighborhood" type="text"
                        value="{{ old('neighborhood', $user['neighborhood'] ?? '') }}" required placeholder="Ex.: Centro"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="state" class="ui-form-label tw:text-sm tw:font-medium">Estado *</label>
                    <select id="state" name="state" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="">Selecione</option>
                        @foreach ($states as $stateKey => $stateLabel)
                            <option value="{{ $stateKey }}"
                                @selected(old('state', $user['state'] ?? '') === $stateKey)>{{ $stateLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tw:space-y-1">
                    <label for="city" class="ui-form-label tw:text-sm tw:font-medium">Cidade *</label>
                    <select id="city" name="city" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="">Selecione</option>
                        @foreach ($cities as $cityLabel)
                            <option value="{{ $cityLabel }}"
                                @selected(old('city', $user['city'] ?? '') === $cityLabel)>{{ $cityLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <x-ui::cep-input name="postal_code" label="CEP" :required="true" :value="$user['postal_code'] ?? ''"
                    address-target="#address" neighborhood-target="#neighborhood" city-target="#city"
                    state-target="#state" complement-target="#complement" />
            </div>
        </div>

        <div
            class="tw:border tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm">
            <div class="tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-2">
                <h3 class="tw:text-sm tw:font-semibold tw:text-[var(--panel-table-head-text)]">Dados de Autenticação
                </h3>
            </div>

            <div class="tw:p-4" style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1rem;">
                <div class="tw:space-y-1">
                    <label for="email" class="ui-form-label tw:text-sm tw:font-medium">E-mail *</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user['email'] ?? '') }}"
                        required placeholder="Ex.: maria@empresa.com"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="group" class="ui-form-label tw:text-sm tw:font-medium">Grupo *</label>
                    <select id="group" name="group" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="">Selecione</option>
                        @foreach ($groups as $groupKey => $groupLabel)
                            <option value="{{ $groupKey }}"
                                @selected(old('group', $user['group_key'] ?? '') === $groupKey)>{{ $groupLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tw:space-y-1">
                    <label for="is_active" class="ui-form-label tw:text-sm tw:font-medium">Status *</label>
                    <select id="is_active" name="is_active" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="1" @selected(old('is_active', $user['is_active'] ?? '1') === '1')>Ativo</option>
                        <option value="0" @selected(old('is_active', $user['is_active'] ?? '1') === '0')>Inativo</option>
                    </select>
                </div>

                <x-ui::password-input name="password" label="Senha" @required(!$isEdit && !$isReadOnly) :disabled="$isReadOnly"
                    placeholder="{{ $isEdit ? 'Preencha apenas se quiser alterar a senha' : 'No minimo 8 caracteres com letras, numeros e simbolos' }}" />

                <x-ui::password-input name="password_confirmation" label="Confirmar Senha"
                    @required(!$isEdit && !$isReadOnly) :disabled="$isReadOnly" :show-strength="false" placeholder="Repita a senha"
                    autocomplete="new-password" />
            </div>
        </div>
    </fieldset>

    @if (!$isReadOnly)
        <div class="tw:flex tw:items-center tw:justify-end tw:gap-3 tw:pt-4">
            <x-ui::button type="submit" variant="success">
                {{ $isEdit ? 'Salvar Alterações' : 'Salvar Usuário' }}
            </x-ui::button>
            <x-ui::button as="a" href="{{ route('users.index') }}" variant="danger" data-turbo-frame="main"
                data-turbo-action="advance">
                Cancelar
            </x-ui::button>
        </div>
    @endif
</form>
