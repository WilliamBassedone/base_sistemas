@props([
    'title' => null,
    'description' => null,
    'columns' => [],
    'rows' => [],
    'pagination' => null,
    'editRoute' => null,
    'createRoute' => null,
    'createLabel' => 'Novo Registro',
    'searchPlaceholder' => 'Pesquisar na tabela',
    'bulkActions' => [
        'activate' => 'Ativar selecionados',
        'deactivate' => 'Inativar selecionados',
        'delete' => 'Excluir selecionados',
    ],
    'emptyMessage' => 'Nenhum registro encontrado.',
])

@pushOnce('scripts')
    @vite('Modules/UI/resources/assets/components/data-table-tw.js')
@endPushOnce

@php
    $statusClasses = [
        'success' => 'tw:bg-[var(--panel-status-success-bg)] tw:text-[var(--panel-status-success-text)]',
        'warning' => 'tw:bg-[var(--panel-status-warning-bg)] tw:text-[var(--panel-status-warning-text)]',
        'neutral' => 'tw:bg-[var(--panel-status-neutral-bg)] tw:text-[var(--panel-status-neutral-text)]',
        'danger' => 'tw:bg-[var(--panel-status-danger-bg)] tw:text-[var(--panel-status-danger-text)]',
    ];

    $bulkSelectId = 'ui-tw-bulk-' . \Illuminate\Support\Str::uuid();
    $searchId = 'ui-tw-search-' . \Illuminate\Support\Str::uuid();
@endphp

