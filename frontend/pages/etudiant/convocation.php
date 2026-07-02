<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-envelope"></i> Convocation</h1>
    </div>
    
    <div class="card">
        <div class="card-body" id="convocation-content">
            <div style="text-align: center; margin-bottom: 30px;">
                <img src="/assets/images/logo.png" alt="Logo" style="height: 80px;">
                <h2 style="margin-top: 10px; color: var(--primary-color);">CONVOCATION À LA SOUTENANCE</h2>
                <p>Université de Dakar - Faculté des Sciences et Technologies</p>
                <p>Département d'Informatique</p>
            </div>
            
            <div style="margin: 30px 0;">
                <p>Madame, Monsieur,</p>
                <p>Nous avons l'honneur de vous convier à la soutenance de :</p>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                    <p><strong><?= htmlspecialchars($soutenance['titre']) ?></strong></p>
                    <p><strong>Étudiant :</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
                    <p><strong>Matricule :</strong> <?= htmlspecialchars($_SESSION['user_matricule'] ?? '') ?></p>
                    <p><strong>Filière :</strong> <?= htmlspecialchars($soutenance['filiere']) ?></p>
                    <hr>
                    <p><strong>Date :</strong> <?= formatDate($soutenance['date_heure']) ?></p>
                    <p><strong>Lieu :</strong> <?= htmlspecialchars($soutenance['salle_nom']) ?></p>
                    <p><strong>Durée :</strong> <?= $soutenance['duree'] ?> minutes</p>
                </div>
                
                <p>Veuillez vous présenter <strong>15 minutes</strong> avant l'heure prévue.</p>
                <p>Munissez-vous de votre carte d'étudiant.</p>
            </div>
            
            <div style="margin-top: 50px; border-top: 1px solid #ddd; padding-top: 20px; text-align: center; color: #666; font-size: 12px;">
                <p>Document généré le <?= date('d/m/Y à H:i') ?></p>
            </div>
            
            <div style="margin-top: 40px; display: flex; justify-content: space-around;">
                <div style="text-align: center;">
                    <div style="width: 200px; border-top: 1px solid #000; padding-top: 5px;">Le Président du jury</div>
                </div>
                <div style="text-align: center;">
                    <div style="width: 200px; border-top: 1px solid #000; padding-top: 5px;">Le Responsable pédagogique</div>
                </div>
            </div>
        </div>
        
        <div class="card-footer" style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Imprimer
            </button>
            <button onclick="telechargerConvocation()" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> Télécharger PDF
            </button>
            <a href="/etudiant/ma-soutenance" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

<script>
function telechargerConvocation() {
    window.location.href = '/etudiant/convocation/telecharger/<?= $soutenance['id'] ?>';
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>