const statusMap = {
    activate: {
        label: 'Ativo',
        badge: ['tw:bg-[var(--panel-status-success-bg)]', 'tw:text-[var(--panel-status-success-text)]'],
        buttonState: 'active',
        button: ['tw:bg-[var(--panel-button-toggle-active-bg)]', 'hover:tw:bg-[var(--panel-button-toggle-active-hover-bg)]'],
    },
    deactivate: {
        label: 'Inativo',
        badge: ['tw:bg-[var(--panel-status-neutral-bg)]', 'tw:text-[var(--panel-status-neutral-text)]'],
        buttonState: 'inactive',
        button: ['tw:bg-[var(--panel-button-toggle-inactive-bg)]', 'hover:tw:bg-[var(--panel-button-toggle-inactive-hover-bg)]'],
    },
};

const allBadgeClasses = [
    'tw:bg-[var(--panel-status-success-bg)]', 'tw:text-[var(--panel-status-success-text)]',
    'tw:bg-[var(--panel-status-warning-bg)]', 'tw:text-[var(--panel-status-warning-text)]',
    'tw:bg-[var(--panel-status-neutral-bg)]', 'tw:text-[var(--panel-status-neutral-text)]',
    'tw:bg-[var(--panel-status-danger-bg)]', 'tw:text-[var(--panel-status-danger-text)]',
];

const allToggleButtonClasses = [
    'tw:bg-[var(--panel-button-toggle-active-bg)]', 'hover:tw:bg-[var(--panel-button-toggle-active-hover-bg)]',
    'tw:bg-[var(--panel-button-toggle-inactive-bg)]', 'hover:tw:bg-[var(--panel-button-toggle-inactive-hover-bg)]',
];

const getRowCheckboxes = (manager) => Array.from(manager.querySelectorAll('[data-ui-tw-row-checkbox]'));

const getVisibleRowCheckboxes = (manager) =>
    getRowCheckboxes(manager).filter((checkbox) => checkbox.closest('[data-ui-tw-row]')?.style.display !== 'none');

const updateRowState = (checkbox) => {
    const row = checkbox.closest('[data-ui-tw-row]');
    if (!row) return;
    row.dataset.selected = checkbox.checked ? 'true' : 'false';
};

const updateBulkState = (manager) => {
    const outsideToggle = manager.querySelector('[data-ui-tw-select-all-toggle]');
    const tableToggle = manager.querySelector('[data-ui-tw-select-all-table]');
    const actionSelect = manager.querySelector('[data-ui-tw-bulk-action]');
    const applyButton = manager.querySelector('[data-ui-tw-apply-action]');
    const countLabel = manager.querySelector('[data-ui-tw-selected-count]');

    if (!outsideToggle || !tableToggle || !actionSelect || !applyButton || !countLabel) {
        return;
    }

    const rowCheckboxes = getVisibleRowCheckboxes(manager);
    const checked = rowCheckboxes.filter((checkbox) => checkbox.checked);
    const checkedCount = checked.length;
    const allChecked = rowCheckboxes.length > 0 && checkedCount === rowCheckboxes.length;

    outsideToggle.checked = allChecked;
    tableToggle.checked = allChecked;
    outsideToggle.indeterminate = checkedCount > 0 && !allChecked;
    tableToggle.indeterminate = checkedCount > 0 && !allChecked;
    countLabel.textContent = `${checkedCount} selecionados`;
    applyButton.disabled = checkedCount === 0 || !actionSelect.value;

    getRowCheckboxes(manager).forEach(updateRowState);
};

const toggleAll = (manager, checked) => {
    getVisibleRowCheckboxes(manager).forEach((checkbox) => {
        checkbox.checked = checked;
    });

    updateBulkState(manager);
};

const copyTextToClipboard = async (text) => {
    if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(text);
        return;
    }

    const helper = document.createElement('textarea');
    helper.value = text;
    helper.setAttribute('readonly', '');
    helper.style.position = 'absolute';
    helper.style.left = '-9999px';
    document.body.appendChild(helper);
    helper.select();
    document.execCommand('copy');
    helper.remove();
};

const handleApplyAction = (manager) => {
    const actionSelect = manager.querySelector('[data-ui-tw-bulk-action]');
    if (!actionSelect) return;

    const action = actionSelect.value;
    const selectedRows = getRowCheckboxes(manager)
        .filter((checkbox) => checkbox.checked)
        .map((checkbox) => checkbox.closest('[data-ui-tw-row]'))
        .filter(Boolean);

    if (!action || !selectedRows.length) return;

    if (action === 'delete') {
        selectedRows.forEach((row) => row.remove());
    } else {
        const nextStatus = statusMap[action];
        if (!nextStatus) return;

        selectedRows.forEach((row) => {
            const badge = row.querySelector('[data-ui-tw-status-badge]');
            const button = row.querySelector('[data-ui-tw-toggle-status]');
            if (!badge || !button) return;

            badge.textContent = nextStatus.label;
            badge.classList.remove(...allBadgeClasses);
            badge.classList.add(...nextStatus.badge);

            button.dataset.uiTwStatusState = nextStatus.buttonState;
            button.classList.remove(...allToggleButtonClasses);
            button.classList.add(...nextStatus.button);
        });
    }

    actionSelect.value = '';
    getRowCheckboxes(manager).forEach((checkbox) => {
        checkbox.checked = false;
    });

    updateBulkState(manager);
};

