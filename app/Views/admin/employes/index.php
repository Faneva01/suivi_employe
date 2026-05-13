<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-4">
        <div class="f-card">
            <div class="f-card-body">
                <h3 class="page-title" style="font-size:1.1rem;margin-bottom:.5rem;">Ajouter un employé</h3>
                <p class="page-sub" style="margin-bottom:1.25rem;">Remplissez les informations ci-dessous.</p>
                <form action="<?= base_url('admin/employes/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="f-group">
                        <label class="f-label">Nom</label>
                        <input type="text" name="nom" class="f-input" required value="<?= old('nom') ?>">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Prénom</label>
                        <input type="text" name="prenom" class="f-input" required value="<?= old('prenom') ?>">
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
                    <button type="submit" class="btn-forest w-100">Créer l'employé</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="data-card">
            <div class="data-card-head">
                <h3>Tous les employés</h3>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Département</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employes as $e): ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px">
                                    <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem"><?= strtoupper(substr($e['prenom'],0,1).substr($e['nom'],0,1)) ?></div>
                                    <div style="line-height:1.2">
                                        <div style="font-weight:500"><?= $e['prenom'] ?> <?= $e['nom'] ?></div>
                                        <div style="font-size:.75rem;color:var(--muted)"><?= $e['email'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-muted"><?= $e['dep_nom'] ?? 'N/A' ?></td>
                            <td><span class="type-badge"><?= $e['role'] ?></span></td>
                            <td>
                                <span class="statut s-<?= $e['actif'] == 1 ? 'approuvee' : 'annulee' ?>">
                                    <?= $e['actif'] == 1 ? 'actif' : 'inactif' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-row">
                                    <a href="<?= base_url('admin/employes/edit/'.$e['id']) ?>" class="btn-outline-primary"><i class="bi bi-pencil"></i> Modifier</a>
                                    <a href="<?= base_url('admin/employes/toggle/'.$e['id']) ?>" class="btn-outline-<?= $e['actif'] == 1 ? 'warning' : 'success' ?>"><i class="bi bi-power"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
