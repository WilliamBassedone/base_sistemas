const isPanelNavLinkActive = (link) => {
    const href = link.getAttribute('href');
    if (!href || href === '#' || href.startsWith('#')) {
        return false;
    }

    return link.pathname === window.location.pathname;
};

const initPanelSidebar = () => {
    const toggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('panel-sidebar');
    const shell = document.getElementById('panel-shell');
    const topbar = document.getElementById('panel-topbar');
    const labels = document.querySelectorAll('[data-sidebar-label]');
    const submenuChevrons = document.querySelectorAll('[data-submenu-chevron]');
    const submenus = document.querySelectorAll('[data-submenu]');

    if (!toggle || !sidebar || !shell || !topbar || toggle.dataset.bound === 'true') {
        return;
    }

    toggle.dataset.bound = 'true';

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('tw:w-64');
        sidebar.classList.toggle('tw:w-16');
        shell.classList.toggle('tw:ml-64');
        shell.classList.toggle('tw:ml-16');
        topbar.classList.toggle('tw:left-64');
        topbar.classList.toggle('tw:left-16');

        const collapsed = sidebar.classList.contains('tw:w-16');
        labels.forEach((label) => label.classList.toggle('tw:hidden', collapsed));
        submenuChevrons.forEach((icon) => icon.classList.toggle('tw:hidden', collapsed));

        if (collapsed) {
            submenus.forEach((submenu) => submenu.classList.add('tw:hidden'));
            document.querySelectorAll('[data-submenu-toggle]').forEach((toggleButton) => {
                toggleButton.setAttribute('aria-expanded', 'false');
            });
            submenuChevrons.forEach((icon) => icon.classList.remove('tw:rotate-180'));
        }

        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggle.setAttribute('aria-label', collapsed ? 'Expandir menu' : 'Recolher menu');
    });
};

const initPanelSubmenus = () => {
    const toggles = document.querySelectorAll('[data-submenu-toggle]');
    if (!toggles.length) return;

    toggles.forEach((toggle) => {
        const submenuId = toggle.getAttribute('aria-controls');
        const submenu = submenuId ? document.getElementById(submenuId) : null;
        const chevron = toggle.querySelector('[data-submenu-chevron]');
        if (!submenu || !chevron) return;

        const hasActiveLink = Array.from(submenu.querySelectorAll('[data-nav-link]')).some((link) =>
            isPanelNavLinkActive(link)
        );

        if (hasActiveLink) {
            submenu.classList.remove('tw:hidden');
            chevron.classList.add('tw:rotate-180');
            toggle.setAttribute('aria-expanded', 'true');
        }

        if (toggle.dataset.bound === 'true') return;
        toggle.dataset.bound = 'true';

        toggle.addEventListener('click', () => {
            const isOpen = !submenu.classList.contains('tw:hidden');
            submenu.classList.toggle('tw:hidden', isOpen);
            chevron.classList.toggle('tw:rotate-180', !isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        });
    });
};

const initPanelNav = () => {
    const links = document.querySelectorAll('[data-nav-link]');
    if (!links.length) return;

    const activeLinkClasses = ['tw:bg-[var(--panel-sidebar-active-bg)]', 'tw:text-[var(--panel-sidebar-active-text)]'];
    const activeIconClasses = ['tw:text-[var(--panel-sidebar-active-icon)]'];
    const inactiveIconClasses = ['tw:text-[var(--panel-sidebar-inactive-icon)]'];

    links.forEach((link) => {
        const isActive = isPanelNavLinkActive(link);
        activeLinkClasses.forEach((className) => link.classList.toggle(className, isActive));

        const icon = link.querySelector('[data-nav-icon]');
        if (icon) {
            activeIconClasses.forEach((className) => icon.classList.toggle(className, isActive));
            inactiveIconClasses.forEach((className) => icon.classList.toggle(className, !isActive));
        }
    });
};

const syncPanelPageMeta = () => {
    const currentPage = document.querySelector('[data-panel-page]');
    if (!currentPage) return;

    const pageTitle = currentPage.dataset.pageTitle;
    const browserTitle = currentPage.dataset.browserTitle;
    const topbarTitle = document.getElementById('panel-page-title');
    const appName = document.documentElement.dataset.appName;

    if (pageTitle && topbarTitle) {
        topbarTitle.textContent = pageTitle;
    }

    if (browserTitle) {
        document.title = appName ? `${browserTitle} - ${appName}` : browserTitle;
    }
};

document.addEventListener('turbo:load', initPanelSidebar);
document.addEventListener('DOMContentLoaded', initPanelSidebar);
document.addEventListener('turbo:load', initPanelSubmenus);
document.addEventListener('DOMContentLoaded', initPanelSubmenus);
document.addEventListener('turbo:load', initPanelNav);
document.addEventListener('turbo:frame-load', initPanelNav);
document.addEventListener('turbo:render', initPanelNav);
document.addEventListener('DOMContentLoaded', initPanelNav);
document.addEventListener('turbo:load', syncPanelPageMeta);
document.addEventListener('turbo:frame-load', syncPanelPageMeta);
document.addEventListener('DOMContentLoaded', syncPanelPageMeta);
