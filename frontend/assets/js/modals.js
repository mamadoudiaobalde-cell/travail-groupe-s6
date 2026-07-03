// Gestion des modals
class ModalManager {
    constructor() {
        this.activeModal = null;
        this.modals = {};
        this.init();
    }
    
    init() {
        // Récupérer tous les modals
        document.querySelectorAll('.modal').forEach(modal => {
            const id = modal.id || 'modal-' + Date.now();
            if (!modal.id) modal.id = id;
            this.modals[id] = modal;
            
            // Ajouter l'overlay si absent
            if (!modal.querySelector('.modal-overlay')) {
                const overlay = document.createElement('div');
                overlay.className = 'modal-overlay';
                overlay.onclick = () => this.close(id);
                modal.prepend(overlay);
            }
            
            // Gérer la fermeture avec ESC
            modal.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.close(id);
            });
        });
    }
    
    open(id, data = null) {
        const modal = this.modals[id];
        if (!modal) {
            console.warn('Modal "${id}" non trouvé');
            return;
        }
        
        // Fermer le modal actif
        if (this.activeModal) {
            this.close(this.activeModal);
        }
        
        // Préparer le modal
        if (data) {
            this.populateModal(modal, data);
        }
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        this.activeModal = id;
        
        // Focus sur le premier champ
        const firstInput = modal.querySelector('input, select, textarea');
        if (firstInput) setTimeout(() => firstInput.focus(), 100);
        
        // Déclencher l'événement
        modal.dispatchEvent(new CustomEvent('modal-open', { detail: { id, data } }));
    }
    
    close(id) {
        const modal = this.modals[id];
        if (!modal) return;
        
        modal.style.display = 'none';
        document.body.style.overflow = '';
        
        if (this.activeModal === id) {
            this.activeModal = null;
        }
        
        // Déclencher l'événement
        modal.dispatchEvent(new CustomEvent('modal-close', { detail: { id } }));
    }
    
    toggle(id, data = null) {
        const modal = this.modals[id];
        if (!modal) return;
        
        if (modal.style.display === 'flex') {
            this.close(id);
        } else {
            this.open(id, data);
        }
    }
    
    populateModal(modal, data) {
        // Remplir les champs du modal avec les données
        Object.keys(data).forEach(key => {
            const input = modal.querySelector([name="${key}"]);
            if (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = data[key];
                } else {
                    input.value = data[key];
                }
            }
        });
    }
    
    getModalData(modal) {
        const data = {};
        const inputs = modal.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                if (input.type === 'checkbox') {
                    data[input.name] = input.checked;
                } else if (input.type === 'radio') {
                    if (input.checked) data[input.name] = input.value;
                } else {
                    data[input.name] = input.value;
                }
            }
        });
        return data;
    }
    
    isOpen(id) {
        const modal = this.modals[id];
        return modal && modal.style.display === 'flex';
    }
}

// Initialiser les modals
const modalManager = new ModalManager();

// Fonctions globales pour la compatibilité
function openModal(id, data = null) {
    modalManager.open(id, data);
}

function closeModal(id) {
    modalManager.close(id);
}

function toggleModal(id, data = null) {
    modalManager.toggle(id, data);
}

// Gestion des boutons de fermeture
document.addEventListener('click', function(e) {
    if (e.target.matches('.modal-close, .modal .btn-secondary')) {
        const modal = e.target.closest('.modal');
        if (modal) closeModal(modal.id);
    }
});

// Exporter
window.ModalManager = ModalManager;
window.modalManager = modalManager;
window.openModal = openModal;
window.closeModal = closeModal;
window.toggleModal = toggleModal;
// Gestion des modals
class ModalManager {
    constructor() {
        this.activeModal = null;
        this.modals = {};
        this.init();
    }
    
    init() {
        // Récupérer tous les modals
        document.querySelectorAll('.modal').forEach(modal => {
            const id = modal.id || 'modal-' + Date.now();
            if (!modal.id) modal.id = id;
            this.modals[id] = modal;
            
            // Ajouter l'overlay si absent
            if (!modal.querySelector('.modal-overlay')) {
                const overlay = document.createElement('div');
                overlay.className = 'modal-overlay';
                overlay.onclick = () => this.close(id);
                modal.prepend(overlay);
            }
            
            // Gérer la fermeture avec ESC
            modal.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.close(id);
            });
        });
    }
    
    open(id, data = null) {
        const modal = this.modals[id];
        if (!modal) {
            console.warn('Modal "${id}" non trouvé');
            return;
        }
        
        // Fermer le modal actif
        if (this.activeModal) {
            this.close(this.activeModal);
        }
        
        // Préparer le modal
        if (data) {
            this.populateModal(modal, data);
        }
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        this.activeModal = id;
        
        // Focus sur le premier champ
        const firstInput = modal.querySelector('input, select, textarea');
        if (firstInput) setTimeout(() => firstInput.focus(), 100);
        
        // Déclencher l'événement
        modal.dispatchEvent(new CustomEvent('modal-open', { detail: { id, data } }));
    }
    
    close(id) {
        const modal = this.modals[id];
        if (!modal) return;
        
        modal.style.display = 'none';
        document.body.style.overflow = '';
        
        if (this.activeModal === id) {
            this.activeModal = null;
        }
        
        // Déclencher l'événement
        modal.dispatchEvent(new CustomEvent('modal-close', { detail: { id } }));
    }
    
    toggle(id, data = null) {
        const modal = this.modals[id];
        if (!modal) return;
        
        if (modal.style.display === 'flex') {
            this.close(id);
        } else {
            this.open(id, data);
        }
    }
    
    populateModal(modal, data) {
        // Remplir les champs du modal avec les données
        Object.keys(data).forEach(key => {
            const input = modal.querySelector([name="${key}"]);
            if (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = data[key];
                } else {
                    input.value = data[key];
                }
            }
        });
    }
    
    getModalData(modal) {
        const data = {};
        const inputs = modal.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                if (input.type === 'checkbox') {
                    data[input.name] = input.checked;
                } else if (input.type === 'radio') {
                    if (input.checked) data[input.name] = input.value;
                } else {
                    data[input.name] = input.value;
                }
            }
        });
        return data;
    }
    
    isOpen(id) {
        const modal = this.modals[id];
        return modal && modal.style.display === 'flex';
    }
}

// Initialiser les modals
const modalManager = new ModalManager();

// Fonctions globales pour la compatibilité
function openModal(id, data = null) {
    modalManager.open(id, data);
}

function closeModal(id) {
    modalManager.close(id);
}

function toggleModal(id, data = null) {
    modalManager.toggle(id, data);
}

// Gestion des boutons de fermeture
document.addEventListener('click', function(e) {
    if (e.target.matches('.modal-close, .modal .btn-secondary')) {
        const modal = e.target.closest('.modal');
        if (modal) closeModal(modal.id);
    }
});

// Exporter
window.ModalManager = ModalManager;
window.modalManager = modalManager;
window.openModal = openModal;
window.closeModal = closeModal;
window.toggleModal = toggleModal;