// Gestion de l'authentification
document.addEventListener('DOMContentLoaded', function() {
    // Vérification du mot de passe
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordInput && confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.classList.add('error');
                showError('confirm_password_error', 'Les mots de passe ne correspondent pas');
            } else {
                this.classList.remove('error');
                hideError('confirm_password_error');
            }
        });
    }
    
    // Masquer/montrer le mot de passe
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            if (target) {
                if (target.type === 'password') {
                    target.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    target.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            }
        });
    });
    
    // Validation en temps réel du formulaire de connexion
    const loginForm = document.querySelector('form[action="/login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let isValid = true;
            
            if (email && !validateEmail(email.value)) {
                email.classList.add('error');
                showError('email_error', 'Veuillez entrer un email valide');
                isValid = false;
            }
            
            if (password && password.value.length < 6) {
                password.classList.add('error');
                showError('password_error', 'Le mot de passe doit contenir au moins 6 caractères');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Validation en temps réel du formulaire d'inscription
    const registerForm = document.querySelector('form[action="/register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const nom = document.getElementById('nom');
            const prenom = document.getElementById('prenom');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const matricule = document.getElementById('matricule');
            let isValid = true;
            
            if (nom && nom.value.trim().length < 2) {
                nom.classList.add('error');
                showError('nom_error', 'Le nom doit contenir au moins 2 caractères');
                isValid = false;
            }
            
            if (prenom && prenom.value.trim().length < 2) {
                prenom.classList.add('error');
                showError('prenom_error', 'Le prénom doit contenir au moins 2 caractères');
                isValid = false;
            }
            
            if (email && !validateEmail(email.value)) {
                email.classList.add('error');
                showError('email_error', 'Veuillez entrer un email valide');
                isValid = false;
            }
            
            if (password && password.value.length < 6) {
                password.classList.add('error');
                showError('password_error', 'Le mot de passe doit contenir au moins 6 caractères');
                isValid = false;
            }
            
            if (confirmPassword && confirmPassword.value !== password.value) {
                confirmPassword.classList.add('error');
                showError('confirm_password_error', 'Les mots de passe ne correspondent pas');
                isValid = false;
            }
            
            if (matricule && matricule.value.trim().length < 3) {
                matricule.classList.add('error');
                showError('matricule_error', 'Le matricule doit contenir au moins 3 caractères');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});

function showError(id, message) {
    const errorElement = document.getElementById(id);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function hideError(id) {
    const errorElement = document.getElementById(id);
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

// Gestion du décompte de déconnexion automatique
let logoutTimer;
const sessionTimeout = 30 * 60 * 1000; // 30 minutes

function resetSessionTimer() {
    clearTimeout(logoutTimer);
    logoutTimer = setTimeout(() => {
        if (confirm('Votre session va expirer. Voulez-vous continuer ?')) {
            // Rafraîchir la session
            fetch('/auth/refresh', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resetSessionTimer();
                    }
                })
                .catch(() => {});
        }
    }, sessionTimeout);
}

// Démarrer le timer si l'utilisateur est connecté
if (document.querySelector('.user-info')) {
    resetSessionTimer();
    document.addEventListener('click', resetSessionTimer);
    document.addEventListener('keypress', resetSessionTimer);
}// Gestion de l'authentification
document.addEventListener('DOMContentLoaded', function() {
    // Vérification du mot de passe
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (passwordInput && confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.classList.add('error');
                showError('confirm_password_error', 'Les mots de passe ne correspondent pas');
            } else {
                this.classList.remove('error');
                hideError('confirm_password_error');
            }
        });
    }
    
    // Masquer/montrer le mot de passe
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            if (target) {
                if (target.type === 'password') {
                    target.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    target.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            }
        });
    });
    
    // Validation en temps réel du formulaire de connexion
    const loginForm = document.querySelector('form[action="/login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let isValid = true;
            
            if (email && !validateEmail(email.value)) {
                email.classList.add('error');
                showError('email_error', 'Veuillez entrer un email valide');
                isValid = false;
            }
            
            if (password && password.value.length < 6) {
                password.classList.add('error');
                showError('password_error', 'Le mot de passe doit contenir au moins 6 caractères');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Validation en temps réel du formulaire d'inscription
    const registerForm = document.querySelector('form[action="/register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const nom = document.getElementById('nom');
            const prenom = document.getElementById('prenom');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const matricule = document.getElementById('matricule');
            let isValid = true;
            
            if (nom && nom.value.trim().length < 2) {
                nom.classList.add('error');
                showError('nom_error', 'Le nom doit contenir au moins 2 caractères');
                isValid = false;
            }
            
            if (prenom && prenom.value.trim().length < 2) {
                prenom.classList.add('error');
                showError('prenom_error', 'Le prénom doit contenir au moins 2 caractères');
                isValid = false;
            }
            
            if (email && !validateEmail(email.value)) {
                email.classList.add('error');
                showError('email_error', 'Veuillez entrer un email valide');
                isValid = false;
            }
            
            if (password && password.value.length < 6) {
                password.classList.add('error');
                showError('password_error', 'Le mot de passe doit contenir au moins 6 caractères');
                isValid = false;
            }
            
            if (confirmPassword && confirmPassword.value !== password.value) {
                confirmPassword.classList.add('error');
                showError('confirm_password_error', 'Les mots de passe ne correspondent pas');
                isValid = false;
            }
            
            if (matricule && matricule.value.trim().length < 3) {
                matricule.classList.add('error');
                showError('matricule_error', 'Le matricule doit contenir au moins 3 caractères');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});

function showError(id, message) {
    const errorElement = document.getElementById(id);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function hideError(id) {
    const errorElement = document.getElementById(id);
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

// Gestion du décompte de déconnexion automatique
let logoutTimer;
const sessionTimeout = 30 * 60 * 1000; // 30 minutes

function resetSessionTimer() {
    clearTimeout(logoutTimer);
    logoutTimer = setTimeout(() => {
        if (confirm('Votre session va expirer. Voulez-vous continuer ?')) {
            // Rafraîchir la session
            fetch('/auth/refresh', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resetSessionTimer();
                    }
                })
                .catch(() => {});
        }
    }, sessionTimeout);
}

// Démarrer le timer si l'utilisateur est connecté
if (document.querySelector('.user-info')) {
    resetSessionTimer();
    document.addEventListener('click', resetSessionTimer);
    document.addEventListener('keypress', resetSessionTimer);
}