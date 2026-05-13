<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-8">
        <h3>Gestion des Employés</h3>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= base_url('admin/employes/create') ?>" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Nouvel Employé
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom & Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Département</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employes as $e): ?>
                            <tr>
                                <td><?= $e['prenom'] ?> <?= $e['nom'] ?></td>
                                <td><?= $e['email'] ?></td>
                                <td><span class="badge bg-secondary"><?= $e['role'] ?></span></td>
                                <td><?= $e['dep_nom'] ?? 'N/A' ?></td>
                                <td>
                                    <?php if ($e['actif'] == 1): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/employes/edit/'.$e['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/employes/toggle/'.$e['id']) ?>" class="btn btn-sm btn-outline-<?= $e['actif'] == 1 ? 'warning' : 'success' ?>" title="<?= $e['actif'] == 1 ? 'Désactiver' : 'Activer' ?>">
                                        <i class="fas fa-power-off"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
