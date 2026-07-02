<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> Mes résultats</h1>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($soutenances)): ?>
            <div class="empty-state">
                <i class="fas fa-chart-bar" style="font-size: 64px;"></i>
                <h4>Aucun résultat disponible</h4>
                <p>Vous n'avez pas encore de soutenance terminée.</p>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Soutenance</th>
                            <th>Date</th>
                            <th>Note</th>
                            <th>Mention</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($soutenances as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['titre']) ?></td>
                            <td><?= formatDate($s['date_heure']) ?></td>
                            <td>
                                <?php if ($s['pv']['note'] ?? false): ?>
                                <strong><?= number_format($s['pv']['note'], 2) ?>/20</strong>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($s['pv']['mention'] ?? false): ?>
                                <span class="badge badge-<?= $s['pv']['mention'] ?>"><?= getMentionLabel($s['pv']['mention']) ?></span>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-<?= $s['statut'] ?>"><?= getStatusLabel($s['statut']) ?></span></td>
                            <td>
                                <?php if ($s['statut'] === 'terminee' && isset($s['pv']) && $s['pv']['statut'] === 'valide'): ?>
                                <a href="/etudiant/telecharger-pv/<?= $s['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i> PV
                                </a>
                                <a href="/etudiant/attestation/<?= $s['id'] ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-certificate"></i> Attestation
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once FRONTEND_PATH . '/templates/footer.php'; ?>