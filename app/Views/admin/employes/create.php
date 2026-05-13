<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/employes') ?>">Employés</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
          </ol>
        </nav>
        <h3>Ajouter un Nouvel Employé</h3>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('admin/employes/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required value="<?= old('nom') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required value="<?= old('prenom') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="<?= old('email') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rôle</label>
                            <select name="role" class="form-select" required>
                                <option value="employe">Employé</option>
                                <option value="rh">Responsable RH</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Département</label>
                            <select name="departement_id" class="form-select" required>
                                <?php foreach ($departements as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= $d['nom'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date d'embauche</label>
                        <input type="date" name="date_embauche" class="form-control" required value="<?= old('date_embauche', date('Y-m-d')) ?>">
                    </div>
                    <hr>
                    <div class="text-end">
                        <a href="<?= base_url('admin/employes') ?>" class="btn btn-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
