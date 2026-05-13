<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 mb-4">
        <h2>Dashboard Administrateur</h2>
        <p class="text-muted">Aperçu général du système.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card bg-primary text-white text-center p-3">
            <h3><?= $totalEmployes ?></h3>
            <p class="mb-0">Employés inscrits</p>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card bg-warning text-white text-center p-3">
            <h3><?= $demandesAttente ?></h3>
            <p class="mb-0">Demandes en attente</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Absences du mois en cours</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Jours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($absencesMois)): ?>
                            <tr><td colspan="4" class="text-center">Aucune absence ce mois-ci.</td></tr>
                        <?php else: ?>
                            <?php foreach ($absencesMois as $a): ?>
                                <tr>
                                    <td><?= $a['prenom'] ?> <?= $a['nom'] ?></td>
                                    <td><?= $a['libelle'] ?></td>
                                    <td>Du <?= $a['date_debut'] ?> au <?= $a['date_fin'] ?></td>
                                    <td><?= $a['nb_jours'] ?></td>
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
