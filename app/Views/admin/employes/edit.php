<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="f-breadcrumb">
    <a href="<?= base_url('admin/employes') ?>">Employés</a>
    <span class="sep">/</span>
    <span class="current">Modifier</span>
</div>
<h3 class="page-title">Modifier l'employé</h3>
<p class="page-sub"><?= $employe['prenom'] ?> <?= $employe['nom'] ?></p>

<div class="row">
    <div class="col-md-8">
        <div class="f-card">
            <div class="f-card-body">
                <form action="<?= base_url('admin/employes/update/'.$employe['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 f-group">
                            <label class="f-label">Nom</label>
                            <input type="text" name="nom" class="f-input" required value="<?= old('nom', $employe['nom']) ?>">
                        </div>
                        <div class="col-md-6 f-group">
                            <label class="f-label">Prénom</label>
                            <input type="text" name="prenom" class="f-input" required value="<?= old('prenom', $employe['prenom']) ?>">
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Email</label>
                        <input type="email" name="email" class="f-input" required value="<?= old('email', $employe['email']) ?>">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" name="password" class="f-input">
                    </div>
                    <div class="row">
                        <div class="col-md-6 f-group">
                            <label class="f-label">Rôle</label>
                            <select name="role" class="f-select" required>
                                <option value="employe" <?= $employe['role'] === 'employe' ? 'selected' : '' ?>>Employé</option>
                                <option value="rh" <?= $employe['role'] === 'rh' ? 'selected' : '' ?>>Responsable RH</option>
                                <option value="admin" <?= $employe['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                            </select>
                        </div>
                        <div class="col-md-6 f-group">
                            <label class="f-label">Département</label>
                            <select name="departement_id" class="f-select" required>
                                <?php foreach ($departements as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= $employe['departement_id'] == $d['id'] ? 'selected' : '' ?>><?= $d['nom'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date d'embauche</label>
                        <input type="date" name="date_embauche" class="f-input" required value="<?= old('date_embauche', $employe['date_embauche']) ?>">
                    </div>
                    <hr style="border-color:var(--border);margin:1.5rem 0;">
                    <div class="btn-row" style="justify-content:flex-end;">
                        <a href="<?= base_url('admin/employes') ?>" class="btn-secondary">Annuler</a>
                        <button type="submit" class="btn-forest">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
