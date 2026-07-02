<?php require_once FRONTEND_PATH . '/templates/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-history"></i> Historique des soutenances</h1>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($soutenances)): ?>
            <div class="empty-state">
                <i class="fas fa-history" style="font-size: 64px;"></i>
                <h4>Aucun historique</h4>
                <p>Vous n'avez pas encore de soutenance enregistrée.</p>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Soutenance</th>
                            <th>Date</th>
                            <th>Filière</th>
                            <th>Statut</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($soutenances as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['titre']) ?></td>
                            <td><?= formatDate($s['date_heure']) ?></td>
                            <td><?= htmlspecialchars($s['filiere']) ?></td>
                            <td><span class="badge badge-<?= $s['statut'] ?>"><?= getStatusLabel($s['statut']) ?></span></td>
                            <td>
                                <?php if (isset($s['pv']) && $s['pv']['statut'] === 'valide'): ?>
                                <?= number_format($s['pv']['note'], 2) ?>/20
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($s['statut'] === 'terminee' && isset($s['pv']) && $s['pv']['statut'] === 'valide'): ?>
                                <a href="/etudiant/telecharger-pv/<?= $s['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i> PV
                                </a>
                                <?php endif; ?>
                                <a href="/etudiant/ma-soutenance/<?= $s['id'] ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
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