@props([
    'columns' => [],
    'rows' => [],
    'pagination' => null,
    'editRoute' => null,
    'searchPlaceholder' => 'Pesquisar na tabela',
    'bulkActions' => [
        'activate' => 'Ativar selecionados',
        'deactivate' => 'Inativar selecionados',
        'delete' => 'Excluir selecionados',
    ],
    'emptyMessage' => 'Nenhum registro foi enviado pelo backend.',
])

@pushOnce('styles')
    @vite('Modules/UI/resources/assets/components/data-table.css')
@endPushOnce

@pushOnce('scripts')
    @vite('Modules/UI/resources/assets/components/data-table.js')
@endPushOnce

<div class="dev-data-table" data-dev-table-manager>
    <div class="dev-filterbar">
        <div class="dev-filterbar__search">
            <label class="dev-field-label" for="dev-table-search">Pesquisa</label>
            <input id="dev-table-search" type="text" class="dev-input" placeholder="{{ $searchPlaceholder }}"
                data-dev-table-search>
        </div>

        <div class="dev-filterbar__filters">
            <span class="dev-field-label">Filtros</span>
            <div class="dev-filterbar__empty">
                Espaco reservado para filtros adicionais.
            </div>
        </div>
    </div>

    <div class="dev-bulkbar">
        <div class="dev-bulkbar__selection">
            <label class="dev-check">
                <input type="checkbox" data-dev-select-all-toggle>
                <span>Selecionar todos da pagina</span>
            </label>
            <span class="dev-bulkbar__count" data-dev-selected-count>0 selecionados</span>
        </div>

        <div class="dev-bulkbar__actions">
            <select class="dev-select" data-dev-bulk-action>
                <option value="">Acoes em massa</option>
                @foreach ($bulkActions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>

            <button type="button" class="dev-btn dev-btn--bulk" data-dev-apply-action disabled>
                Aplicar
            </button>
        </div>
    </div>

    <div class="dev-table-shell">
        <div class="dev-table-scroll">
            <table class="dev-table">
                <thead>
                    <tr>
                        <th class="dev-table__checkbox">
                            <input type="checkbox" data-dev-select-all-table aria-label="Selecionar todos">
                        </th>
                        @foreach ($columns as $column)
                            <th style="width: {{ $column['width'] ?? 'auto' }};">
                                {{ $column['label'] }}
                            </th>
                        @endforeach
                        <th class="dev-table__actions">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr data-dev-row>
                            <td class="dev-table__checkbox">
                                <input type="checkbox" data-dev-row-checkbox value="{{ $row['code'] ?? '' }}"
                                    aria-label="Selecionar {{ $row['name'] ?? 'registro' }}">
                            </td>

                            @foreach ($columns as $column)
                                @php
                                    $value = $row[$column['key']] ?? null;
                                    $variant = $column['variant'] ?? 'text';
                                @endphp

                                <td>
                                    @if ($variant === 'badge' && is_array($value))
                                        <span class="dev-badge dev-badge--{{ $value['tone'] ?? 'neutral' }}"
                                            data-dev-status-badge>
                                            {{ $value['label'] ?? '-' }}
                                        </span>
                                    @elseif ($variant === 'code')
                                        <span class="dev-cell-code">{{ $value ?? '-' }}</span>
                                    @else
                                        {{ $value ?? '-' }}
                                    @endif
                                </td>
                            @endforeach

                            <td class="dev-table__actions">
                                <div class="dev-row-actions">
                                    @if ($editRoute)
                                        <a href="{{ route($editRoute) }}" class="dev-icon-btn" data-turbo-frame="main"
                                            data-turbo-action="advance" aria-label="Editar {{ $row['name'] ?? 'registro' }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    @endif

                                    <button type="button" class="dev-icon-btn dev-icon-btn--danger"
                                        data-dev-delete-row aria-label="Excluir {{ $row['name'] ?? 'registro' }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 2 }}" class="dev-empty">
                                {{ $emptyMessage }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($pagination)
        <div class="dev-pagination-shell">
            <div class="dev-pagination-summary">
                Exibindo {{ $pagination->firstItem() ?? 0 }} a {{ $pagination->lastItem() ?? 0 }} de
                {{ $pagination->total() }} componentes
            </div>

            @if ($pagination->lastPage() > 1)
                <nav class="dev-pagination" aria-label="Paginacao da tabela">
                    @php
                        $startPage = max(1, $pagination->currentPage() - 1);
                        $endPage = min($pagination->lastPage(), $pagination->currentPage() + 1);
                    @endphp

                    <a href="{{ $pagination->previousPageUrl() ?: '#' }}"
                        class="dev-page-link{{ $pagination->onFirstPage() ? ' is-disabled' : '' }}"
                        @if ($pagination->onFirstPage()) aria-disabled="true" tabindex="-1"
                        @else
                            data-turbo-frame="main" data-turbo-action="advance" @endif>
                        <i class="fa-solid fa-chevron-left"></i>
                        Anterior
                    </a>

                    <div class="dev-page-list">
                        @if ($startPage > 1)
                            <a href="{{ $pagination->url(1) }}" class="dev-page-number" data-turbo-frame="main"
                                data-turbo-action="advance">1</a>
                            @if ($startPage > 2)
                                <span class="dev-page-dots">...</span>
                            @endif
                        @endif

                        @for ($page = $startPage; $page <= $endPage; $page++)
                            <a href="{{ $pagination->url($page) }}"
                                class="dev-page-number{{ $page === $pagination->currentPage() ? ' is-active' : '' }}"
                                @if ($page !== $pagination->currentPage()) data-turbo-frame="main" data-turbo-action="advance" @endif>
                                {{ $page }}
                            </a>
                        @endfor

                        @if ($endPage < $pagination->lastPage())
                            @if ($endPage < $pagination->lastPage() - 1)
                                <span class="dev-page-dots">...</span>
                            @endif
                            <a href="{{ $pagination->url($pagination->lastPage()) }}" class="dev-page-number"
                                data-turbo-frame="main" data-turbo-action="advance">
                                {{ $pagination->lastPage() }}
                            </a>
                        @endif
                    </div>

                    <a href="{{ $pagination->nextPageUrl() ?: '#' }}"
                        class="dev-page-link{{ $pagination->hasMorePages() ? '' : ' is-disabled' }}"
                        @if (!$pagination->hasMorePages()) aria-disabled="true" tabindex="-1"
                        @else
                            data-turbo-frame="main" data-turbo-action="advance" @endif>
                        Proxima
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </nav>
            @endif
        </div>
    @endif
</div>
