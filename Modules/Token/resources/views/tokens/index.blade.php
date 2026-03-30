@extends('panel::app')

@section('title', 'Tokens')
@section('page_title', 'Tokens')

@section('content')
    <div class="tw:space-y-6">
        <div class="tw:flex tw:items-start tw:gap-3 tw:border tw:border-amber-200 tw:bg-amber-50 tw:px-4 tw:py-3 tw:text-sm tw:text-amber-900">
            <i class="fa-solid fa-triangle-exclamation tw:mt-0.5 tw:text-amber-500"></i>
            <p>
                Não faça commit do token em repositórios públicos. Isso expõe o acesso da API e pode permitir uso indevido por terceiros.
            </p>
        </div>

        <x-ui::data-table-tw title="Tokens para acesso via API"
            description="Tokens vinculados a empresas para integrações externas e consumo de API." :columns="$table['columns']"
            :rows="$table['rows']" :pagination="$pagination" create-route="tokens.create" create-label="Novo Token"
            edit-route="tokens.edit" actions-width="208px"
            search-placeholder="Pesquisar tokens, empresas ou segredos mascarados"
            :row-actions="[
                [
                    'type' => 'link',
                    'kind' => 'edit',
                    'icon' => 'fa-solid fa-pen',
                    'title' => 'Editar token',
                    'classes' => 'tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]',
                ],
                [
                    'type' => 'button',
                    'kind' => 'reveal',
                    'icon' => 'fa-solid fa-eye',
                    'title' => 'Ver token',
                    'classes' => 'tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]',
                    'attributes' => [
                        'data-ui-tw-reveal-toggle' => true,
                    ],
                ],
                [
                    'type' => 'button',
                    'kind' => 'copy',
                    'icon' => 'fa-solid fa-copy',
                    'title' => 'Copiar token',
                    'classes' => 'tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]',
                    'attributes' => [
                        'data-ui-tw-copy-value' => true,
                    ],
                ],
                [
                    'type' => 'button',
                    'kind' => 'toggle',
                    'icon' => 'fa-solid fa-power-off',
                    'title' => 'Ativar ou inativar token',
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
                    'title' => 'Remover token',
                    'classes' => 'tw:border tw:border-[var(--panel-button-danger-border)] tw:bg-[var(--panel-button-danger-bg)] tw:text-[var(--panel-button-danger-text)] hover:tw:bg-[var(--panel-button-danger-hover-bg)]',
                    'attributes' => [
                        'data-ui-tw-delete-row' => true,
                    ],
                ],
            ]" />

        <p class="tw:text-xs tw:italic tw:text-[var(--panel-table-muted-text)]">
            A apuração de uso do mês pode sofrer pequena defasagem em cenários de alto volume. Os valores exibidos nesta tela são ilustrativos.
        </p>
    </div>
@endsection
