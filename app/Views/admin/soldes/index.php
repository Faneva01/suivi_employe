<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<h3 class="page-title">Gestion des soldes</h3>
<p class="page-sub">Consultez et ajustez les soldes annuels des employés.</p>

<div class="data-card">
    <div class="data-card-head">
        <h3>Soldes <?= $currentYear ?></h3>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Type de congé</th>
                <th>Attribués</th>
                <th>Pris</th>
                <th>Restant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($soldes)): ?>
                <tr><td colspan="6" class="text-center">Aucun solde trouvé.</td></tr>
            <?php else: ?>
                <?php foreach ($soldes as $s): ?>
                    <?php $restant = $s['jours_attribues'] - $s['jours_pris']; ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="avatar av-green" style="width:28px;height:28px;font-size:.6rem"><?= strtoupper(substr($s['emp_prenom'],0,1).substr($s['emp_nom'],0,1)) ?></div>
                                <span style="font-weight:500"><?= $s['emp_prenom'] ?> <?= $s['emp_nom'] ?></span>
                            </div>
                        </td>
                        <td><span class="type-badge"><?= $s['type_libelle'] ?></span></td>
                        <td class="td-mono"><?= $s['jours_attribues'] ?></td>
                        <td class="td-mono"><?= $s['jours_pris'] ?></td>
                        <td class="td-mono" style="font-weight:600;color:<?= $restant > 0 ? 'var(--success)' : 'var(--danger)' ?>">
                            <?= $restant ?> j
                        </td>
                        <td>
                            <button type="button" class="btn-outline-primary" onclick="document.getElementById('modalSolde<?= $s['id'] ?>').classList.add('show')">
                                <i class="bi bi-pencil"></i> Ajuster
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Ajuster Solde -->
                    <div class="f-modal-overlay" id="modalSolde<?= $s['id'] ?>">
                        <div class="f-modal">
                            <div class="f-modal-head">
                                <h5>Ajuster le solde</h5>
                                <button type="button" class="f-modal-close" onclick="document.getElementById('modalSolde<?= $s['id'] ?>').classList.remove('show')">&times;</button>
                            </div>
                            <form action="<?= base_url('admin/soldes/update') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <div class="f-modal-body">
                                    <p>Employé: <strong><?= $s['emp_prenom'] ?> <?= $s['emp_nom'] ?></strong></p>
                                    <p>Type: <span class="type-badge"><?= $s['type_libelle'] ?></span></p>
                                    <div class="f-group">
                                        <label class="f-label">Jours attribués</label>
                                        <input type="number" name="jours_attribues" class="f-input" required min="0" value="<?= $s['jours_attribues'] ?>">
                                    </div>
                                    <p style="font-size:.8rem;color:var(--muted);">Jours déjà pris: <strong><?= $s['jours_pris'] ?></strong></p>
                                </div>
                                <div class="f-modal-foot">
                                    <button type="button" class="btn-secondary" onclick="document.getElementById('modalSolde<?= $s['id'] ?>').classList.remove('show')">Annuler</button>
                                    <button type="submit" class="btn-forest">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
