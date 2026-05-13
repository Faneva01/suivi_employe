<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Bienvenue, <?= session()->get('prenom') ?> !</h2>
        <p class="text-muted">Tableau de bord de suivi de vos congés.</p>
    </div>
</div>

<div class="row">
    <?php foreach ($soldes as $solde): ?>
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= $solde['libelle'] ?></h5>
                    <h1 class="display-4"><?= $solde['jours_attribues'] - $solde['jours_pris'] ?></h1>
                    <p class="card-text text-muted">jours restants sur <?= $solde['jours_attribues'] ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Dernières demandes</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Dates</th>
                            <th>Jours</th>
                            <th>Statut</th>
                            <th>Soumis le</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lastConges)): ?>
                            <tr><td colspan="4" class="text-center">Aucune demande récente.</td></tr>
                        <?php else: ?>
                            <?php foreach ($lastConges as $c): ?>
                                <tr>
                                    <td>Du <?= $c['date_debut'] ?> au <?= $c['date_fin'] ?></td>
                                    <td><?= $c['nb_jours'] ?></td>
                                    <td>
                                        <?php if ($c['statut'] === 'en_attente'): ?>
                                            <span class="badge bg-warning">En attente</span>
                                        <?php elseif ($c['statut'] === 'approuvee'): ?>
                                            <span class="badge bg-success">Approuvée</span>
                                        <?php elseif ($c['statut'] === 'refusee'): ?>
                                            <span class="badge bg-danger">Refusée</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $c['statut'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
