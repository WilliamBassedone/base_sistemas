const initDevelopmentDataTable = () => {
    document.querySelectorAll('[data-dev-table-manager]').forEach((manager) => {
        if (manager.dataset.bound === 'true') return;

        const outsideToggle = manager.querySelector('[data-dev-select-all-toggle]');
        const tableToggle = manager.querySelector('[data-dev-select-all-table]');
        const actionSelect = manager.querySelector('[data-dev-bulk-action]');
        const applyButton = manager.querySelector('[data-dev-apply-action]');
        const countLabel = manager.querySelector('[data-dev-selected-count]');
        const searchInput = manager.querySelector('[data-dev-table-search]');

        if (!outsideToggle || !tableToggle || !actionSelect || !applyButton || !countLabel) {
            return;
        }

        manager.dataset.bound = 'true';

        const getRowCheckboxes = () => Array.from(manager.querySelectorAll('[data-dev-row-checkbox]'));

        const statusMap = {
            activate: { label: 'Ativo', tone: 'success' },
            deactivate: { label: 'Inativo', tone: 'danger' },
        };

        const updateRowHighlight = (checkbox) => {
            const row = checkbox.closest('[data-dev-row]');
            if (!row) return;
            row.classList.toggle('is-selected', checkbox.checked);
        };

        const updateBulkState = () => {
            const rowCheckboxes = getRowCheckboxes();
            if (!rowCheckboxes.length) {
                outsideToggle.checked = false;
                tableToggle.checked = false;
                outsideToggle.indeterminate = false;
                tableToggle.indeterminate = false;
                countLabel.textContent = '0 selecionados';
                applyButton.disabled = true;
                return;
            }

            const checked = rowCheckboxes.filter((checkbox) => checkbox.checked);
            const checkedCount = checked.length;
            const allChecked = checkedCount === rowCheckboxes.length;

            outsideToggle.checked = allChecked;
            tableToggle.checked = allChecked;
            outsideToggle.indeterminate = checkedCount > 0 && !allChecked;
            tableToggle.indeterminate = checkedCount > 0 && !allChecked;
            countLabel.textContent = `${checkedCount} selecionados`;
            applyButton.disabled = checkedCount === 0 || !actionSelect.value;

            rowCheckboxes.forEach(updateRowHighlight);
        };

        const bindRowCheckboxes = () => {
            getRowCheckboxes().forEach((checkbox) => {
                if (checkbox.dataset.bound === 'true') return;
                checkbox.dataset.bound = 'true';
                checkbox.addEventListener('change', updateBulkState);
            });
        };

        const bindDeleteButtons = () => {
            manager.querySelectorAll('[data-dev-delete-row]').forEach((button) => {
                if (button.dataset.bound === 'true') return;
                button.dataset.bound = 'true';

                button.addEventListener('click', () => {
                    const row = button.closest('[data-dev-row]');
                    row?.remove();
                    updateBulkState();
                });
            });
        };

        const toggleAll = (checked) => {
            getRowCheckboxes().forEach((checkbox) => {
                checkbox.checked = checked;
            });
            updateBulkState();
        };

        outsideToggle.addEventListener('change', () => toggleAll(outsideToggle.checked));
        tableToggle.addEventListener('change', () => toggleAll(tableToggle.checked));
        actionSelect.addEventListener('change', updateBulkState);

        searchInput?.addEventListener('input', () => {
            const term = searchInput.value.trim().toLowerCase();

            manager.querySelectorAll('[data-dev-row]').forEach((row) => {
                const text = row.textContent?.toLowerCase() ?? '';
                row.style.display = !term || text.includes(term) ? '' : 'none';
            });
        });

        applyButton.addEventListener('click', () => {
            const action = actionSelect.value;
            const selectedRows = getRowCheckboxes()
                .filter((checkbox) => checkbox.checked)
                .map((checkbox) => checkbox.closest('[data-dev-row]'))
                .filter(Boolean);

            if (!action || !selectedRows.length) return;

            if (action === 'delete') {
                selectedRows.forEach((row) => row.remove());
            } else {
                const nextStatus = statusMap[action];
                if (!nextStatus) return;

                selectedRows.forEach((row) => {
                    const badge = row.querySelector('[data-dev-status-badge]');
                    if (!badge) return;

                    badge.textContent = nextStatus.label;
                    badge.classList.remove('dev-badge--success', 'dev-badge--warning', 'dev-badge--neutral', 'dev-badge--danger');
                    badge.classList.add(`dev-badge--${nextStatus.tone}`);
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
        updateBulkState();
    });
};

document.addEventListener('turbo:load', initDevelopmentDataTable);
document.addEventListener('turbo:frame-load', initDevelopmentDataTable);
document.addEventListener('DOMContentLoaded', initDevelopmentDataTable);
