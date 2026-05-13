<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4" style="align-items:center;">
    <div class="col">
        <h3 class="page-title" style="margin:0;">Gestion des types de congé</h3>
    </div>
    <div class="col text-end">
        <button type="button" class="btn-forest" onclick="document.getElementById('modalType').classList.add('show')">
            <i class="bi bi-plus-lg"></i> Nouveau type
        </button>
    </div>
</div>

<div class="data-card">
    <table class="tbl">
        <thead>
            <tr>
                <th>ID</th>
                <th>Libellé</th>
                <th>Jours annuels</th>
                <th>Déductible</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($types)): ?>
                <tr><td colspan="5" class="text-center">Aucun type de congé.</td></tr>
            <?php else: ?>
                <?php foreach ($types as $t): ?>
                    <tr>
                        <td class="td-muted"><?= $t['id'] ?></td>
                        <td><strong><?= $t['libelle'] ?></strong></td>
                        <td class="td-mono"><?= $t['jours_annuels'] ?> j</td>
                        <td>
                            <span class="statut s-<?= $t['deductible'] == 1 ? 'approuvee' : 'annulee' ?>">
                                <?= $t['deductible'] == 1 ? 'Oui' : 'Non' ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-row">
                                <a href="<?= base_url('admin/types-conge/edit/'.$t['id']) ?>" class="btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <a href="<?= base_url('admin/types-conge/delete/'.$t['id']) ?>" class="btn-outline-danger" onclick="return confirm('Supprimer ce type de congé ?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Nouveau Type -->
<div class="f-modal-overlay" id="modalType">
    <div class="f-modal">
        <div class="f-modal-head">
            <h5>Ajouter un type de congé</h5>
            <button type="button" class="f-modal-close" onclick="document.getElementById('modalType').classList.remove('show')">&times;</button>
        </div>
        <form action="<?= base_url('admin/types-conge/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="f-modal-body">
                <div class="f-group">
                    <label class="f-label">Libellé</label>
                    <input type="text" name="libelle" class="f-input" required>
                </div>
                <div class="f-group">
                    <label class="f-label">Jours annuels</label>
                    <input type="number" name="jours_annuels" class="f-input" required min="1">
                </div>
                <div class="f-group">
                    <label class="f-label">Déductible du solde</label>
                    <select name="deductible" class="f-select">
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
            </div>
            <div class="f-modal-foot">
                <button type="button" class="btn-secondary" onclick="document.getElementById('modalType').classList.remove('show')">Fermer</button>
                <button type="submit" class="btn-forest">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
