// Fonctions principales
document.addEventListener('DOMContentLoaded', function() {
    // Gérer les messages flash
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Confirmation des formulaires
    const confirmForms = document.querySelectorAll('.confirm-form');
    confirmForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-close modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });
    
    // Toggle sidebar sur mobile
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    }
});

// Fonctions globales
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className =" alert alert-${type} ";
    toast.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'}"></i>
        <span>${message}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.prepend(toast);
    } else {
        document.body.prepend(toast);
    }
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s';
        setTimeout(() => toast.remove(), 500);
    }, 5000);
}

function confirmAction(message) {
    return confirm(message);
}

function formatDate(dateString, format = 'DD/MM/YYYY HH:mm') {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return format
        .replace('DD', day)
        .replace('MM', month)
        .replace('YYYY', year)
        .replace('HH', hours)
        .replace('mm', minutes);
}

function getUrlParams() {
    const params = new URLSearchParams(window.location.search);
    const result = {};
    for (const [key, value] of params.entries()) {
        result[key] = value;
    }
    return result;
}

function updateUrlParams(params) {
    const currentParams = getUrlParams();
    const newParams = { ...currentParams, ...params };
    const searchParams = new URLSearchParams(newParams);
    window.location.search = searchParams.toString();
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validatePhone(phone) {
    return /^[0-9]{9,15}$/.test(phone);
}

function formatNumber(number, decimals = 0) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}

function getStatusLabel(status) {
    const labels = {
        'planifiee': 'Planifiée',
        'en_cours': 'En cours',
        'terminee': 'Terminée',
        'annulee': 'Annulée',
        'actif': 'Actif',
        'inactif': 'Inactif',
        'suspendu': 'Suspendu',
        'brouillon': 'Brouillon',
        'soumis': 'Soumis',
        'valide': 'Validé',
        'en_attente': 'En attente',
        'confirme': 'Confirmé',
        'refuse': 'Refusé'
    };
    return labels[status] || status;
}

function getRoleLabel(role) {
    const labels = {
        'admin': 'Administrateur',
        'secretaire': 'Secrétaire',
        'enseignant': 'Enseignant',
        'etudiant': 'Étudiant',
        'responsable': 'Responsable'
    };
    return labels[role] || role;
}

function getMentionLabel(mention) {
    const labels = {
        'excellent': 'Excellent',
        'tres_bien': 'Très Bien',
        'bien': 'Bien',
        'assez_bien': 'Assez Bien',
        'passable': 'Passable',
        'insuffisant': 'Insuffisant'
    };
    return labels[mention] || mention;
}

function getStatusBadge(status) {
    const badges = {
        'planifiee': 'badge-info',
        'en_cours': 'badge-warning',
        'terminee': 'badge-success',
        'annulee': 'badge-danger',
        'actif': 'badge-success',
        'inactif': 'badge-secondary',
        'suspendu': 'badge-danger',
        'brouillon': 'badge-warning',
        'soumis': 'badge-info',
        'valide': 'badge-success',
        'en_attente': 'badge-warning',
        'confirme': 'badge-success',
        'refuse': 'badge-danger'
    };
    return badges[status] || 'badge-secondary';
}

// Export pour utilisation globale
window.showToast = showToast;
window.confirmAction = confirmAction;
window.formatDate = formatDate;
window.getUrlParams = getUrlParams;
window.updateUrlParams = updateUrlParams;
window.validateEmail = validateEmail;
window.validatePassword = validatePassword;
window.validatePhone = validatePhone;
window.formatNumber = formatNumber;
window.getStatusLabel = getStatusLabel;
window.getRoleLabel = getRoleLabel;
window.getMentionLabel = getMentionLabel;
window.getStatusBadge = getStatusBadge;