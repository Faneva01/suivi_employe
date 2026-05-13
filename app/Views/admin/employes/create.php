<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="f-breadcrumb">
    <a href="<?= base_url('admin/employes') ?>">Employés</a>
    <span class="sep">/</span>
    <span class="current">Ajouter</span>
</div>
<h3 class="page-title">Ajouter un nouvel employé</h3>
<p class="page-sub">Remplissez les informations ci-dessous.</p>

<div class="row">
    <div class="col-md-8">
        <div class="f-card">
            <div class="f-card-body">
                <form action="<?= base_url('admin/employes/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 f-group">
                            <label class="f-label">Nom</label>
                            <input type="text" name="nom" class="f-input" required value="<?= old('nom') ?>">
                        </div>
                        <div class="col-md-6 f-group">
                            <label class="f-label">Prénom</label>
                            <input type="text" name="prenom" class="f-input" required value="<?= old('prenom') ?>">
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Email</label>
                        <input type="email" name="email" class="f-input" required value="<?= old('email') ?>">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Mot de passe</label>
                        <input type="password" name="password" class="f-input" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 f-group">
                            <label class="f-label">Rôle</label>
                            <select name="role" class="f-select" required>
                                <option value="employe">Employé</option>
                                <option value="rh">Responsable RH</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="col-md-6 f-group">
                            <label class="f-label">Département</label>
                            <select name="departement_id" class="f-select" required>
                                <?php foreach ($departements as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['nom'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Date d'embauche</label>
                        <input type="date" name="date_embauche" class="f-input" required value="<?= old('date_embauche', date('Y-m-d')) ?>">
                    </div>
                    <hr style="border-color:var(--border);margin:1.5rem 0;">
                    <div class="btn-row" style="justify-content:flex-end;">
                        <a href="<?= base_url('admin/employes') ?>" class="btn-secondary">Annuler</a>
                        <button type="submit" class="btn-forest">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
