<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-4">
        <div class="data-card p-4">
            <h3 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Nouvelle demande</h3>
            <form action="<?= base_url('employe/conges/soumettre') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label" style="font-size: .8rem; font-weight: 500;">Type de congé</label>
                    <select name="type_conge_id" class="form-select" style="border-radius: 8px; border: 1.5px solid var(--border);" required>
                        <?php foreach ($soldes as $s): ?>
                            <option value="<?= $s['type_conge_id'] ?>">
                                <?= $s['libelle'] ?> (<?= $s['jours_attribues'] - $s['jours_pris'] ?> j dispo)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size: .8rem; font-weight: 500;">Date de début</label>
                    <input type="date" name="date_debut" class="form-control" style="border-radius: 8px; border: 1.5px solid var(--border);" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size: .8rem; font-weight: 500;">Date de fin</label>
                    <input type="date" name="date_fin" class="form-control" style="border-radius: 8px; border: 1.5px solid var(--border);" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-size: .8rem; font-weight: 500;">Motif (optionnel)</label>
                    <textarea name="motif" class="form-control" style="border-radius: 8px; border: 1.5px solid var(--border);" rows="3" placeholder="Ex: Raisons familiales..."></textarea>
                </div>
                <button type="submit" class="btn-forest w-100">Soumettre la demande</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="data-card">
            <div class="data-card-head">
                <h3>Mes demandes</h3>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Période</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($conges)): ?>
                        <tr><td colspan="5" class="text-center">Aucune demande effectuée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($conges as $c): ?>
                            <tr>
                                <td><span class="type-badge"><?= $c['type_libelle'] ?></span></td>
                                <td class="td-muted">Du <?= date('d/m/Y', strtotime($c['date_debut'])) ?> au <?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
                                <td class="td-mono"><?= $c['nb_jours'] ?> j</td>
                                <td>
                                    <span class="statut s-<?= $c['statut'] ?>"><?= $c['statut'] ?></span>
                                    <?php if ($c['commentaire_rh']): ?>
                                        <i class="bi bi-info-circle ms-1" title="<?= $c['commentaire_rh'] ?>"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($c['statut'] === 'en_attente'): ?>
                                        <a href="<?= base_url('employe/conges/annuler/'.$c['id']) ?>" class="btn btn-sm btn-outline-danger" style="font-size: .75rem;" onclick="return confirm('Annuler cette demande ?')">
                                            <i class="bi bi-x"></i> Annuler
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
<?= $this->endSection() ?>
