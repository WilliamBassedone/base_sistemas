let dataTableTwLoader = null;

export const ensureDataTableTwModule = async () => {
    if (!document.querySelector('[data-ui-tw-table-manager]')) {
        return;
    }

    if (!dataTableTwLoader) {
        dataTableTwLoader = import('./index');
    }

    await dataTableTwLoader;
};
