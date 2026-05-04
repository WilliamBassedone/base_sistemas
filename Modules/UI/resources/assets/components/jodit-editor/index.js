let joditLoader = null;

const loadJodit = async () => {
    if (!joditLoader) {
        joditLoader = Promise.all([
            import('jodit'),
            import('jodit/es2021/jodit.min.css'),
        ]).then(([jodit]) => jodit.Jodit);
    }

    return joditLoader;
};

const buildJoditButtons = (Jodit) => {
    const buttons = Jodit.defaultOptions.buttons;

    if (Array.isArray(buttons)) {
        return [...buttons];
    }

    if (typeof buttons === 'string') {
        return buttons.split(',').map((button) => button.trim()).filter(Boolean);
    }

    return buttons;
};

export const initJoditEditors = async () => {
    const editors = document.querySelectorAll('[data-jodit-editor]');
    if (!editors.length) return;

    const Jodit = await loadJodit();
    const fullToolbar = buildJoditButtons(Jodit);

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

export const destroyJoditEditors = () => {
    document.querySelectorAll('[data-jodit-editor]').forEach((textarea) => {
        textarea.joditInstance?.destruct();
        delete textarea.joditInstance;
        delete textarea.dataset.joditBound;
    });
};
