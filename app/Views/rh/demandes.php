<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <h3>Demandes de Congés en Attente</h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Jours</th>
                            <th>Motif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($demandes)): ?>
                            <tr><td colspan="6" class="text-center">Aucune demande en attente.</td></tr>
                        <?php else: ?>
                            <?php foreach ($demandes as $d): ?>
                                <tr>
                                    <td><?= $d['prenom'] ?> <?= $d['nom'] ?></td>
                                    <td><?= $d['libelle'] ?></td>
                                    <td>Du <?= $d['date_debut'] ?> au <?= $d['date_fin'] ?></td>
                                    <td><?= $d['nb_jours'] ?></td>
                                    <td><?= $d['motif'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalTraiter<?= $d['id'] ?>" onclick="setAction('approuvee', <?= $d['id'] ?>)">
                                            Approuver
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalTraiter<?= $d['id'] ?>" onclick="setAction('refusee', <?= $d['id'] ?>)">
                                            Refuser
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Traiter -->
                                <div class="modal fade" id="modalTraiter<?= $d['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Traiter la demande</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?= base_url('rh/demandes/traiter') ?>" method="post">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                                <input type="hidden" name="action" id="action<?= $d['id'] ?>">
                                                <div class="modal-body">
                                                    <p>Voulez-vous vraiment <span id="actionLabel<?= $d['id'] ?>"></span> cette demande ?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Commentaire (optionnel)</label>
                                                        <textarea name="commentaire_rh" class="form-control" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Confirmer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function setAction(action, id) {
        document.getElementById('action' + id).value = action;
        document.getElementById('actionLabel' + id).innerText = (action === 'approuvee' ? 'approuver' : 'refuser');
    }
</script>
<?= $this->endSection() ?>
