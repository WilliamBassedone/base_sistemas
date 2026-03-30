import '../components/form-fields';
import { Jodit } from 'jodit';
import 'jodit/es2021/jodit.min.css';

let dataTableTwLoader = null;

const ensureUiDataTableModule = async () => {
    if (!document.querySelector('[data-ui-tw-table-manager]')) {
        return;
    }

    if (!dataTableTwLoader) {
        dataTableTwLoader = import('../components/data-table-tw');
    }

    await dataTableTwLoader;
};

document.addEventListener('turbo:load', ensureUiDataTableModule);
document.addEventListener('turbo:render', ensureUiDataTableModule);
document.addEventListener('turbo:frame-load', ensureUiDataTableModule);
document.addEventListener('DOMContentLoaded', ensureUiDataTableModule);

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

document.addEventListener('turbo:load', initJoditEditors);
document.addEventListener('turbo:frame-load', initJoditEditors);
document.addEventListener('DOMContentLoaded', initJoditEditors);
document.addEventListener('turbo:before-cache', destroyJoditEditors);
