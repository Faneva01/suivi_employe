<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-8">
        <h3>Mes Demandes de Congés</h3>
    </div>
    <div class="col-md-4 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDemande">
            <i class="fas fa-plus me-2"></i> Nouvelle Demande
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Jours</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($conges)): ?>
                            <tr><td colspan="6" class="text-center">Aucune demande effectuée.</td></tr>
                        <?php else: ?>
                            <?php foreach ($conges as $c): ?>
                                <tr>
                                    <td><?= $c['type_libelle'] ?></td>
                                    <td>Du <?= $c['date_debut'] ?> au <?= $c['date_fin'] ?></td>
                                    <td><?= $c['nb_jours'] ?></td>
                                    <td><?= $c['motif'] ?></td>
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
                                        
                                        <?php if ($c['commentaire_rh']): ?>
                                            <i class="fas fa-info-circle text-info ms-1" data-bs-toggle="tooltip" title="<?= $c['commentaire_rh'] ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($c['statut'] === 'en_attente'): ?>
                                            <a href="<?= base_url('employe/conges/annuler/'.$c['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Annuler cette demande ?')">
                                                Annuler
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Demande -->
<div class="modal fade" id="modalDemande" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Soumettre une demande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('employe/conges/soumettre') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Type de congé</label>
                        <select name="type_conge_id" class="form-select" required>
                            <?php foreach ($soldes as $s): ?>
                                <option value="<?= $s['type_conge_id'] ?>">
                                    <?= $s['libelle'] ?> (Restant : <?= $s['jours_attribues'] - $s['jours_pris'] ?> j)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de début</label>
                            <input type="date" name="date_debut" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de fin</label>
                            <input type="date" name="date_fin" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea name="motif" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?= $this->endSection() ?>
