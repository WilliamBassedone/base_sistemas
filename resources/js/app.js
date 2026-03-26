import './bootstrap';
import './elements/turbo-echo-stream-tag';
import './libs';
import { Jodit } from 'jodit';
import 'jodit/es2021/jodit.min.css';

// Controla o estado da sidebar (expandida/recolhida) e sincroniza labels/submenus.
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

    // Mantém as classes no bundle do Tailwind (evita purge em classes dinâmicas).
    const collapsedClasses = ['tw:w-16', 'tw:ml-16', 'tw:left-16', 'tw:hidden'];
    const expandedClasses = ['tw:w-64', 'tw:ml-64', 'tw:left-64'];

    toggle.dataset.bound = 'true';

    toggle.addEventListener('click', () => {
        // Alterna larguras/offsets do layout ao recolher ou expandir.
        sidebar.classList.toggle('tw:w-64');
        sidebar.classList.toggle('tw:w-16');
        shell.classList.toggle('tw:ml-64');
        shell.classList.toggle('tw:ml-16');
        topbar.classList.toggle('tw:left-64');
        topbar.classList.toggle('tw:left-16');

        const collapsed = sidebar.classList.contains('tw:w-16');
        // Esconde labels e chevrons quando a sidebar está recolhida.
        labels.forEach((label) => label.classList.toggle('tw:hidden', collapsed));
        submenuChevrons.forEach((icon) => icon.classList.toggle('tw:hidden', collapsed));
        if (collapsed) {
            // Ao recolher, fecha todos os submenus e reseta estados visuais.
            submenus.forEach((submenu) => submenu.classList.add('tw:hidden'));
            document.querySelectorAll('[data-submenu-toggle]').forEach((toggleButton) => {
                toggleButton.setAttribute('aria-expanded', 'false');
            });
            submenuChevrons.forEach((icon) => icon.classList.remove('tw:rotate-180'));
        }
        // Atualiza atributos de acessibilidade do botão principal.
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggle.setAttribute('aria-label', collapsed ? 'Expandir menu' : 'Recolher menu');
    });
};

// Reexecuta ao carregar página inteira e também em navegação Turbo.
document.addEventListener('turbo:load', initPanelSidebar);
document.addEventListener('DOMContentLoaded', initPanelSidebar);

// Controla abrir/fechar dos submenus da sidebar e rotação do chevron.
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

        // Se o link ativo está dentro do submenu, já abre por padrão.
        if (hasActiveLink) {
            submenu.classList.remove('tw:hidden');
            chevron.classList.add('tw:rotate-180');
            toggle.setAttribute('aria-expanded', 'true');
        }

        if (toggle.dataset.bound === 'true') return;
        toggle.dataset.bound = 'true';

        toggle.addEventListener('click', () => {
            // Alterna visibilidade do submenu e estado aria/chevron.
            const isOpen = !submenu.classList.contains('tw:hidden');
            submenu.classList.toggle('tw:hidden', isOpen);
            chevron.classList.toggle('tw:rotate-180', !isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        });
    });
};

document.addEventListener('turbo:load', initPanelSubmenus);
document.addEventListener('DOMContentLoaded', initPanelSubmenus);

// Implementa o "Selecionar tudo" da matriz de permissões.
const initPermissionsMatrix = () => {
    const toggleAll = document.querySelector('[data-permissions-toggle-all]');
    const items = Array.from(document.querySelectorAll('[data-permission-item]'));
    if (!toggleAll || !items.length) return;
    if (toggleAll.dataset.bound === 'true') return;

    // Mantém o checkbox geral sincronizado (marcado, desmarcado ou indeterminado).
    const updateToggleAllState = () => {
        const checkedCount = items.filter((item) => item.checked).length;
        toggleAll.checked = checkedCount === items.length;
        toggleAll.indeterminate = checkedCount > 0 && checkedCount < items.length;
    };

    toggleAll.dataset.bound = 'true';
    updateToggleAllState();

    toggleAll.addEventListener('change', () => {
        // Marca/desmarca todos os itens a partir do checkbox mestre.
        items.forEach((item) => {
            item.checked = toggleAll.checked;
        });
        updateToggleAllState();
    });

    // Recalcula estado do "Selecionar tudo" quando um item individual muda.
    items.forEach((item) => {
        item.addEventListener('change', updateToggleAllState);
    });
};

