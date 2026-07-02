<div id="<?= $id ?? 'modal' ?>" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('<?= $id ?? 'modal' ?>')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><?= $title ?? 'Modal' ?></h3>
            <button class="modal-close" onclick="closeModal('<?= $id ?? 'modal' ?>')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <?= $content ?? '' ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('<?= $id ?? 'modal' ?>')">
                Annuler
            </button>
            <button class="btn btn-primary" id="modal-submit">
                Confirmer
            </button>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease;
}

.modal-container {
    position: relative;
    background: white;
    border-radius: var(--border-radius);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    animation: slideUp 0.3s ease;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: var(--text-color);
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: var(--text-light);
    transition: var(--transition);
    padding: 5px;
}

.modal-close:hover {
    color: var(--text-color);
    transform: rotate(90deg);
}

.modal-body {
    padding: 20px;
    overflow-y: auto;
    max-height: calc(80vh - 140px);
}

.modal-body .form-group:last-child {
    margin-bottom: 0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 15px 20px;
    border-top: 1px solid #eee;
    background: #fafafa;
}

.modal-footer .btn {
    min-width: 100px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 480px) {
    .modal-container {
        width: 95%;
        margin: 10px;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
}
</style>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Fermer le modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal').forEach(modal => {
            if (modal.style.display === 'flex') {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }
});

// Fermer le modal en cliquant sur l'overlay
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        const modal = e.target.closest('.modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
});
</script>