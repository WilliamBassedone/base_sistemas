const scriptPromises = new Map();

const loadRecaptchaScript = (siteKey) => {
    if (window.grecaptcha?.execute) {
        return Promise.resolve(window.grecaptcha);
    }

    if (scriptPromises.has(siteKey)) {
        return scriptPromises.get(siteKey);
    }

    const promise = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(siteKey)}`;
        script.async = true;
        script.defer = true;
        script.onload = () => resolve(window.grecaptcha);
        script.onerror = () => reject(new Error('Não foi possível carregar o reCAPTCHA.'));
        document.head.appendChild(script);
    });

    scriptPromises.set(siteKey, promise);

    return promise;
};

const executeRecaptcha = async (siteKey, action) => {
    const grecaptcha = await loadRecaptchaScript(siteKey);

    return new Promise((resolve, reject) => {
        grecaptcha.ready(() => {
            grecaptcha.execute(siteKey, { action }).then(resolve).catch(reject);
        });
    });
};

const bindRecaptchaForm = (element) => {
    const siteKey = element.dataset.recaptchaV3SiteKey;

    if (siteKey) {
        loadRecaptchaScript(siteKey).catch(() => {});
    }

    const form = element.closest('form');

    if (!form || form.dataset.recaptchaV3Bound === 'true') {
        return;
    }

    form.dataset.recaptchaV3Bound = 'true';

    form.addEventListener('submit', async (event) => {
        if (form.dataset.recaptchaV3Verified === 'true') {
            delete form.dataset.recaptchaV3Verified;
            return;
        }

        const recaptcha = form.querySelector('[data-recaptcha-v3]');
        const tokenInput = form.querySelector('[data-recaptcha-v3-token]');
        const siteKey = recaptcha?.dataset.recaptchaV3SiteKey;
        const action = recaptcha?.dataset.recaptchaV3Action;

        if (!recaptcha || !tokenInput || !siteKey || !action) {
            return;
        }

        event.preventDefault();

        try {
            tokenInput.value = await executeRecaptcha(siteKey, action);
            form.dataset.recaptchaV3Verified = 'true';

            if (event.submitter && typeof form.requestSubmit === 'function') {
                form.requestSubmit(event.submitter);
                return;
            }

            form.requestSubmit();
        } catch {
            form.dataset.recaptchaV3Verified = 'false';
        }
    });
};

export const initRecaptchaV3 = () => {
    document.querySelectorAll('[data-recaptcha-v3]').forEach(bindRecaptchaForm);
};
