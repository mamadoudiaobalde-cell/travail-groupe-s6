// Système de validation des formulaires
class FormValidator {
    constructor(form) {
        this.form = form;
        this.rules = {};
        this.errors = {};
        this.isValid = true;
        this.init();
    }
    
    init() {
        // Récupérer les règles de validation depuis les attributs data
        const inputs = this.form.querySelectorAll('[data-validate]');
        inputs.forEach(input => {
            const rules = input.dataset.validate.split('|');
            this.rules[input.name] = rules.map(rule => {
                const [name, param] = rule.split(':');
                return { name, param };
            });
            
            // Ajouter les événements de validation en temps réel
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => {
                if (input.classList.contains('error')) {
                    this.validateField(input);
                }
            });
        });
    }
    
    validateField(input) {
        const rules = this.rules[input.name] || [];
        let fieldErrors = [];
        
        rules.forEach(rule => {
            const error = this.validateRule(input.value, rule);
            if (error) {
                fieldErrors.push(error);
            }
        });
        
        if (fieldErrors.length > 0) {
            input.classList.add('error');
            this.errors[input.name] = fieldErrors[0];
            this.showError(input, fieldErrors[0]);
            return false;
        } else {
            input.classList.remove('error');
            delete this.errors[input.name];
            this.hideError(input);
            return true;
        }
    }
    
    validateRule(value, rule) {
        switch(rule.name) {
            case 'required':
                if (!value || value.trim() === '') {
                    return 'Ce champ est obligatoire';
                }
                break;
            case 'email':
                if (value && !validateEmail(value)) {
                    return 'Veuillez entrer un email valide';
                }
                break;
            case 'min':
                if (value && value.length < parseInt(rule.param)) {
                    return 'Minimum ${rule.param} caractères';
                }
                break;
            case 'max':
                if (value && value.length > parseInt(rule.param)) {
                    return 'Maximum ${rule.param} caractères';
                }
                break;
            case 'min_value':
                if (value && parseFloat(value) < parseFloat(rule.param)) {
                    return 'La valeur doit être supérieure à ${rule.param}';
                }
                break;
            case 'max_value':
                if (value && parseFloat(value) > parseFloat(rule.param)) {
                    return 'La valeur doit être inférieure à ${rule.param}';
                }
                break;
            case 'number':
                if (value && isNaN(parseFloat(value))) {
                    return 'Veuillez entrer un nombre valide';
                }
                break;
            case 'phone':
                if (value && !validatePhone(value)) {
                    return 'Veuillez entrer un numéro de téléphone valide';
                }
                break;
            case 'confirm':
                const target = document.querySelector([name="${rule.param}"]);
                if (target && value !== target.value) {
                    return 'Les valeurs ne correspondent pas';
                }
                break;
            case 'regex':
                const regex = new RegExp(rule.param);
                if (value && !regex.test(value)) {
                    return 'Format invalide';
                }
                break;
        }
        return null;
    }
    
    showError(input, message) {
        const errorId =' ${input.name}_error';
        let errorElement = document.getElementById(errorId);
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = errorId;
            errorElement.className = 'form-error';
            input.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    hideError(input) {
        const errorId = '${input.name}_error';
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }
    
    validate() {
        this.errors = {};
        this.isValid = true;
        
        const inputs = this.form.querySelectorAll('[data-validate]');
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                this.isValid = false;
            }
        });
        
        return this.isValid;
    }
    
    getErrors() {
        return this.errors;
    }
}

// Initialiser les validateurs sur tous les formulaires
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('[data-validate-form]');
    forms.forEach(form => {
        const validator = new FormValidator(form);
        
        form.addEventListener('submit', function(e) {
            if (!validator.validate()) {
                e.preventDefault();
                const firstError = this.querySelector('.error');
                if (firstError) {
                    firstError.focus();
                }
                showToast('Veuillez corriger les erreurs du formulaire', 'error');
            }
        });
    });
});

