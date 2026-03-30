const initPermissionsMatrix = () => {
    const toggleAll = document.querySelector('[data-permissions-toggle-all]');
    const items = Array.from(document.querySelectorAll('[data-permission-item]'));
    if (!toggleAll || !items.length) return;
    if (toggleAll.dataset.bound === 'true') return;

    const updateToggleAllState = () => {
        const checkedCount = items.filter((item) => item.checked).length;
        toggleAll.checked = checkedCount === items.length;
        toggleAll.indeterminate = checkedCount > 0 && checkedCount < items.length;
    };

    toggleAll.dataset.bound = 'true';
    updateToggleAllState();

    toggleAll.addEventListener('change', () => {
        items.forEach((item) => {
            item.checked = toggleAll.checked;
        });
        updateToggleAllState();
    });

    items.forEach((item) => {
        item.addEventListener('change', updateToggleAllState);
    });
};

document.addEventListener('turbo:load', initPermissionsMatrix);
document.addEventListener('turbo:frame-load', initPermissionsMatrix);
document.addEventListener('DOMContentLoaded', initPermissionsMatrix);