<section class="tw:border tw:border-[var(--panel-table-container-border)] tw:bg-[var(--panel-table-surface)] tw:shadow-sm" data-ui-tw-table-manager>
    @if ($title || $description || $createRoute)
        <div class="tw:flex tw:flex-wrap tw:items-start tw:justify-between tw:gap-4 tw:border-b tw:border-[var(--panel-table-section-border)] tw:px-4 tw:py-4">
            <div>
                @if ($title)
                    <h2 class="tw:text-lg tw:font-semibold tw:text-[var(--panel-table-heading-text)]">{{ $title }}</h2>
                @endif

                @if ($description)
                    <p class="tw:mt-1 tw:text-sm tw:text-[var(--panel-table-muted-text)]">{{ $description }}</p>
                @endif
            </div>

            @if ($createRoute)
                <a href="{{ route($createRoute) }}"
                    class="tw:inline-flex tw:items-center tw:justify-center tw:rounded-lg tw:bg-[var(--panel-button-primary-bg)] tw:px-4 tw:py-1.5 tw:text-sm tw:font-semibold tw:text-[var(--panel-button-primary-text)] hover:tw:bg-[var(--panel-button-primary-hover-bg)]"
                    data-turbo-frame="main" data-turbo-action="advance">
                    {{ $createLabel }}
                </a>
            @endif
        </div>
    @endif

    <div class="tw:border-b tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface-muted)] tw:px-4 tw:py-3">
        <div class="tw:grid tw:gap-4 lg:tw:grid-cols-[420px_minmax(0,1fr)]">
            <div class="tw:space-y-2">
                <label for="{{ $searchId }}" class="ui-form-label tw:text-xs tw:font-semibold tw:uppercase tw:tracking-[0.08em]">
                    Pesquisa
                </label>
                <input id="{{ $searchId }}" type="text" placeholder="{{ $searchPlaceholder }}"
                    class="ui-form-control tw:w-full tw:px-3 tw:py-1.5 tw:text-sm"
                    data-ui-tw-table-search>
            </div>

            <div class="tw:space-y-2">
                <span class="ui-form-label tw:text-xs tw:font-semibold tw:uppercase tw:tracking-[0.08em]">Filtros</span>
                <div
                    class="ui-form-control tw:flex tw:min-h-11 tw:items-center tw:border-dashed tw:px-3 tw:text-sm tw:text-[var(--panel-table-placeholder-text)]">
                    Espaço reservado para filtros adicionais.
                </div>
            </div>
        </div>
    </div>

    <div class="tw:border-b tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface)] tw:px-4 tw:py-3">
        <div class="tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-3">
            <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-3">
                <label for="{{ $bulkSelectId }}" class="tw:inline-flex tw:items-center tw:gap-2 tw:text-sm tw:font-medium tw:text-[var(--panel-table-body-text)]">
                    <input id="{{ $bulkSelectId }}" type="checkbox"
                        class="ui-form-checkbox tw:h-4 tw:w-4 tw:rounded-none"
                        data-ui-tw-select-all-toggle>
                    <span>Selecionar todos da página</span>
                </label>

                <span class="tw:text-sm tw:font-medium tw:text-[var(--panel-table-muted-text)]" data-ui-tw-selected-count>0 selecionados</span>
            </div>

            <div class="tw:flex tw:flex-wrap tw:items-center tw:gap-3">
                <select
                    class="ui-form-control tw:min-h-10 tw:min-w-[220px] tw:px-3 tw:text-sm tw:font-medium"
                    data-ui-tw-bulk-action>
                    <option value="">Ações em massa</option>
                    @foreach ($bulkActions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <button type="button"
                    class="tw:inline-flex tw:min-h-10 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:px-4 tw:text-sm tw:font-semibold tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)] disabled:tw:cursor-not-allowed disabled:tw:opacity-50"
                    data-ui-tw-apply-action disabled>
                    Aplicar
                </button>
            </div>
        </div>
    </div>

    <div class="tw:relative tw:max-h-[480px] tw:overflow-auto tw:bg-[var(--panel-table-surface)]">
        <table class="tw:min-w-full tw:border-collapse tw:text-sm">
            <thead class="tw:bg-[var(--panel-table-surface-soft)] tw:text-left tw:text-[var(--panel-table-head-text)]">
                <tr>
                    <th class="tw:sticky tw:top-0 tw:z-[2] tw:w-14 tw:border-r tw:border-b tw:border-l tw:border-[var(--panel-table-grid-border)] tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-1.5 tw:text-center tw:font-medium">
                        <input type="checkbox"
                            class="ui-form-checkbox tw:h-4 tw:w-4 tw:rounded-none"
                            data-ui-tw-select-all-table aria-label="Selecionar todos">
                    </th>
                    @foreach ($columns as $column)
                        <th class="tw:sticky tw:top-0 tw:z-[2] tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-1.5 tw:font-medium"
                            @if (!empty($column['width'])) style="width: {{ $column['width'] }};" @endif>
                            {{ $column['label'] }}
                        </th>
                    @endforeach
                    <th class="tw:sticky tw:top-0 tw:z-[2] tw:w-[130px] tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:bg-[var(--panel-table-surface-soft)] tw:px-4 tw:py-1.5 tw:text-center tw:font-medium">Ações</th>
                </tr>
            </thead>

            <tbody class="tw:text-[var(--panel-table-body-text)]">
                @forelse ($rows as $row)
                    <tr class="ui-tw-table-row" data-ui-tw-row>
                        <td class="tw:border-l tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:px-4 tw:py-1 tw:text-center">
                            <input type="checkbox"
                                class="ui-form-checkbox tw:h-4 tw:w-4 tw:rounded-none"
                                data-ui-tw-row-checkbox value="{{ $row['code'] ?? '' }}"
                                aria-label="Selecionar {{ $row['name'] ?? 'registro' }}">
                        </td>

                        @foreach ($columns as $column)
                            @php
                                $value = $row[$column['key']] ?? null;
                                $variant = $column['variant'] ?? 'text';
                            @endphp

                            <td class="tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:px-4 tw:py-1">
                                @if ($variant === 'badge' && is_array($value))
                                    @php
                                        $tone = $value['tone'] ?? 'neutral';
                                    @endphp
                                    <span class="tw:inline-flex tw:items-center tw:rounded-full tw:px-2.5 tw:py-1 tw:text-xs tw:font-medium {{ $statusClasses[$tone] ?? $statusClasses['neutral'] }}"
                                        data-ui-tw-status-badge>
                                        {{ $value['label'] ?? '-' }}
                                    </span>
                                @elseif ($variant === 'code')
                                    <span class="tw:font-mono tw:text-xs tw:font-semibold tw:text-[var(--panel-table-input-focus-border)]">
                                        {{ $value ?? '-' }}
                                    </span>
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </td>
                        @endforeach

                        <td class="tw:border-r tw:border-b tw:border-[var(--panel-table-grid-border)] tw:px-4 tw:py-1">
                            <div class="tw:flex tw:flex-wrap tw:justify-center tw:gap-2">
                                @if ($editRoute)
                                    <a href="{{ route($editRoute) }}"
                                        class="tw:inline-flex tw:h-9 tw:w-9 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]"
                                        data-turbo-frame="main" data-turbo-action="advance">
                                        <i class="fa-solid fa-pen tw:text-xs"></i>
                                    </a>
                                @endif

                                <button type="button"
                                    class="tw:inline-flex tw:h-9 tw:w-9 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-toggle-active-bg)] tw:text-[var(--panel-button-toggle-text)] hover:tw:bg-[var(--panel-button-toggle-active-hover-bg)]"
                                    data-ui-tw-status-state="active"
                                    data-ui-tw-toggle-status>
                                    <i class="fa-solid fa-power-off tw:text-xs"></i>
                                </button>

                                <button type="button"
                                    class="tw:inline-flex tw:h-9 tw:w-9 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-danger-border)] tw:bg-[var(--panel-button-danger-bg)] tw:text-[var(--panel-button-danger-text)] hover:tw:bg-[var(--panel-button-danger-hover-bg)]"
                                    data-ui-tw-delete-row>
                                    <i class="fa-solid fa-trash tw:text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 2 }}" class="tw:px-4 tw:py-8 tw:text-center tw:text-sm tw:text-[var(--panel-table-muted-text)]">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($pagination)
        <div class="tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-4 tw:border-t tw:border-[var(--panel-table-section-border)] tw:bg-[var(--panel-table-surface-muted)] tw:px-4 tw:py-4">
            <div class="tw:text-sm tw:text-[var(--panel-table-muted-text)]">
                Exibindo {{ $pagination->firstItem() ?? 0 }} a {{ $pagination->lastItem() ?? 0 }} de {{ $pagination->total() }} registros
            </div>

            @if ($pagination->lastPage() > 1)
                <nav class="tw:flex tw:flex-wrap tw:items-center tw:gap-2" aria-label="Paginacao da tabela">
                    @php
                        $startPage = max(1, $pagination->currentPage() - 1);
                        $endPage = min($pagination->lastPage(), $pagination->currentPage() + 1);
                    @endphp

                    <a href="{{ $pagination->previousPageUrl() ?: '#' }}"
                        class="tw:inline-flex tw:min-h-10 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:px-4 tw:text-sm tw:font-medium tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)] {{ $pagination->onFirstPage() ? 'tw:pointer-events-none tw:opacity-50' : '' }}"
                        @if (!$pagination->onFirstPage()) data-turbo-frame="main" data-turbo-action="advance" @endif>
                        Anterior
                    </a>

                    @if ($startPage > 1)
                        <a href="{{ $pagination->url(1) }}"
                            class="tw:inline-flex tw:h-10 tw:w-10 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-sm tw:font-medium tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]"
                            data-turbo-frame="main" data-turbo-action="advance">1</a>
                        @if ($startPage > 2)
                            <span class="tw:px-1 tw:text-[var(--panel-table-placeholder-text)]">...</span>
                        @endif
                    @endif

                    @for ($page = $startPage; $page <= $endPage; $page++)
                        <a href="{{ $pagination->url($page) }}"
                            class="tw:inline-flex tw:h-10 tw:w-10 tw:items-center tw:justify-center tw:border tw:text-sm tw:font-semibold {{ $page === $pagination->currentPage() ? 'tw:border-[var(--panel-table-pagination-active-border)] tw:bg-[var(--panel-table-pagination-active-bg)] tw:text-[var(--panel-table-pagination-active-text)]' : 'tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]' }}"
                            @if ($page !== $pagination->currentPage()) data-turbo-frame="main" data-turbo-action="advance" @endif>
                            {{ $page }}
                        </a>
                    @endfor

                    @if ($endPage < $pagination->lastPage())
                        @if ($endPage < $pagination->lastPage() - 1)
                            <span class="tw:px-1 tw:text-[var(--panel-table-placeholder-text)]">...</span>
                        @endif
                        <a href="{{ $pagination->url($pagination->lastPage()) }}"
                            class="tw:inline-flex tw:h-10 tw:w-10 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:text-sm tw:font-medium tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)]"
                            data-turbo-frame="main" data-turbo-action="advance">
                            {{ $pagination->lastPage() }}
                        </a>
                    @endif

                    <a href="{{ $pagination->nextPageUrl() ?: '#' }}"
                        class="tw:inline-flex tw:min-h-10 tw:items-center tw:justify-center tw:border tw:border-[var(--panel-button-soft-border)] tw:bg-[var(--panel-button-soft-bg)] tw:px-4 tw:text-sm tw:font-medium tw:text-[var(--panel-button-soft-text)] hover:tw:bg-[var(--panel-button-soft-hover-bg)] {{ !$pagination->hasMorePages() ? 'tw:pointer-events-none tw:opacity-50' : '' }}"
                        @if ($pagination->hasMorePages()) data-turbo-frame="main" data-turbo-action="advance" @endif>
                        Próxima
                    </a>
                </nav>
            @endif
        </div>
    @endif
</section>
