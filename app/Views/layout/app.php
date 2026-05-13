<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?= $title ?? 'TechMada RH' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink:      #1c2b1e;
            --forest:   #2d5a3d;
            --forest2:  #3d7a52;
            --leaf:     #5fa876;
            --mint:     #d4ede0;
            --cream:    #f8f6f1;
            --white:    #ffffff;
            --border:   #dde8e1;
            --muted:    #7a8f80;
            --danger:   #c0392b;
            --danger-bg:#fdf0ee;
            --danger-br:#f0b8b2;
            --warn:     #b8750a;
            --warn-bg:  #fef9ee;
            --warn-br:  #f5d98a;
            --success:  #1e6b3f;
            --success-bg:#edf7f2;
            --success-br:#8fd4aa;
            --info:     #1a4f7a;
            --info-bg:  #eaf2fb;
            --info-br:  #8fbde8;
            --sidebar-w:240px;
            --topbar-h: 62px;
        }
        body { font-family:'Manrope',sans-serif; background:var(--cream); color:var(--ink); margin:0; font-size:15px; }
        h1,h2,h3,.brand-name { font-family:'Playfair Display',serif; }
        
        /* Custom Select Styling */
        .f-select {
            display: block;
            width: 100%;
            padding: 10px 12px;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--ink);
            background-color: var(--white);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231c2b1e' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            appearance: none;
        }
        .f-select:focus {
            border-color: var(--forest);
            outline: 0;
            box-shadow: 0 0 0 3px rgba(45,90,61,.1);
        }
        
        /* Form inputs */
        .f-input {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: .875rem;
            background: var(--white);
            color: var(--ink);
            transition: border-color .15s, box-shadow .15s;
        }
        .f-input:focus {
            border-color: var(--forest);
            outline: none;
            box-shadow: 0 0 0 3px rgba(45,90,61,.1);
        }
        
        .f-label {
            font-size: .8rem;
            font-weight: 500;
            color: var(--ink);
            margin-bottom: 5px;
            display: block;
        }
        
        .f-group { margin-bottom: 1rem; }
        .app-wrap { display:flex; min-height:100vh; }
        .sidebar { width:var(--sidebar-w); background:var(--ink); display:flex; flex-direction:column; flex-shrink:0; position:sticky; top:0; height:100vh; overflow-y:auto; }
        .sidebar-brand { padding:1.4rem 1.2rem 1rem; display:flex; align-items:center; gap:10px; border-bottom:1px solid rgba(255,255,255,.06); }
        .sidebar-logo-icon { width:34px; height:34px; background:var(--forest); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .sidebar-logo-icon i { color:var(--white); font-size:1.1rem; }
        .sidebar-brand-name { font-family:'Playfair Display',serif; font-size:1rem; color:var(--white); line-height:1.2; }
        .sidebar-brand-name span { display:block; font-size:.65rem; font-family:'DM Sans',sans-serif; font-weight:400; color:rgba(255,255,255,.35); letter-spacing:.05em; text-transform:uppercase; }
        .sidebar-section { padding:.75rem 1.1rem .3rem; font-size:.62rem; font-weight:500; letter-spacing:1.4px; text-transform:uppercase; color:rgba(255,255,255,.25); margin-top:.25rem; }
        .sidebar-nav { list-style:none; padding:0 .75rem; margin:0; }
        .sidebar-nav li a { display:flex; align-items:center; gap:9px; padding:9px 11px; border-radius:7px; color:rgba(255,255,255,.55); text-decoration:none; font-size:.85rem; transition:all .15s; }
        .sidebar-nav li a:hover { background:rgba(255,255,255,.06); color:rgba(255,255,255,.9); }
        .sidebar-nav li a.active { background:var(--forest); color:var(--white); }
        .sidebar-user { padding:.85rem .75rem; border-top:1px solid rgba(255,255,255,.06); margin-top:auto; }
        .s-user-row { display:flex; align-items:center; gap:9px; padding:9px 11px; border-radius:7px; }
        .avatar { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:500; color:var(--white); flex-shrink:0; }
        .av-green { background:var(--forest2); }
        .av-blue { background:#1a4f7a; }
        .av-amber { background:#b8750a; }
        .user-name { font-size:.825rem; font-weight:500; color:var(--white); line-height:1.2; }
        .user-role { font-size:.65rem; color:rgba(255,255,255,.35); text-transform:uppercase; }
        .main { flex:1; min-width:0; display:flex; flex-direction:column; }
        .topbar { height:var(--topbar-h); background:var(--white); border-bottom:1px solid var(--border); display:flex; align-items:center; padding:0 1.75rem; gap:1rem; position:sticky; top:0; z-index:10; }
        .topbar-title { font-family:'Playfair Display',serif; font-size:1.05rem; font-weight:600; color:var(--ink); }
        .content { padding:1.75rem; flex:1; }
        .data-card { background:var(--white); border:1.5px solid var(--border); border-radius:12px; margin-bottom:1.5rem; overflow:hidden; }
        .data-card-head { padding:1.1rem 1.4rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .data-card-head h3 { font-size:.95rem; font-weight:700; margin:0; }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl th { background:var(--cream); padding:10px 1.4rem; font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); text-align:left; border-bottom:1.5px solid var(--border); }
        .tbl td { padding:12px 1.4rem; border-bottom:1px solid var(--border); vertical-align:middle; font-size:.85rem; }
        .statut { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.03em; padding:3px 8px; border-radius:5px; }
        .s-attente { background:var(--warn-bg); color:var(--warn); border:1px solid var(--warn-br); }
        .s-approuvee { background:var(--success-bg); color:var(--success); border:1px solid var(--success-br); }
        .s-refusee { background:var(--danger-bg); color:var(--danger); border:1px solid var(--danger-br); }
        .s-annulee { background:#f1efe8; color:#7a7a77; border:1px solid #d4d2cc; }
        .btn-forest { background:var(--forest); color:var(--white); border:none; border-radius:8px; padding:9px 18px; font-weight:500; cursor:pointer; text-decoration:none; }
        .btn-forest:hover { background:var(--forest2); color:var(--white); }
        .flash { padding:12px 1.4rem; border-radius:8px; margin-bottom:1.5rem; font-size:.875rem; display:flex; align-items:center; gap:10px; }
        .flash-success { background:var(--success-bg); color:var(--success); border:1px solid var(--success-br); }
        .flash-error { background:var(--danger-bg); color:var(--danger); border:1px solid var(--danger-br); }
    </style>
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
                <?php endif; ?>

                <?php if (session()->get('role') === 'rh'): ?>
                    <li><a href="<?= base_url('rh/demandes') ?>" class="<?= url_is('rh/demandes*') ? 'active' : '' ?>"><i class="bi bi-inbox"></i> Demandes à traiter</a></li>
                <?php endif; ?>

                <?php if (session()->get('role') === 'admin'): ?>
                    <li><a href="<?= base_url('admin/employes') ?>" class="<?= url_is('admin/employes*') ? 'active' : '' ?>"><i class="bi bi-people"></i> Employés</a></li>
                    <li><a href="<?= base_url('admin/departements') ?>" class="<?= url_is('admin/departements*') ? 'active' : '' ?>"><i class="bi bi-building"></i> Départements</a></li>
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
                    <a href="<?= base_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);"><i class="bi bi-box-arrow-right"></i></a>
                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
