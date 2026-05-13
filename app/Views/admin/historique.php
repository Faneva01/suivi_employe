<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="data-card">
    <div class="data-card-head">
        <h3>Historique complet des demandes</h3>
        <form method="get" action="<?= base_url('admin/historique') ?>" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <select name="statut" class="f-select" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                <option value="all">Tous les statuts</option>
                <option value="en_attente" <?= $filtreStatut === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                <option value="approuvee" <?= $filtreStatut === 'approuvee' ? 'selected' : '' ?>>Approuvées</option>
                <option value="refusee" <?= $filtreStatut === 'refusee' ? 'selected' : '' ?>>Refusées</option>
                <option value="annulee" <?= $filtreStatut === 'annulee' ? 'selected' : '' ?>>Annulées</option>
            </select>
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
                <th>Dates</th>
                <th>Jours</th>
                <th>Statut</th>
                <th>Motif / Commentaire RH</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($demandes)): ?>
                <tr><td colspan="6" class="text-center">Aucune demande trouvée.</td></tr>
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
                        <td>
                            <?php if ($d['statut'] === 'en_attente'): ?>
                                <span class="badge badge-warning">En attente</span>
                            <?php elseif ($d['statut'] === 'approuvee'): ?>
                                <span class="badge badge-success">Approuvée</span>
                            <?php elseif ($d['statut'] === 'refusee'): ?>
                                <span class="badge badge-danger">Refusée</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?= ucfirst($d['statut']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small>
                                <strong>Motif:</strong> <?= $d['motif'] ?><br>
                                <?php if ($d['commentaire_rh']): ?>
                                    <strong>RH:</strong> <?= $d['commentaire_rh'] ?>
                                <?php endif; ?>
                            </small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>