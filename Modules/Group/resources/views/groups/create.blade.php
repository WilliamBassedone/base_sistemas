@extends('panel::app')

@section('title', 'Novo Grupo')
@section('page_title', 'Novo Grupo')

@section('content')
    <div class="tw:w-full">
        <form action="{{ route('groups.store') }}" method="POST" class="tw:bg-white tw:shadow-sm tw:space-y-6 tw:p-6">
            @csrf

            <div class="tw:flex tw:items-start tw:justify-between tw:gap-4">
                <div>
                    <h2 class="tw:text-base tw:font-semibold tw:text-slate-800">Cadastro de Grupo</h2>
                    <p class="tw:text-sm tw:text-slate-500 tw:mt-1">Defina dados, permissões e menus visíveis para este
                        grupo.
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
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                        placeholder="Ex.: Coordenadores"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="tw:space-y-1">
                    <label for="slug" class="ui-form-label tw:text-sm tw:font-medium">Slug</label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug') }}"
                        placeholder="Ex.: coordenadores"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                </div>

                <div class="md:tw:col-span-2 tw:space-y-1">
                    <label for="description" class="ui-form-label tw:text-sm tw:font-medium">Descrição</label>
                    <textarea id="description" name="description" rows="3" placeholder="Descreva o objetivo deste grupo"
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">{{ old('description') }}</textarea>
                </div>

                <div class="tw:space-y-1">
                    <label for="is_active" class="ui-form-label tw:text-sm tw:font-medium">Status *</label>
                    <select id="is_active" name="is_active" required
                        class="ui-form-control tw:w-full tw:px-3 tw:py-2 tw:text-sm tw:rounded-none tw:appearance-none">
                        <option value="1" @selected(old('is_active', '1') === '1')>Ativo</option>
                        <option value="0" @selected(old('is_active') === '0')>Inativo</option>
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
                <div class="tw:overflow-x-auto">
                    @php
                        $menus = [
                            'dashboard' => 'Dashboard',
                            'professores' => 'Professores',
                            'grupos' => 'Grupos',
                            'usuarios' => 'Usuários',
                        ];
                        $actions = [
                            'view' => 'Visualizar',
                            'create' => 'Cadastrar',
                            'update' => 'Modificar',
                            'delete' => 'Excluir',
                        ];
                        $selectedPermissions = collect(old('permissions', []));
                    @endphp
                    <table class="tw:w-full tw:min-w-[760px] tw:text-sm tw:border-collapse tw:border tw:border-slate-400">
                        <thead class="tw:bg-slate-200 tw:text-slate-800">
                            <tr>
                                <th class="tw:px-3 tw:py-2 tw:text-left tw:border tw:border-slate-400">Menu</th>
                                @foreach ($actions as $actionLabel)
                                    <th class="tw:px-3 tw:py-2 tw:text-center tw:border tw:border-slate-400">
                                        {{ $actionLabel }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="tw:text-slate-800">
                            @foreach ($menus as $menuKey => $menuLabel)
                                <tr class="odd:tw:bg-slate-100 even:tw:bg-slate-200">
                                    <td class="tw:px-3 tw:py-2 tw:font-medium tw:border tw:border-slate-400">
                                        {{ $menuLabel }}</td>
                                    @foreach ($actions as $actionKey => $actionLabel)
                                        @php($permissionValue = "menu.{$menuKey}.{$actionKey}")
                                        <td class="tw:px-3 tw:py-2 tw:text-center tw:border tw:border-slate-400">
                                            <input type="checkbox" name="permissions[]" value="{{ $permissionValue }}"
                                                data-permission-item @checked($selectedPermissions->contains($permissionValue))>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- <x-ui::jodit-editor name="content" label="Conteúdo" :value="$post->content ?? ''" placeholder="Escreva aqui..."
                height="520" /> --}}


            <div class="tw:flex tw:items-center tw:justify-end tw:gap-3 tw:pt-4">
                {{-- Botao de envio do formulario (dispara o POST para groups.store). --}}
                <x-ui::button type="submit" variant="success">
                    Salvar Grupo
                </x-ui::button>
                {{-- Link com visual de botao para voltar sem enviar o formulario. --}}
                <x-ui::button as="a" href="{{ route('groups.index') }}" variant="danger" data-turbo-frame="main"
                    data-turbo-action="advance">
                    Cancelar
                </x-ui::button>
            </div>
        </form>
    </div>
@endsection
