@php
    $isEdit = !empty($group);
    $submitRoute = $isEdit ? route('groups.update', $group['code']) : route('groups.store');
    $selectedPermissions = collect(old('permissions', $group['permissions_list'] ?? []));
@endphp

<form action="{{ $submitRoute }}" method="POST" class="tw:bg-white tw:shadow-sm tw:space-y-6 tw:p-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="tw:flex tw:items-start tw:justify-between tw:gap-4">
        <div>
            <h2 class="tw:text-base tw:font-semibold tw:text-slate-800">
                {{ $isEdit ? 'Edição de Grupo' : 'Cadastro de Grupo' }}
            </h2>
            <p class="tw:text-sm tw:text-slate-500 tw:mt-1">
                {{ $isEdit ? 'Atualize dados, permissões e menus visíveis para este grupo.' : 'Defina dados, permissões e menus visíveis para este grupo.' }}
            </p>
        </div>
        <a href="{{ route('groups.index') }}"
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

    <section class="tw:grid tw:grid-cols-1 md:tw:grid-cols-2 tw:gap-4">
        <div class="tw:space-y-1">
            <label for="name" class="ui-form-label tw:text-sm tw:font-medium">Nome do Grupo *</label>
            <input id="name" name="name" type="text" value="{{ old('name', $group['name'] ?? '') }}" required
                placeholder="Ex.: Coordenadores"
                class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
        </div>

        <div class="tw:space-y-1 hidden">
            <label for="slug" class="ui-form-label tw:text-sm tw:font-medium">Slug</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $group['slug'] ?? '') }}"
                placeholder="Ex.: coordenadores"
                class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
        </div>

        <div class="md:tw:col-span-2 tw:space-y-1">
            <label for="description" class="ui-form-label tw:text-sm tw:font-medium">Descrição</label>
            <textarea id="description" name="description" rows="3" placeholder="Descreva o objetivo deste grupo"
                class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">{{ old('description', $group['description'] ?? '') }}</textarea>
        </div>

        <div class="tw:space-y-1">
            <label for="is_active" class="ui-form-label tw:text-sm tw:font-medium">Status *</label>
            <select id="is_active" name="is_active" required
                class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                <option value="1" @selected(old('is_active', $group['is_active'] ?? '1') === '1')>Ativo</option>
                <option value="0" @selected(old('is_active', $group['is_active'] ?? '1') === '0')>Inativo</option>
            </select>
        </div>
    </section>

    <section class="tw:space-y-3">
        <div class="tw:flex tw:items-center tw:justify-between tw:gap-3">
            <h3 class="tw:text-sm tw:font-semibold tw:text-slate-800">Permissões por Menu (fixo)</h3>
            <label class="tw:inline-flex tw:items-center tw:gap-2 tw:text-sm tw:text-slate-800">
                <input type="checkbox" data-permissions-toggle-all>
                <span>Selecionar tudo</span>
            </label>
        </div>
        <div
            class="tw:overflow-x-auto tw:border tw:border-[var(--panel-table-container-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm">
            <table class="tw:min-w-full tw:border-collapse tw:text-sm">
                <thead class="tw:bg-[var(--panel-table-surface-soft)] tw:text-left tw:text-[var(--panel-table-head-text)]">
                    <tr>
                        <th
                            class="tw:border-l tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-1.5 tw:text-left tw:font-medium">
                            Menu</th>
                        @foreach ($actions as $actionLabel)
                            <th
                                class="tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-1.5 tw:text-center tw:font-medium">
                                {{ $actionLabel }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="tw:text-[var(--panel-table-body-text)]">
                    @foreach ($menus as $menuKey => $menuLabel)
                        <tr class="ui-tw-table-row odd:tw:bg-[var(--panel-table-row-odd)] even:tw:bg-[var(--panel-table-row-even)]">
                            <td
                                class="tw:border-l tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:px-4 tw:py-1 tw:font-medium">
                                {{ $menuLabel }}</td>
                            @foreach ($actions as $actionKey => $actionLabel)
                                @php($permissionValue = "menu.{$menuKey}.{$actionKey}")
                                <td
                                    class="tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:px-4 tw:py-1 tw:text-center">
                                    <input type="checkbox" class="ui-form-checkbox tw:h-4 tw:w-4 tw:rounded-none"
                                        name="permissions[]" value="{{ $permissionValue }}" data-permission-item
                                        @checked($selectedPermissions->contains($permissionValue))>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div class="tw:flex tw:items-center tw:justify-end tw:gap-3 tw:pt-4">
        <x-ui::button type="submit" variant="success">
            {{ $isEdit ? 'Salvar Alterações' : 'Salvar Grupo' }}
        </x-ui::button>
        <x-ui::button as="a" href="{{ route('groups.index') }}" variant="danger" data-turbo-frame="main"
            data-turbo-action="advance">
            Cancelar
        </x-ui::button>
    </div>
</form>
