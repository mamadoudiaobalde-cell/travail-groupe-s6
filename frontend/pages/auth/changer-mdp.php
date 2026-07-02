<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="form-container">
    <h2><i class="fas fa-key"></i> Changer mon mot de passe</h2>
    <p class="text-muted">Modifiez votre mot de passe en remplissant le formulaire ci-dessous.</p>
    
    <form method="POST" action="/profile/password" data-validate-form>
        <div class="form-group">
            <label for="old_password" class="required">Mot de passe actuel</label>
            <div style="position: relative;">
                <input type="password" id="old_password" name="old_password" class="form-control" 
                       placeholder="••••••••" data-validate="required" required>
                <button type="button" class="toggle-password" data-target="old_password" 
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #999;">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="form-error" id="old_password_error"></div>
        </div>
        
        <div class="form-group">
            <label for="new_password" class="required">Nouveau mot de passe</label>
            <div style="position: relative;">
                <input type="password" id="new_password" name="new_password" class="form-control" 
                       placeholder="••••••••" data-validate="required|min:6" required>
                <button type="button" class="toggle-password" data-target="new_password" 
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #999;">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="form-error" id="new_password_error"></div>
            <small class="form-help">Minimum 6 caractères</small>
        </div>
        
        <div class="form-group">
            <label for="confirm_password" class="required">Confirmer le mot de passe</label>
            <div style="position: relative;">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                       placeholder="••••••••" data-validate="required|confirm:new_password" required>
                <button type="button" class="toggle-password" data-target="confirm_password" 
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #999;">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="form-error" id="confirm_password_error"></div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Changer le mot de passe
            </button>
            <a href="/dashboard" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
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
});
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>