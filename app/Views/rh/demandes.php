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
                            <div class="btn-row">
                                <button type="button" class="btn-success" onclick="document.getElementById('modalTraiter<?= $d['id'] ?>').classList.add('show');setAction('approuvee', <?= $d['id'] ?>)">
                                    Approuver
                                </button>
                                <button type="button" class="btn-danger" onclick="document.getElementById('modalTraiter<?= $d['id'] ?>').classList.add('show');setAction('refusee', <?= $d['id'] ?>)">
                                    Refuser
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Traiter -->
                    <div class="f-modal-overlay" id="modalTraiter<?= $d['id'] ?>">
                        <div class="f-modal">
                            <div class="f-modal-head">
                                <h5>Traiter la demande</h5>
                                <button type="button" class="f-modal-close" onclick="document.getElementById('modalTraiter<?= $d['id'] ?>').classList.remove('show')">&times;</button>
                            </div>
                            <form action="<?= base_url('rh/demandes/traiter') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                <input type="hidden" name="action" id="action<?= $d['id'] ?>">
                                <div class="f-modal-body">
                                    <p>Voulez-vous vraiment <span id="actionLabel<?= $d['id'] ?>" style="font-weight: 700;"></span> cette demande de <strong><?= $d['prenom'] ?> <?= $d['nom'] ?></strong> ?</p>
                                    <div class="f-group">
                                        <label class="f-label">Commentaire pour l'employé (optionnel)</label>
                                        <textarea name="commentaire_rh" class="f-input" rows="3" placeholder="Ex: Bonnes vacances !"></textarea>
                                    </div>
                                </div>
                                <div class="f-modal-foot">
                                    <button type="button" class="btn-secondary" onclick="document.getElementById('modalTraiter<?= $d['id'] ?>').classList.remove('show')">Annuler</button>
                                    <button type="submit" class="btn-forest">Confirmer</button>
                                </div>
                            </form>
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
