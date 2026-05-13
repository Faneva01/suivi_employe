<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="data-card">
    <div class="data-card-head">
        <h3>Demandes de congés en attente</h3>
    </div>
    <table class="tbl">
        <thead>
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
                        <td>
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="avatar av-green" style="width:28px;height:28px;font-size:.6rem"><?= strtoupper(substr($d['prenom'],0,1).substr($d['nom'],0,1)) ?></div>
                                <span style="font-weight:500"><?= $d['prenom'] ?> <?= $d['nom'] ?></span>
                            </div>
                        </td>
                        <td><span class="type-badge"><?= $d['libelle'] ?></span></td>
                        <td class="td-muted">Du <?= date('d/m/Y', strtotime($d['date_debut'])) ?> au <?= date('d/m/Y', strtotime($d['date_fin'])) ?></td>
                        <td class="td-mono"><?= $d['nb_jours'] ?> j</td>
                        <td><small><?= $d['motif'] ?></small></td>
                        <td>
                            <div style="display:flex;gap:5px">
                                <button type="button" class="btn btn-sm btn-success" style="font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#modalTraiter<?= $d['id'] ?>" onclick="setAction('approuvee', <?= $d['id'] ?>)">
                                    Approuver
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" style="font-size: .75rem;" data-bs-toggle="modal" data-bs-target="#modalTraiter<?= $d['id'] ?>" onclick="setAction('refusee', <?= $d['id'] ?>)">
                                    Refuser
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Traiter -->
                    <div class="modal fade" id="modalTraiter<?= $d['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                                    <h5 class="modal-title" style="font-family: 'Playfair Display', serif; font-weight: 700;">Traiter la demande</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?= base_url('rh/demandes/traiter') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                    <input type="hidden" name="action" id="action<?= $d['id'] ?>">
                                    <div class="modal-body">
                                        <p>Voulez-vous vraiment <span id="actionLabel<?= $d['id'] ?>" style="font-weight: 700;"></span> cette demande de <strong><?= $d['prenom'] ?> <?= $d['nom'] ?></strong> ?</p>
                                        <div class="mb-3">
                                            <label class="form-label" style="font-size: .8rem; font-weight: 500;">Commentaire pour l'employé (optionnel)</label>
                                            <textarea name="commentaire_rh" class="form-control" style="border-radius: 8px; border: 1.5px solid var(--border);" rows="3" placeholder="Ex: Bonnes vacances !"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="border-top: none; padding-top: 0;">
                                        <button type="button" class="btn btn-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn-forest">Confirmer</button>
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

<script>
    function setAction(action, id) {
        document.getElementById('action' + id).value = action;
        document.getElementById('actionLabel' + id).innerText = (action === 'approuvee' ? 'approuver' : 'refuser');
    }
</script>
<?= $this->endSection() ?>
