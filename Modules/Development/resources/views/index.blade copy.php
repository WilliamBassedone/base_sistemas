@extends('panel::app')

@section('title', 'Grupos')
@section('page_title', 'Grupos')

@section('content')
    <div class="tw:space-y-6">
        @if (session('status'))
            <div
                class="tw:bg-emerald-50 tw:border tw:border-emerald-200 tw:text-emerald-800 tw:rounded-lg tw:px-4 tw:py-3 tw:text-sm">
                {{ session('status') }}
            </div>
        @endif

        <section class="tw:bg-white tw:border tw:border-slate-200 tw:rounded-lg tw:shadow-sm">
            <div class="tw:px-4 tw:py-3 tw:border-b tw:border-slate-200 tw:flex tw:items-center tw:justify-between tw:gap-3">
                <div>
                    <h2 class="tw:text-sm tw:font-semibold tw:text-slate-800">Listagem de Grupos</h2>
                    <p class="tw:text-xs tw:text-slate-500 tw:mt-1">Dados fictícios para iniciar o layout.</p>
                </div>
                <a href="{{ route('groups.create') }}"
                    class="tw:inline-flex tw:items-center tw:rounded-md tw:bg-slate-900 tw:text-white tw:text-xs tw:px-3 tw:py-2 hover:tw:bg-slate-800"
                    data-turbo-frame="main" data-turbo-action="advance">
                    Novo Grupo
                </a>
            </div>

            <div class="tw:overflow-x-auto">
                <table class="tw:w-full tw:min-w-[760px] tw:text-sm">
                    <thead class="tw:bg-slate-50 tw:text-left tw:text-slate-500">
                        <tr>
                            <th class="tw:px-4 tw:py-3 tw:font-medium">Grupo</th>
                            <th class="tw:px-4 tw:py-3 tw:font-medium">Descrição</th>
                            <th class="tw:px-4 tw:py-3 tw:font-medium">Usuários</th>
                            <th class="tw:px-4 tw:py-3 tw:font-medium">Permissões</th>
                            <th class="tw:px-4 tw:py-3 tw:font-medium">Status</th>
                            <th class="tw:px-4 tw:py-3 tw:font-medium tw:text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="tw:divide-y tw:divide-slate-100 tw:text-slate-700">
                        <tr>
                            <td class="tw:px-4 tw:py-3 tw:font-medium">Administradores</td>
                            <td class="tw:px-4 tw:py-3">Acesso completo ao sistema</td>
                            <td class="tw:px-4 tw:py-3">3</td>
                            <td class="tw:px-4 tw:py-3">25</td>
                            <td class="tw:px-4 tw:py-3">
                                <span class="tw:inline-flex tw:items-center tw:rounded-full tw:bg-emerald-100 tw:text-emerald-700 tw:text-xs tw:px-2 tw:py-0.5">Ativo</span>
                            </td>
                            <td class="tw:px-4 tw:py-3 tw:text-center">
                                <div class="tw:flex tw:justify-center tw:gap-2 tw:flex-wrap">
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-amber-500 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-amber-600">
                                        Editar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-slate-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-slate-700">
                                        Inativar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-rose-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-rose-700">
                                        Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tw:px-4 tw:py-3 tw:font-medium">Financeiro</td>
                            <td class="tw:px-4 tw:py-3">Acesso a cobranças e relatórios</td>
                            <td class="tw:px-4 tw:py-3">8</td>
                            <td class="tw:px-4 tw:py-3">12</td>
                            <td class="tw:px-4 tw:py-3">
                                <span class="tw:inline-flex tw:items-center tw:rounded-full tw:bg-emerald-100 tw:text-emerald-700 tw:text-xs tw:px-2 tw:py-0.5">Ativo</span>
                            </td>
                            <td class="tw:px-4 tw:py-3 tw:text-center">
                                <div class="tw:flex tw:justify-center tw:gap-2 tw:flex-wrap">
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-amber-500 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-amber-600">
                                        Editar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-slate-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-slate-700">
                                        Inativar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-rose-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-rose-700">
                                        Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tw:px-4 tw:py-3 tw:font-medium">Secretaria</td>
                            <td class="tw:px-4 tw:py-3">Acesso a cadastros acadêmicos</td>
                            <td class="tw:px-4 tw:py-3">14</td>
                            <td class="tw:px-4 tw:py-3">10</td>
                            <td class="tw:px-4 tw:py-3">
                                <span class="tw:inline-flex tw:items-center tw:rounded-full tw:bg-amber-100 tw:text-amber-700 tw:text-xs tw:px-2 tw:py-0.5">Revisão</span>
                            </td>
                            <td class="tw:px-4 tw:py-3 tw:text-center">
                                <div class="tw:flex tw:justify-center tw:gap-2 tw:flex-wrap">
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-amber-500 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-amber-600">
                                        Editar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-slate-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-slate-700">
                                        Inativar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-rose-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-rose-700">
                                        Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tw:px-4 tw:py-3 tw:font-medium">Suporte</td>
                            <td class="tw:px-4 tw:py-3">Acesso ao atendimento interno</td>
                            <td class="tw:px-4 tw:py-3">5</td>
                            <td class="tw:px-4 tw:py-3">7</td>
                            <td class="tw:px-4 tw:py-3">
                                <span class="tw:inline-flex tw:items-center tw:rounded-full tw:bg-slate-200 tw:text-slate-700 tw:text-xs tw:px-2 tw:py-0.5">Inativo</span>
                            </td>
                            <td class="tw:px-4 tw:py-3 tw:text-center">
                                <div class="tw:flex tw:justify-center tw:gap-2 tw:flex-wrap">
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-amber-500 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-amber-600">
                                        Editar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-emerald-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-emerald-700">
                                        Ativar
                                    </a>
                                    <a href="#"
                                        class="tw:inline-flex tw:min-w-[74px] tw:justify-center tw:items-center tw:rounded-md tw:border tw:border-transparent tw:bg-rose-600 tw:px-3 tw:py-1.5 tw:text-xs tw:font-medium tw:text-white hover:tw:bg-rose-700">
                                        Excluir
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
