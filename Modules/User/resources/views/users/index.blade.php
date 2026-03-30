@extends('panel::app')

@section('title', 'Usuários')
@section('page_title', 'Usuários')

@section('content')
    <div class="tw:space-y-6">
        <x-ui::data-table-tw title="Listagem de Usuários" description="Dados fictícios para iniciar o layout." :columns="$table['columns']"
            :rows="$table['rows']" :pagination="$pagination" create-route="users.create" create-label="Novo Usuário"
            show-route="users.show" edit-route="users.edit" actions-width="168px"
            :row-actions="[
                [
                    'type' => 'link',
                    'kind' => 'show',
                    'icon' => 'fa-solid fa-eye',
                    'title' => 'Visualizar usuário',
                    'classes' => 'tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]',
                ],
                [
                    'type' => 'link',
                    'kind' => 'edit',
                    'icon' => 'fa-solid fa-pen',
                    'title' => 'Editar usuário',
                    'classes' => 'tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]',
                ],
                [
                    'type' => 'button',
                    'kind' => 'toggle',
                    'icon' => 'fa-solid fa-power-off',
                    'title' => 'Ativar ou inativar usuário',
                    'classes' => 'tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-toggle-active-bg)] tw:text-[var(--panel-button-toggle-text)] hover:tw:bg-[var(--panel-button-toggle-active-hover-bg)]',
                    'attributes' => [
                        'data-ui-tw-status-state' => 'active',
                        'data-ui-tw-toggle-status' => true,
                    ],
                ],
                [
                    'type' => 'button',
                    'kind' => 'delete',
                    'icon' => 'fa-solid fa-trash',
                    'title' => 'Remover usuário',
                    'classes' => 'tw:border tw:border-[var(--panel-button-danger-border)] tw:bg-[var(--panel-button-danger-bg)] tw:text-[var(--panel-button-danger-text)] hover:tw:bg-[var(--panel-button-danger-hover-bg)]',
                    'attributes' => [
                        'data-ui-tw-delete-row' => true,
                    ],
                ],
            ]" />
    </div>
@endsection
