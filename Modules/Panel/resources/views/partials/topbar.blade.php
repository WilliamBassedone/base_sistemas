@php
    $pageTitle = trim($__env->yieldContent('page_title', 'Panel'));
@endphp

<header id="panel-topbar"
    class="tw:fixed tw:top-0 tw:right-0 tw:left-64 tw:z-10 tw:flex tw:h-16 tw:items-center tw:justify-between tw:border-b tw:border-[var(--panel-topbar-border)] tw:bg-[var(--panel-topbar-bg)] tw:px-6 tw:text-[var(--panel-topbar-text)] tw:transition-all tw:duration-200">
    <div class="tw:flex tw:items-center tw:gap-3">
        <button id="sidebar-toggle" type="button" aria-label="Recolher menu" aria-expanded="true"
            class="tw:inline-flex tw:h-9 tw:w-9 tw:cursor-pointer tw:items-center tw:justify-center tw:border tw:border-[var(--panel-topbar-toggle-border)] tw:bg-[var(--panel-topbar-toggle-bg)] tw:text-[var(--panel-topbar-toggle-text)] hover:tw:bg-[var(--panel-topbar-toggle-hover-bg)]">
            <i class="fa-solid fa-bars tw:text-base"></i>
        </button>
        <span id="panel-page-title" class="tw:text-sm tw:font-semibold tw:uppercase tw:tracking-wide">
            {{ $pageTitle }}
        </span>
    </div>

    <div class="tw:flex tw:items-center tw:gap-4">
        <span class="tw:text-sm">ROOT</span>
        <a href="#" class="tw:text-sm tw:text-[var(--panel-topbar-muted-text)] hover:tw:text-[var(--panel-topbar-text)]">Sair</a>
    </div>
</header>
