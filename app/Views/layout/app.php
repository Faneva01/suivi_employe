<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'TechMada RH' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { height: 100vh; background: #2c3e50; color: white; padding-top: 20px; position: fixed; width: 250px; }
        .sidebar a { color: #bdc3c7; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: white; }
        .content { margin-left: 250px; padding: 20px; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center mb-4">TechMada</h4>
        <a href="<?= base_url(session()->get('role')) ?>" class="<?= url_is(session()->get('role')) ? 'active' : '' ?>">
            <i class="fas fa-home me-2"></i> Dashboard
        </a>
        
        <?php if (session()->get('role') === 'employe'): ?>
            <a href="<?= base_url('employe/conges') ?>" class="<?= url_is('employe/conges*') ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt me-2"></i> Mes Congés
            </a>
        <?php endif; ?>

        <?php if (session()->get('role') === 'rh'): ?>
            <a href="<?= base_url('rh/demandes') ?>" class="<?= url_is('rh/demandes*') ? 'active' : '' ?>">
                <i class="fas fa-tasks me-2"></i> Demandes à traiter
            </a>
        <?php endif; ?>

        <?php if (session()->get('role') === 'admin'): ?>
            <a href="<?= base_url('admin/employes') ?>" class="<?= url_is('admin/employes*') ? 'active' : '' ?>">
                <i class="fas fa-users me-2"></i> Employés
            </a>
            <a href="<?= base_url('admin/departements') ?>" class="<?= url_is('admin/departements*') ? 'active' : '' ?>">
                <i class="fas fa-building me-2"></i> Départements
            </a>
        <?php endif; ?>

        <hr>
        <div class="px-3 small mb-2 text-muted">Utilisateur : <?= session()->get('prenom') ?></div>
        <a href="<?= base_url('logout') ?>" class="text-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
        </a>
    </div>

    <div class="content">
        <div class="container-fluid">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
