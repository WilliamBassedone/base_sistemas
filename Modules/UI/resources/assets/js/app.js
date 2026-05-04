import '../components/form-fields';
import { ensureDataTableTwModule } from '../components/data-table-tw/loader';
import { destroyJoditEditors, initJoditEditors } from '../components/jodit-editor';
import { initRecaptchaV3 } from '../components/recaptcha-v3';

document.addEventListener('turbo:load', ensureDataTableTwModule);
document.addEventListener('turbo:render', ensureDataTableTwModule);
document.addEventListener('turbo:frame-load', ensureDataTableTwModule);
document.addEventListener('DOMContentLoaded', ensureDataTableTwModule);

document.addEventListener('turbo:load', initJoditEditors);
document.addEventListener('turbo:frame-load', initJoditEditors);
document.addEventListener('DOMContentLoaded', initJoditEditors);
document.addEventListener('turbo:before-cache', destroyJoditEditors);

document.addEventListener('turbo:load', initRecaptchaV3);
document.addEventListener('turbo:frame-load', initRecaptchaV3);
document.addEventListener('DOMContentLoaded', initRecaptchaV3);