const bindManagerEvents = (manager) => {
    if (manager.dataset.uiTwEventsBound === 'true') return;
    manager.dataset.uiTwEventsBound = 'true';

    manager.addEventListener('change', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement)) return;

        if (target.matches('[data-ui-tw-select-all-toggle], [data-ui-tw-select-all-table]')) {
            toggleAll(manager, target.checked);
            return;
        }

        if (target.matches('[data-ui-tw-row-checkbox], [data-ui-tw-bulk-action]')) {
            updateBulkState(manager);
        }
    });

    manager.addEventListener('input', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLInputElement)) return;
        if (!target.matches('[data-ui-tw-table-search]')) return;

        const term = target.value.trim().toLowerCase();

        manager.querySelectorAll('[data-ui-tw-row]').forEach((row) => {
            const text = row.textContent?.toLowerCase() ?? '';
            row.style.display = !term || text.includes(term) ? '' : 'none';
        });

        updateBulkState(manager);
    });

    manager.addEventListener('click', async (event) => {
        const trigger = event.target instanceof Element
            ? event.target.closest('[data-ui-tw-apply-action], [data-ui-tw-delete-row], [data-ui-tw-toggle-status], [data-ui-tw-reveal-toggle], [data-ui-tw-copy-value]')
            : null;

        if (!(trigger instanceof HTMLElement)) return;

        if (trigger.matches('[data-ui-tw-apply-action]')) {
            handleApplyAction(manager);
            return;
        }

        if (trigger.matches('[data-ui-tw-delete-row]')) {
            trigger.closest('[data-ui-tw-row]')?.remove();
            updateBulkState(manager);
            return;
        }

        if (trigger.matches('[data-ui-tw-toggle-status]')) {
            const row = trigger.closest('[data-ui-tw-row]');
            const badge = row?.querySelector('[data-ui-tw-status-badge]');
            if (!row || !badge) return;

            const activate = trigger.dataset.uiTwStatusState === 'inactive';
            const nextStatus = activate ? statusMap.activate : statusMap.deactivate;

            badge.textContent = nextStatus.label;
            badge.classList.remove(...allBadgeClasses);
            badge.classList.add(...nextStatus.badge);

            trigger.dataset.uiTwStatusState = nextStatus.buttonState;
            trigger.classList.remove(...allToggleButtonClasses);
            trigger.classList.add(...nextStatus.button);
            return;
        }

        if (trigger.matches('[data-ui-tw-reveal-toggle]')) {
            const row = trigger.closest('[data-ui-tw-row]');
            const value = row?.querySelector('[data-ui-tw-reveal-value]');
            const icon = trigger.querySelector('i');

            if (!row || !value || !icon) return;

            const masked = value.dataset.uiTwRevealMasked ?? '';
            const full = value.dataset.uiTwRevealFull ?? '';
            const isMasked = value.textContent.trim() === masked;

            value.textContent = isMasked ? full : masked;
            icon.classList.toggle('fa-eye', !isMasked);
            icon.classList.toggle('fa-eye-slash', isMasked);
            return;
        }

        if (trigger.matches('[data-ui-tw-copy-value]')) {
            const row = trigger.closest('[data-ui-tw-row]');
            const value = row?.querySelector('[data-ui-tw-reveal-value]');
            const icon = trigger.querySelector('i');
            const originalTitle = trigger.getAttribute('title') || 'Copiar';

            if (!row || !value || !icon) return;

            const full = value.dataset.uiTwRevealFull ?? value.textContent.trim();

            try {
                await copyTextToClipboard(full);
                trigger.setAttribute('title', 'Copiado');
                trigger.setAttribute('aria-label', 'Copiado');
                icon.classList.remove('fa-copy');
                icon.classList.add('fa-check');

                window.setTimeout(() => {
                    trigger.setAttribute('title', originalTitle);
                    trigger.setAttribute('aria-label', originalTitle);
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-copy');
                }, 1200);
            } catch (error) {
                trigger.setAttribute('title', 'Falha ao copiar');
                trigger.setAttribute('aria-label', 'Falha ao copiar');
            }
        }
    });
};

const initUiTwDataTable = () => {
    document.querySelectorAll('[data-ui-tw-table-manager]').forEach((manager) => {
        bindManagerEvents(manager);
        updateBulkState(manager);
    });
};

const scheduleUiTwDataTableInit = () => {
    window.requestAnimationFrame(() => {
        initUiTwDataTable();
    });
};

const observeUiTwDataTable = () => {
    if (document.body?.dataset.uiTwTableObserverBound === 'true') return;
    if (!document.body) return;

    document.body.dataset.uiTwTableObserverBound = 'true';

    const observer = new MutationObserver((mutations) => {
        const hasTableManager = mutations.some((mutation) =>
            Array.from(mutation.addedNodes).some((node) =>
                node.nodeType === Node.ELEMENT_NODE &&
                (node.matches?.('[data-ui-tw-table-manager]') || node.querySelector?.('[data-ui-tw-table-manager]'))
            )
        );

        if (hasTableManager) {
            scheduleUiTwDataTableInit();
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });
};

document.addEventListener('turbo:load', initUiTwDataTable);
document.addEventListener('turbo:frame-load', initUiTwDataTable);
document.addEventListener('turbo:render', scheduleUiTwDataTableInit);
document.addEventListener('readystatechange', scheduleUiTwDataTableInit);
document.addEventListener('DOMContentLoaded', initUiTwDataTable);

observeUiTwDataTable();
scheduleUiTwDataTableInit();
