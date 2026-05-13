<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="f-card">
            <div class="f-card-body">
                <h3 class="page-title" style="font-size:1.1rem;margin-bottom:.5rem;">Mon Profil</h3>
                <p class="page-sub" style="margin-bottom:1.25rem;">Modifiez vos informations personnelles.</p>
                
                <form action="<?= base_url('employe/profil/update') ?>" method="post">
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
                        <input type="email" class="f-input" value="<?= $employe['email'] ?>" disabled>
                        <small style="color:var(--muted);font-size:.75rem;">L'email ne peut pas être modifié.</small>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Département</label>
                        <input type="text" class="f-input" value="<?= $employe['departement_id'] ?>" disabled>
                    </div>
                    <hr style="border-color:var(--border);margin:1.5rem 0;">
                    <div class="f-group">
                        <label class="f-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" name="password" class="f-input" placeholder="••••••••">
                    </div>
                    <div class="btn-row" style="justify-content:flex-end;">
                        <button type="submit" class="btn-forest">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
