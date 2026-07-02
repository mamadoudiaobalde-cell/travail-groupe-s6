<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-certificate"></i> Attestation de soutenance</h1>
    </div>
    
    <div class="card">
        <div class="card-body" id="attestation-content">
            <div style="text-align: center; margin-bottom: 30px;">
                <img src="/assets/images/logo.png" alt="Logo" style="height: 80px;">
                <h2 style="margin-top: 10px; color: var(--primary-color);">ATTESTATION DE SOUTENANCE</h2>
                <p>Université de Dakar - Faculté des Sciences et Technologies</p>
                <p>Département d'Informatique</p>
            </div>
            
            <div style="margin: 30px 0;">
                <p>Le présent document atteste que :</p>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
                    <p><strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
                    <p>Matricule : <?= htmlspecialchars($_SESSION['user_matricule'] ?? '') ?></p>
                    <p>Filière : <?= htmlspecialchars($soutenance['filiere']) ?></p>
                    <hr>
                    <p>A soutenu son travail intitulé :</p>
                    <p><strong>"<?= htmlspecialchars($soutenance['titre']) ?>"</strong></p>
                    <hr>
                    <p>Le <?= formatDate($soutenance['date_heure']) ?></p>
                    <p>Devant le jury composé de :</p>
                    <div style="margin: 10px 0;">
                        <?php foreach ($jury as $m): ?>
                        <p><strong><?= ucfirst($m['role']) ?> :</strong> <?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <p>Et a obtenu la mention :</p>
                    <p><strong style="color: var(--primary-color); font-size: 24px;"><?= getMentionLabel($pv['mention']) ?></strong></p>
                    <p>Note : <?= number_format($pv['note'], 2) ?>/20</p>
                </div>
                
                <p>Cette attestation lui est délivrée pour faire valoir ce que de droit.</p>
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
            <button onclick="telechargerAttestation()" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> Télécharger PDF
            </button>
            <a href="/etudiant/resultats" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

<script>
function telechargerAttestation() {
    window.location.href = '/etudiant/attestation/telecharger/<?= $soutenance['id'] ?>';
}
</script>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>