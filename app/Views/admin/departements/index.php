<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4" style="align-items:center;">
    <div class="col">
        <h3 class="page-title" style="margin:0;">Gestion des départements</h3>
    </div>
    <div class="col text-end">
        <button type="button" class="btn-forest" onclick="document.getElementById('modalDep').classList.add('show')">
            <i class="bi bi-plus-lg"></i> Nouveau département
        </button>
    </div>
</div>

<div class="data-card">
    <table class="tbl">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($departements)): ?>
                <tr><td colspan="3" class="text-center">Aucun département.</td></tr>
            <?php else: ?>
                <?php foreach ($departements as $d): ?>
                    <tr>
                        <td class="td-muted"><?= $d['id'] ?></td>
                        <td><strong><?= $d['nom'] ?></strong></td>
                        <td class="td-muted"><?= $d['description'] ?? '—' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Nouveau Département -->
<div class="f-modal-overlay" id="modalDep">
    <div class="f-modal">
        <div class="f-modal-head">
            <h5>Ajouter un département</h5>
            <button type="button" class="f-modal-close" onclick="document.getElementById('modalDep').classList.remove('show')">&times;</button>
        </div>
        <form action="<?= base_url('admin/departements/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="f-modal-body">
                <div class="f-group">
                    <label class="f-label">Nom du département</label>
                    <input type="text" name="nom" class="f-input" required>
                </div>
                <div class="f-group">
                    <label class="f-label">Description</label>
                    <textarea name="description" class="f-input" rows="3"></textarea>
                </div>
            </div>
            <div class="f-modal-foot">
                <button type="button" class="btn-secondary" onclick="document.getElementById('modalDep').classList.remove('show')">Fermer</button>
                <button type="submit" class="btn-forest">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
