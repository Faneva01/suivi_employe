<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= $title ?? 'TechMada RH' ?></title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fonts/fonts.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</head>

<body>
    <div class="app-wrap">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
                <div class="sidebar-brand-name">TechMada RH<span><?= ucfirst(session()->get('role')) ?></span></div>
            </div>
            
            <div class="sidebar-section">Menu</div>
            <ul class="sidebar-nav">
                <li><a href="<?= base_url(session()->get('role')) ?>" class="<?= url_is(session()->get('role')) ? 'active' : '' ?>"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
                
                <?php if (session()->get('role') === 'employe'): ?>
                    <li><a href="<?= base_url('employe/conges') ?>" class="<?= url_is('employe/conges*') ? 'active' : '' ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
                    <li><a href="<?= base_url('employe/calendar') ?>" class="<?= url_is('employe/calendar*') ? 'active' : '' ?>"><i class="bi bi-calendar-week"></i> Calendrier</a></li>
                    <li><a href="<?= base_url('employe/profil') ?>" class="<?= url_is('employe/profil*') ? 'active' : '' ?>"><i class="bi bi-person"></i> Mon profil</a></li>
                <?php endif; ?>

                <?php if (session()->get('role') === 'rh'): ?>
                    <li><a href="<?= base_url('rh/demandes') ?>" class="<?= url_is('rh/demandes*') ? 'active' : '' ?>"><i class="bi bi-inbox"></i> Demandes</a></li>
                    <li><a href="<?= base_url('rh/soldes') ?>" class="<?= url_is('rh/soldes*') ? 'active' : '' ?>"><i class="bi bi-pie-chart"></i> Soldes</a></li>
                <?php endif; ?>

                <?php if (session()->get('role') === 'admin'): ?>
                    <li><a href="<?= base_url('admin/employes') ?>" class="<?= url_is('admin/employes*') ? 'active' : '' ?>"><i class="bi bi-people"></i> Employés</a></li>
                    <li><a href="<?= base_url('admin/departements') ?>" class="<?= url_is('admin/departements*') ? 'active' : '' ?>"><i class="bi bi-building"></i> Départements</a></li>
                    <li><a href="<?= base_url('admin/types-conge') ?>" class="<?= url_is('admin/types-conge*') ? 'active' : '' ?>"><i class="bi bi-tags"></i> Types de congé</a></li>
                    <li><a href="<?= base_url('admin/soldes') ?>" class="<?= url_is('admin/soldes*') ? 'active' : '' ?>"><i class="bi bi-pie-chart"></i> Soldes</a></li>
                    <li><a href="<?= base_url('admin/historique') ?>" class="<?= url_is('admin/historique*') ? 'active' : '' ?>"><i class="bi bi-clock-history"></i> Historique</a></li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-user">
                <div class="s-user-row">
                    <div class="avatar <?= session()->get('role') === 'admin' ? 'av-blue' : (session()->get('role') === 'rh' ? 'av-amber' : 'av-green') ?>">
                        <?= strtoupper(substr(session()->get('prenom'), 0, 1) . substr(session()->get('nom'), 0, 1)) ?>
                    </div>
                    <div>
                        <div class="user-name"><?= session()->get('prenom') ?> <?= session()->get('nom') ?></div>
                        <div class="user-role"><?= session()->get('role') ?></div>
                    </div>
                </div>
                <a href="<?= base_url('logout') ?>" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </aside>

        <div class="main">
            <div class="topbar">
                <div class="topbar-title"><?= $title ?? 'TechMada RH' ?></div>
            </div>

            <div class="content">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="flash flash-success">
                        <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="flash flash-error">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
