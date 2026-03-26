@php
    $pageTitle = trim($__env->yieldContent('page_title', 'Panel'));
    $browserTitle = trim($__env->yieldContent('title', $pageTitle));
@endphp

<div data-panel-page data-page-title="{{ $pageTitle }}" data-browser-title="{{ $browserTitle }}">
    @yield('content')
</div>
