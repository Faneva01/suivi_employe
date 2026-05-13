<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<h3 class="page-title">Soldes des employés</h3>
<p class="page-sub">Consultez les soldes de congés par employé.</p>

<div class="data-card">
    <div class="data-card-head">
        <h3>Soldes de congés</h3>
        <form method="get" action="<?= base_url('rh/soldes') ?>" style="display:flex;align-items:center;gap:8px;">
            <select name="departement_id" class="f-select" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                <option value="">Tous les départements</option>
                <?php foreach ($departements as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= $filtreDep == $d['id'] ? 'selected' : '' ?>><?= $d['nom'] ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Type</th>
                <th>Attribués</th>
                <th>Pris</th>
                <th>Restant</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($soldes)): ?>
                <tr><td colspan="5" class="text-center">Aucun solde trouvé.</td></tr>
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
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
