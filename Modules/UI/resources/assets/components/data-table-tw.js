const initUiTwDataTable = () => {
    document.querySelectorAll('[data-ui-tw-table-manager]').forEach((manager) => {
        if (manager.dataset.bound === 'true') return;
        manager.dataset.bound = 'true';

        const outsideToggle = manager.querySelector('[data-ui-tw-select-all-toggle]');
        const tableToggle = manager.querySelector('[data-ui-tw-select-all-table]');
        const actionSelect = manager.querySelector('[data-ui-tw-bulk-action]');
        const applyButton = manager.querySelector('[data-ui-tw-apply-action]');
        const countLabel = manager.querySelector('[data-ui-tw-selected-count]');
        const searchInput = manager.querySelector('[data-ui-tw-table-search]');

        if (!outsideToggle || !tableToggle || !actionSelect || !applyButton || !countLabel) {
            return;
        }

        const getRowCheckboxes = () => Array.from(manager.querySelectorAll('[data-ui-tw-row-checkbox]'));

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

        const updateRowState = (checkbox) => {
            const row = checkbox.closest('[data-ui-tw-row]');
            if (!row) return;
            row.dataset.selected = checkbox.checked ? 'true' : 'false';
        };

        const updateBulkState = () => {
            const rowCheckboxes = getRowCheckboxes().filter((checkbox) => checkbox.closest('[data-ui-tw-row]')?.style.display !== 'none');
            const checked = rowCheckboxes.filter((checkbox) => checkbox.checked);
            const checkedCount = checked.length;
            const allChecked = rowCheckboxes.length > 0 && checkedCount === rowCheckboxes.length;

            outsideToggle.checked = allChecked;
            tableToggle.checked = allChecked;
            outsideToggle.indeterminate = checkedCount > 0 && !allChecked;
            tableToggle.indeterminate = checkedCount > 0 && !allChecked;
            countLabel.textContent = `${checkedCount} selecionados`;
            applyButton.disabled = checkedCount === 0 || !actionSelect.value;

            getRowCheckboxes().forEach(updateRowState);
        };

        const bindRowCheckboxes = () => {
            getRowCheckboxes().forEach((checkbox) => {
                if (checkbox.dataset.bound === 'true') return;
                checkbox.dataset.bound = 'true';
                checkbox.addEventListener('change', updateBulkState);
            });
        };

        const bindDeleteButtons = () => {
            manager.querySelectorAll('[data-ui-tw-delete-row]').forEach((button) => {
                if (button.dataset.bound === 'true') return;
                button.dataset.bound = 'true';

                button.addEventListener('click', () => {
                    button.closest('[data-ui-tw-row]')?.remove();
                    updateBulkState();
                });
            });
        };

        const bindStatusButtons = () => {
            manager.querySelectorAll('[data-ui-tw-toggle-status]').forEach((button) => {
                if (button.dataset.bound === 'true') return;
                button.dataset.bound = 'true';

                button.addEventListener('click', () => {
                    const row = button.closest('[data-ui-tw-row]');
                    const badge = row?.querySelector('[data-ui-tw-status-badge]');
                    if (!row || !badge) return;

                    const activate = button.dataset.uiTwStatusState === 'inactive';
                    const nextStatus = activate ? statusMap.activate : statusMap.deactivate;

                    badge.textContent = nextStatus.label;
                    badge.classList.remove(...allBadgeClasses);
                    badge.classList.add(...nextStatus.badge);

                    button.dataset.uiTwStatusState = nextStatus.buttonState;
                    button.classList.remove(...allToggleButtonClasses);
                    button.classList.add(...nextStatus.button);
                });
            });
        };

        const toggleAll = (checked) => {
            getRowCheckboxes().forEach((checkbox) => {
                const row = checkbox.closest('[data-ui-tw-row]');
                if (row?.style.display === 'none') return;
                checkbox.checked = checked;
            });
            updateBulkState();
        };

        outsideToggle.addEventListener('change', () => toggleAll(outsideToggle.checked));
        tableToggle.addEventListener('change', () => toggleAll(tableToggle.checked));
        actionSelect.addEventListener('change', updateBulkState);

        searchInput?.addEventListener('input', () => {
            const term = searchInput.value.trim().toLowerCase();

            manager.querySelectorAll('[data-ui-tw-row]').forEach((row) => {
                const text = row.textContent?.toLowerCase() ?? '';
                row.style.display = !term || text.includes(term) ? '' : 'none';
            });

            updateBulkState();
        });

        applyButton.addEventListener('click', () => {
            const action = actionSelect.value;
            const selectedRows = getRowCheckboxes()
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
            getRowCheckboxes().forEach((checkbox) => {
                checkbox.checked = false;
            });
            updateBulkState();
        });

        bindRowCheckboxes();
        bindDeleteButtons();
        bindStatusButtons();
        updateBulkState();
    });
};

document.addEventListener('turbo:load', initUiTwDataTable);
document.addEventListener('turbo:frame-load', initUiTwDataTable);
document.addEventListener('DOMContentLoaded', initUiTwDataTable);