document.addEventListener('turbo:load', initPermissionsMatrix);
document.addEventListener('turbo:frame-load', initPermissionsMatrix);
document.addEventListener('DOMContentLoaded', initPermissionsMatrix);

const buildJoditButtons = () => {
    const buttons = Jodit.defaultOptions.buttons;

    if (Array.isArray(buttons)) {
        return [...buttons];
    }

    if (typeof buttons === 'string') {
        return buttons.split(',').map((button) => button.trim()).filter(Boolean);
    }

    return buttons;
};

const initJoditEditors = () => {
    const editors = document.querySelectorAll('[data-jodit-editor]');
    if (!editors.length) return;

    const fullToolbar = buildJoditButtons();

    editors.forEach((textarea) => {
        if (textarea.dataset.joditBound === 'true') return;

        const editor = Jodit.make(textarea, {
            height: Number(textarea.dataset.joditHeight || 420),
            placeholder: textarea.dataset.joditPlaceholder || '',
            readonly: textarea.dataset.joditReadonly === 'true',
            toolbarAdaptive: false,
            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: true,
            askBeforePasteHTML: false,
            askBeforePasteFromWord: false,
            defaultActionOnPaste: 'insert_clear_html',
            buttons: fullToolbar,
            buttonsMD: fullToolbar,
            buttonsSM: fullToolbar,
            buttonsXS: fullToolbar,
            removeButtons: [],
            uploader: {
                insertImageAsBase64URI: true,
            },
        });

        textarea.dataset.joditBound = 'true';
        textarea.joditInstance = editor;

        const form = textarea.closest('form');
        if (form && form.dataset.joditSyncBound !== 'true') {
            form.dataset.joditSyncBound = 'true';
            form.addEventListener('submit', () => {
                form.querySelectorAll('[data-jodit-editor]').forEach((field) => {
                    field.joditInstance?.synchronizeValues();
                });
            });
        }
    });
};

const destroyJoditEditors = () => {
    document.querySelectorAll('[data-jodit-editor]').forEach((textarea) => {
        textarea.joditInstance?.destruct();
        delete textarea.joditInstance;
        delete textarea.dataset.joditBound;
    });
};

const isPanelNavLinkActive = (link) => {
    const href = link.getAttribute('href');
    if (!href || href === '#' || href.startsWith('#')) {
        return false;
    }

    return link.pathname === window.location.pathname;
};

// Aplica estilo de "menu ativo" conforme a URL atual.
const initPanelNav = () => {
    const links = document.querySelectorAll('[data-nav-link]');
    if (!links.length) return;

    const activeLinkClasses = ['tw:bg-[var(--panel-sidebar-active-bg)]', 'tw:text-[var(--panel-sidebar-active-text)]'];
    const activeIconClasses = ['tw:text-[var(--panel-sidebar-active-icon)]'];
    const inactiveIconClasses = ['tw:text-[var(--panel-sidebar-inactive-icon)]'];

    links.forEach((link) => {
        const isActive = isPanelNavLinkActive(link);
        activeLinkClasses.forEach((className) => link.classList.toggle(className, isActive));

        // Sincroniza cor do ícone com o estado ativo do item.
        const icon = link.querySelector('[data-nav-icon]');
        if (icon) {
            activeIconClasses.forEach((className) => icon.classList.toggle(className, isActive));
            inactiveIconClasses.forEach((className) => icon.classList.toggle(className, !isActive));
        }
    });
};

document.addEventListener('turbo:load', initPanelNav);
document.addEventListener('turbo:frame-load', initPanelNav);
document.addEventListener('turbo:render', initPanelNav);
document.addEventListener('DOMContentLoaded', initPanelNav);
document.addEventListener('turbo:load', initJoditEditors);
document.addEventListener('turbo:frame-load', initJoditEditors);
document.addEventListener('DOMContentLoaded', initJoditEditors);
document.addEventListener('turbo:before-cache', destroyJoditEditors);

// Sincroniza o titulo do topo e do documento quando o Turbo carrega uma tela no frame principal.
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

document.addEventListener('turbo:load', syncPanelPageMeta);
document.addEventListener('turbo:frame-load', syncPanelPageMeta);
document.addEventListener('DOMContentLoaded', syncPanelPageMeta);
