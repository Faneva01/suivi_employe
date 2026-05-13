<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="f-breadcrumb">
    <a href="<?= base_url('admin/types-conge') ?>">Types de congé</a>
    <span class="sep">/</span>
    <span class="current">Modifier</span>
</div>
<h3 class="page-title">Modifier le type de congé</h3>
<p class="page-sub"><?= $type['libelle'] ?></p>

<div class="row">
    <div class="col-md-8">
        <div class="f-card">
            <div class="f-card-body">
                <form action="<?= base_url('admin/types-conge/update/'.$type['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="f-group">
                        <label class="f-label">Libellé</label>
                        <input type="text" name="libelle" class="f-input" required value="<?= old('libelle', $type['libelle']) ?>">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Jours annuels</label>
                        <input type="number" name="jours_annuels" class="f-input" required min="1" value="<?= old('jours_annuels', $type['jours_annuels']) ?>">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Déductible du solde</label>
                        <select name="deductible" class="f-select">
                            <option value="1" <?= $type['deductible'] == 1 ? 'selected' : '' ?>>Oui</option>
                            <option value="0" <?= $type['deductible'] == 0 ? 'selected' : '' ?>>Non</option>
                        </select>
                    </div>
                    <hr style="border-color:var(--border);margin:1.5rem 0;">
                    <div class="btn-row" style="justify-content:flex-end;">
                        <a href="<?= base_url('admin/types-conge') ?>" class="btn-secondary">Annuler</a>
                        <button type="submit" class="btn-forest">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
