@php
    $pageTitle = trim($__env->yieldContent('page_title', 'Panel'));
    $browserTitle = trim($__env->yieldContent('title', $pageTitle));
    $fullTitle = $browserTitle . ' - ' . config('app.name', 'Laravel');
    $isMainFrameRequest = request()->header('Turbo-Frame') === 'main';
    $theme = config('ui_theme.panel');
@endphp

@if ($isMainFrameRequest)
    <turbo-frame id="main">
        @include('panel::partials.page')
    </turbo-frame>
@else
    <!doctype html>
    <html lang="pt-BR" data-app-name="{{ config('app.name', 'Laravel') }}">

        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>{{ $fullTitle }}</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
                crossorigin="anonymous" referrerpolicy="no-referrer" />
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            <style>
                :root {
                    --panel-body-bg: {{ $theme['body']['bg'] }};
                    --panel-body-text: {{ $theme['body']['text'] }};
                    --panel-topbar-bg: {{ $theme['topbar']['bg'] }};
                    --panel-topbar-border: {{ $theme['topbar']['border'] }};
                    --panel-topbar-text: {{ $theme['topbar']['text'] }};
                    --panel-topbar-muted-text: {{ $theme['topbar']['muted_text'] }};
                    --panel-topbar-toggle-border: {{ $theme['topbar']['toggle_border'] }};
                    --panel-topbar-toggle-bg: {{ $theme['topbar']['toggle_bg'] }};
                    --panel-topbar-toggle-text: {{ $theme['topbar']['toggle_text'] }};
                    --panel-topbar-toggle-hover-bg: {{ $theme['topbar']['toggle_hover_bg'] }};

                    --panel-sidebar-bg: {{ $theme['sidebar']['bg'] }};
                    --panel-sidebar-text: {{ $theme['sidebar']['text'] }};
                    --panel-sidebar-icon: {{ $theme['sidebar']['icon'] }};
                    --panel-sidebar-border: {{ $theme['sidebar']['border'] }};
                    --panel-sidebar-hover-bg: {{ $theme['sidebar']['hover_bg'] }};
                    --panel-sidebar-active-bg: {{ $theme['sidebar']['active_bg'] }};
                    --panel-sidebar-active-text: {{ $theme['sidebar']['active_text'] }};
                    --panel-sidebar-active-icon: {{ $theme['sidebar']['active_icon'] }};
                    --panel-sidebar-inactive-icon: {{ $theme['sidebar']['inactive_icon'] }};

                    --panel-table-surface: {{ $theme['table']['surface'] }};
                    --panel-table-surface-muted: {{ $theme['table']['surface_muted'] }};
                    --panel-table-surface-soft: {{ $theme['table']['surface_soft'] }};
                    --panel-table-heading-text: {{ $theme['table']['heading_text'] }};
                    --panel-table-head-text: {{ $theme['table']['head_text'] }};
                    --panel-table-muted-text: {{ $theme['table']['muted_text'] }};
                    --panel-table-body-text: {{ $theme['table']['body_text'] }};
                    --panel-table-placeholder-text: {{ $theme['table']['placeholder_text'] }};
                    --panel-table-container-border: {{ $theme['table']['container_border'] }};
                    --panel-table-section-border: {{ $theme['table']['section_border'] }};
                    --panel-table-grid-border: {{ $theme['table']['grid_border'] }};
                    --panel-table-control-border: {{ $theme['table']['control_border'] }};
                    --panel-table-input-bg: {{ $theme['table']['input_bg'] }};
                    --panel-table-input-focus-border: {{ $theme['table']['input_focus_border'] }};
                    --panel-table-row-hover: {{ $theme['table']['row_hover'] }};
                    --panel-table-row-selected: {{ $theme['table']['row_selected'] }};
                    --panel-table-pagination-active-bg: {{ $theme['table']['pagination_active_bg'] }};
                    --panel-table-pagination-active-border: {{ $theme['table']['pagination_active_border'] }};
                    --panel-table-pagination-active-text: {{ $theme['table']['pagination_active_text'] }};

                    --panel-form-label-text: {{ $theme['form']['label_text'] }};
                    --panel-form-text: {{ $theme['form']['text'] }};
                    --panel-form-placeholder-text: {{ $theme['form']['placeholder_text'] }};
                    --panel-form-bg: {{ $theme['form']['bg'] }};
                    --panel-form-border: {{ $theme['form']['border'] }};
                    --panel-form-focus-border: {{ $theme['form']['focus_border'] }};
                    --panel-form-focus-ring: {{ $theme['form']['focus_ring'] }};
                    --panel-form-checkbox-border: {{ $theme['form']['checkbox_border'] }};
                    --panel-form-checkbox-bg: {{ $theme['form']['checkbox_bg'] }};
                    --panel-form-checkbox-checked: {{ $theme['form']['checkbox_checked'] }};
                    --panel-form-checkbox-focus-ring: {{ $theme['form']['checkbox_focus_ring'] }};

                    --panel-status-success-bg: {{ $theme['status']['success_bg'] }};
                    --panel-status-success-text: {{ $theme['status']['success_text'] }};
                    --panel-status-warning-bg: {{ $theme['status']['warning_bg'] }};
                    --panel-status-warning-text: {{ $theme['status']['warning_text'] }};
                    --panel-status-neutral-bg: {{ $theme['status']['neutral_bg'] }};
                    --panel-status-neutral-text: {{ $theme['status']['neutral_text'] }};
                    --panel-status-danger-bg: {{ $theme['status']['danger_bg'] }};
                    --panel-status-danger-text: {{ $theme['status']['danger_text'] }};

                    --panel-button-primary-bg: {{ $theme['button']['primary_bg'] }};
                    --panel-button-primary-hover-bg: {{ $theme['button']['primary_hover_bg'] }};
                    --panel-button-primary-text: {{ $theme['button']['primary_text'] }};
                    --panel-button-soft-bg: {{ $theme['button']['soft_bg'] }};
                    --panel-button-soft-border: {{ $theme['button']['soft_border'] }};
                    --panel-button-soft-text: {{ $theme['button']['soft_text'] }};
                    --panel-button-soft-hover-bg: {{ $theme['button']['soft_hover_bg'] }};
                    --panel-button-toggle-active-bg: {{ $theme['button']['toggle_active_bg'] }};
                    --panel-button-toggle-active-hover-bg: {{ $theme['button']['toggle_active_hover_bg'] }};
                    --panel-button-toggle-inactive-bg: {{ $theme['button']['toggle_inactive_bg'] }};
                    --panel-button-toggle-inactive-hover-bg: {{ $theme['button']['toggle_inactive_hover_bg'] }};
                    --panel-button-toggle-text: {{ $theme['button']['toggle_text'] }};
                    --panel-button-danger-bg: {{ $theme['button']['danger_bg'] }};
                    --panel-button-danger-hover-bg: {{ $theme['button']['danger_hover_bg'] }};
                    --panel-button-danger-border: {{ $theme['button']['danger_border'] }};
                    --panel-button-danger-text: {{ $theme['button']['danger_text'] }};
                }

            </style>
            @stack('styles')
        </head>

        <body class="tw:bg-[var(--panel-body-bg)] tw:text-[var(--panel-body-text)]">
            <div id="panel-root" class="tw:min-h-screen tw:flex">
                @include('panel::partials.sidebar')

                <div id="panel-shell" class="tw:flex-1 tw:flex tw:flex-col tw:ml-64 tw:transition-all tw:duration-200">
                    @include('panel::partials.topbar')

                    <main id="panel-main" class="tw:flex-1 tw:p-6 tw:pt-20">
                        <turbo-frame id="main">
                            @include('panel::partials.page')
                        </turbo-frame>
                    </main>
                </div>
            </div>
            @stack('scripts')
        </body>

    </html>
@endif
