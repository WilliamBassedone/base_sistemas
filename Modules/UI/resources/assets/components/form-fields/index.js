const digitsOnly = (value = '') => value.replace(/\D/g, '');

const applyCpfMask = (value = '') => {
    const digits = digitsOnly(value).slice(0, 11);

    return digits
        .replace(/^(\d{3})(\d)/, '$1.$2')
        .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
        .replace(/\.(\d{3})(\d)/, '.$1-$2');
};

const applyCepMask = (value = '') => {
    const digits = digitsOnly(value).slice(0, 8);
    return digits.replace(/^(\d{5})(\d)/, '$1-$2');
};

const applyPhoneMask = (value = '', variant = 'mobile') => {
    const digits = digitsOnly(value).slice(0, 11);

    if (!digits) {
        return '';
    }

    const isMobile = variant === 'mobile' || digits.length > 10;
    const limitedDigits = digits.slice(0, isMobile ? 11 : 10);

    if (isMobile) {
        return limitedDigits
            .replace(/^(\d{2})(\d)/, '($1) $2')
            .replace(/(\d{5})(\d)/, '$1-$2');
    }

    return limitedDigits
        .replace(/^(\d{2})(\d)/, '($1) $2')
        .replace(/(\d{4})(\d)/, '$1-$2');
};

const getPasswordScore = (value = '') => {
    let score = 0;

    if (value.length >= 8) score += 1;
    if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score += 1;
    if (/\d/.test(value)) score += 1;
    if (/[^A-Za-z0-9]/.test(value)) score += 1;

    return score;
};

const getStrengthConfig = (score, length) => {
    if (!length) {
        return { width: '0%', label: 'Use letras maiúsculas, minúsculas, números e símbolos.', color: 'var(--panel-table-grid-border)' };
    }

    if (score <= 1) {
        return { width: '25%', label: 'Senha fraca', color: '#dc2626' };
    }

    if (score === 2) {
        return { width: '50%', label: 'Senha média', color: '#d97706' };
    }

    if (score === 3) {
        return { width: '75%', label: 'Senha boa', color: '#2563eb' };
    }

    return { width: '100%', label: 'Senha forte', color: '#059669' };
};

const bindMaskedInputs = () => {
    document.querySelectorAll('[data-ui-cpf-input]').forEach((input) => {
        if (input.dataset.bound === 'true') return;
        input.dataset.bound = 'true';

        input.value = applyCpfMask(input.value);
        input.addEventListener('input', () => {
            input.value = applyCpfMask(input.value);
        });
    });

    document.querySelectorAll('[data-ui-phone-input]').forEach((input) => {
        if (input.dataset.bound === 'true') return;
        input.dataset.bound = 'true';

        const variant = input.dataset.phoneVariant || 'mobile';
        input.value = applyPhoneMask(input.value, variant);
        input.addEventListener('input', () => {
            input.value = applyPhoneMask(input.value, variant);
        });
    });
};

const fillTargetValue = (selector, value) => {
    if (!selector || !value) return;

    const field = document.querySelector(selector);
    if (!field) return;

    if (field.tagName === 'SELECT') {
        const option = Array.from(field.options).find((item) => item.value === value || item.text === value);
        if (option) {
            field.value = option.value;
            field.dispatchEvent(new Event('change', { bubbles: true }));
            return;
        }

        const created = new Option(value, value, true, true);
        field.add(created);
        field.dispatchEvent(new Event('change', { bubbles: true }));
        return;
    }

    field.value = value;
    field.dispatchEvent(new Event('input', { bubbles: true }));
    field.dispatchEvent(new Event('change', { bubbles: true }));
};

const bindCepInputs = () => {
    document.querySelectorAll('[data-ui-cep-input]').forEach((input) => {
        if (input.dataset.bound === 'true') return;
        input.dataset.bound = 'true';

        const feedback = input.parentElement?.querySelector('[data-ui-cep-feedback]');

        input.value = applyCepMask(input.value);

        input.addEventListener('input', () => {
            input.value = applyCepMask(input.value);
            if (feedback) feedback.textContent = '';
        });

        input.addEventListener('blur', async () => {
            const cep = digitsOnly(input.value);
            if (cep.length !== 8) return;

            if (feedback) feedback.textContent = 'Buscando endereço...';

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();

                if (data.erro) {
                    if (feedback) feedback.textContent = 'CEP não encontrado.';
                    return;
                }

                fillTargetValue(input.dataset.addressTarget, data.logradouro || '');
                fillTargetValue(input.dataset.neighborhoodTarget, data.bairro || '');
                fillTargetValue(input.dataset.cityTarget, data.localidade || '');
                fillTargetValue(input.dataset.stateTarget, data.uf || '');
                fillTargetValue(input.dataset.complementTarget, data.complemento || '');

                input.dispatchEvent(new CustomEvent('ui:cep-resolved', {
                    bubbles: true,
                    detail: data,
                }));

                if (feedback) feedback.textContent = 'Endereço preenchido automaticamente.';
            } catch {
                if (feedback) feedback.textContent = 'Não foi possível consultar o CEP agora.';
            }
        });
    });
};

const bindPasswordInputs = () => {
    document.querySelectorAll('[data-ui-password-input]').forEach((input) => {
        if (input.dataset.passwordBound === 'true') return;
        input.dataset.passwordBound = 'true';

        const wrapper = input.closest('[data-ui-password-field]');
        const toggle = wrapper?.querySelector('[data-ui-password-toggle]');
        const icon = toggle?.querySelector('i');
        const strengthContainer = wrapper?.querySelector('[data-password-strength]');
        const strengthBar = wrapper?.querySelector('[data-password-strength-bar]');
        const strengthLabel = wrapper?.querySelector('[data-password-strength-label]');

        const updateStrength = () => {
            if (!input.hasAttribute('data-password-strength-source') || !strengthBar || !strengthLabel) {
                return;
            }

            const config = getStrengthConfig(getPasswordScore(input.value), input.value.length);
            strengthBar.style.width = config.width;
            strengthBar.style.backgroundColor = config.color;
            strengthLabel.textContent = config.label;
        };

        toggle?.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            if (icon) {
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            }

            toggle.setAttribute('aria-label', isPassword ? 'Ocultar senha' : 'Mostrar senha');
        });

        input.addEventListener('input', updateStrength);

        if (strengthContainer) {
            updateStrength();
        }
    });
};

const initUiFormFields = () => {
    bindMaskedInputs();
    bindCepInputs();
    bindPasswordInputs();
};

document.addEventListener('turbo:load', initUiFormFields);
document.addEventListener('turbo:frame-load', initUiFormFields);
document.addEventListener('DOMContentLoaded', initUiFormFields);
