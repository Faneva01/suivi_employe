<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <?php foreach ($soldes as $solde): ?>
        <div class="col-md-4 mb-4">
            <div class="data-card text-center p-4">
                <div style="font-size: .8rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 5px;"><?= $solde['libelle'] ?></div>
                <div style="font-size: 2.5rem; font-weight: 700; color: var(--forest); line-height: 1;"><?= $solde['jours_attribues'] - $solde['jours_pris'] ?> j</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: 5px;">restants sur <?= $solde['jours_attribues'] ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="data-card">
    <div class="data-card-head">
        <h3>Dernières demandes</h3>
        <a href="<?= base_url('employe/conges') ?>" style="font-size: .8rem; color: var(--forest); text-decoration: none;">Tout voir →</a>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Type</th>
                <th>Période</th>
                <th>Durée</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lastConges)): ?>
                <tr><td colspan="4" class="text-center">Aucune demande récente.</td></tr>
            <?php else: ?>
                <?php foreach ($lastConges as $c): ?>
                    <tr>
                        <td><span class="type-badge"><?= $c['type_libelle'] ?? 'N/A' ?></span></td>
                        <td class="td-muted">Du <?= date('d/m/Y', strtotime($c['date_debut'])) ?> au <?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
                        <td class="td-mono"><?= $c['nb_jours'] ?> j</td>
                        <td>
                            <span class="statut s-<?= $c['statut'] ?>"><?= $c['statut'] ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
