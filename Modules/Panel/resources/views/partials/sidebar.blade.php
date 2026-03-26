@php
    $navigation = config('panel.navigation', []);
    $submenuIndex = 0;
    $resolveNavigationUrl = static function (?string $routeName, ?string $fallbackUrl = '#') {
        if ($routeName && \Illuminate\Support\Facades\Route::has($routeName)) {
            return route($routeName);
        }

        return $fallbackUrl ?? '#';
    };
@endphp

<aside id="panel-sidebar"
    class="tw:fixed tw:inset-y-0 tw:left-0 tw:flex tw:w-64 tw:flex-col tw:overflow-hidden tw:bg-[var(--panel-sidebar-bg)] tw:text-[var(--panel-sidebar-text)] tw:transition-all tw:duration-200">
    <div class="tw:flex tw:h-16 tw:items-center tw:border-b tw:border-[var(--panel-sidebar-border)] tw:px-6">
        <img src="{{ asset('images/sinpro-logo.png') }}" alt="CMS"
            class="tw:h-10 tw:w-auto tw:max-w-full tw:object-contain" />
    </div>

    <nav class="tw:flex-1 tw:py-2">
        @foreach ($navigation as $item)
            @php
                $hasChildren = filled($item['children'] ?? []);
                $routeName = $item['route'] ?? null;
                $targetUrl = $resolveNavigationUrl($routeName, $item['url'] ?? '#');
                $submenuId = $hasChildren ? 'panel-submenu-' . ++$submenuIndex : null;
            @endphp

            @if ($hasChildren)
                <button type="button"
                    class="tw:flex tw:w-full tw:cursor-pointer tw:items-center tw:justify-between tw:gap-3 tw:px-6 tw:py-3 tw:text-left tw:text-sm hover:tw:bg-[var(--panel-sidebar-hover-bg)]"
                    data-submenu-toggle aria-expanded="false" aria-controls="{{ $submenuId }}">
                    <span class="tw:flex tw:items-center tw:gap-3">
                        <i class="{{ $item['icon'] }} tw:w-5 tw:text-[var(--panel-sidebar-icon)]"></i>
                        <span data-sidebar-label>{{ $item['label'] }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down tw:text-xs tw:transition-transform tw:duration-200"
                        data-submenu-chevron></i>
                </button>

                <div id="{{ $submenuId }}" class="tw:hidden" data-submenu>
                    @foreach ($item['children'] as $child)
                        <a href="{{ $resolveNavigationUrl($child['route'] ?? null, $child['url'] ?? '#') }}"
                            class="tw:flex tw:items-center tw:gap-3 tw:px-6 tw:py-2 tw:pl-14 tw:text-sm hover:tw:bg-[var(--panel-sidebar-hover-bg)]"
                            data-nav-link data-turbo-frame="main" data-turbo-action="advance">
                            <i class="{{ $child['icon'] }} tw:w-5 tw:text-[var(--panel-sidebar-icon)]" data-nav-icon></i>
                            <span data-sidebar-label>{{ $child['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <a href="{{ $targetUrl }}"
                    class="tw:flex tw:items-center tw:gap-3 tw:px-6 tw:py-3 tw:text-sm hover:tw:bg-[var(--panel-sidebar-hover-bg)]"
                    data-nav-link data-turbo-frame="main" data-turbo-action="advance">
                    <i class="{{ $item['icon'] }} tw:w-5 tw:text-[var(--panel-sidebar-icon)]" data-nav-icon></i>
                    <span data-sidebar-label>{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>
</aside>