// Fonctions de validation globales
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePhone(phone) {
    return /^[0-9]{9,15}$/.test(phone);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validateDate(date) {
    return !isNaN(new Date(date).getTime());
}

function validateUrl(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

function validateFileSize(file, maxSize) {
    return file.size <= maxSize;
}

function validateFileType(file, allowedTypes) {
    return allowedTypes.includes(file.type);
}

// Exporter
window.FormValidator = FormValidator;
window.validateEmail = validateEmail;
window.validatePhone = validatePhone;
window.validatePassword = validatePassword;
window.validateDate = validateDate;
window.validateUrl = validateUrl;
window.validateFileSize = validateFileSize;
window.validateFileType = validateFileType;// Système de validation des formulaires
class FormValidator {
    constructor(form) {
        this.form = form;
        this.rules = {};
        this.errors = {};
        this.isValid = true;
        this.init();
    }
    
    init() {
        // Récupérer les règles de validation depuis les attributs data
        const inputs = this.form.querySelectorAll('[data-validate]');
        inputs.forEach(input => {
            const rules = input.dataset.validate.split('|');
            this.rules[input.name] = rules.map(rule => {
                const [name, param] = rule.split(':');
                return { name, param };
            });
            
            // Ajouter les événements de validation en temps réel
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => {
                if (input.classList.contains('error')) {
                    this.validateField(input);
                }
            });
        });
    }
    
    validateField(input) {
        const rules = this.rules[input.name] || [];
        let fieldErrors = [];
        
        rules.forEach(rule => {
            const error = this.validateRule(input.value, rule);
            if (error) {
                fieldErrors.push(error);
            }
        });
        
        if (fieldErrors.length > 0) {
            input.classList.add('error');
            this.errors[input.name] = fieldErrors[0];
            this.showError(input, fieldErrors[0]);
            return false;
        } else {
            input.classList.remove('error');
            delete this.errors[input.name];
            this.hideError(input);
            return true;
        }
    }
    
    validateRule(value, rule) {
        switch(rule.name) {
            case 'required':
                if (!value || value.trim() === '') {
                    return 'Ce champ est obligatoire';
                }
                break;
            case 'email':
                if (value && !validateEmail(value)) {
                    return 'Veuillez entrer un email valide';
                }
                break;
            case 'min':
                if (value && value.length < parseInt(rule.param)) {
                    return' Minimum ${rule.param} caractères';
                }
                break;
            case 'max':
                if (value && value.length > parseInt(rule.param)) {
                    return' Maximum ${rule.param} caractères';
                }
                break;
            case 'min_value':
                if (value && parseFloat(value) < parseFloat(rule.param)) {
                    return 'La valeur doit être supérieure à ${rule.param}';
                }
                break;
            case 'max_value':
                if (value && parseFloat(value) > parseFloat(rule.param)) {
                    return 'La valeur doit être inférieure à ${rule.param}';
                }
                break;
            case 'number':
                if (value && isNaN(parseFloat(value))) {
                    return 'Veuillez entrer un nombre valide';
                }
                break;
            case 'phone':
                if (value && !validatePhone(value)) {
                    return 'Veuillez entrer un numéro de téléphone valide';
                }
                break;
            case 'confirm':
                const target = document.querySelector([name="${rule.param}"]);
                if (target && value !== target.value) {
                    return 'Les valeurs ne correspondent pas';
                }
                break;
            case 'regex':
                const regex = new RegExp(rule.param);
                if (value && !regex.test(value)) {
                    return 'Format invalide';
                }
                break;
        }
        return null;
    }
    
    showError(input, message) {
        const errorId ='${input.name}_error';
        let errorElement = document.getElementById(errorId);
        
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = errorId;
            errorElement.className = 'form-error';
            input.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    hideError(input) {
        const errorId =' ${input.name}_error';
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }
    
    validate() {
        this.errors = {};
        this.isValid = true;
        
        const inputs = this.form.querySelectorAll('[data-validate]');
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                this.isValid = false;
            }
        });
        
        return this.isValid;
    }
    
    getErrors() {
        return this.errors;
    }
}

// Initialiser les validateurs sur tous les formulaires
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('[data-validate-form]');
    forms.forEach(form => {
        const validator = new FormValidator(form);
        
        form.addEventListener('submit', function(e) {
            if (!validator.validate()) {
                e.preventDefault();
                const firstError = this.querySelector('.error');
                if (firstError) {
                    firstError.focus();
                }
                showToast('Veuillez corriger les erreurs du formulaire', 'error');
            }
        });
    });
});

// Fonctions de validation globales
function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePhone(phone) {
    return /^[0-9]{9,15}$/.test(phone);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validateDate(date) {
    return !isNaN(new Date(date).getTime());
}

function validateUrl(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

function validateFileSize(file, maxSize) {
    return file.size <= maxSize;
}

function validateFileType(file, allowedTypes) {
    return allowedTypes.includes(file.type);
}

// Exporter
window.FormValidator = FormValidator;
window.validateEmail = validateEmail;
window.validatePhone = validatePhone;
window.validatePassword = validatePassword;
window.validateDate = validateDate;
window.validateUrl = validateUrl;
window.validateFileSize = validateFileSize;
window.validateFileType = validateFileType;