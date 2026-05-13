<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Connexion - TechMada RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:      #1c2b1e;
            --forest:   #2d5a3d;
            --forest2:  #3d7a52;
            --leaf:     #5fa876;
            --mint:     #d4ede0;
            --cream:    #f8f6f1;
            --white:    #ffffff;
            --border:   #dde8e1;
            --muted:    #7a8f80;
        }
        body{font-family:'Manrope',sans-serif;background:var(--cream);color:var(--ink);margin:0;font-size:15px}
        .auth-page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;background:var(--ink)}
        .auth-split{display:grid;grid-template-columns:1fr 420px;max-width:900px;width:100%;border-radius:16px;overflow:hidden;background:var(--white)}
        .auth-left{background:var(--forest);padding:3rem;display:flex;flex-direction:column;justify-content:space-between}
        .auth-left-brand{font-family:'Playfair Display',serif;font-size:1.6rem;color:var(--white);letter-spacing:-.5px;margin:0}
        .auth-left-brand span{display:block;font-size:.85rem;font-weight:300;font-family:'Manrope',sans-serif;color:rgba(255,255,255,.5);margin-top:4px;letter-spacing:0}
        .auth-left-text{color:rgba(255,255,255,.6);font-size:.875rem;line-height:1.7}
        .auth-left-text strong{color:var(--white);display:block;font-size:1.25rem;font-family:'Playfair Display',serif;margin-bottom:.5rem}
        .auth-right{padding:2.5rem}
        .auth-title{font-size:1.3rem;font-weight:700;margin:0 0 .25rem; font-family:'Playfair Display',serif;}
        .auth-sub{font-size:.85rem;color:var(--muted);margin:0 0 1.75rem}
        .f-label{font-size:.8rem;font-weight:500;color:var(--ink);margin-bottom:5px;display:block}
        .f-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;background:var(--white);color:var(--ink);transition:border-color .15s,box-shadow .15s}
        .f-input:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(45,90,61,.1);outline:none}
        .f-group{margin-bottom:1rem}
        .btn-primary{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:11px 20px;font-weight:500;font-size:.9rem;cursor:pointer;transition:background .15s;width:100%}
        .btn-primary:hover{background:var(--forest2)}
        
        /* Roles quick connect */
        .auth-roles{display:flex;flex-direction:column;gap:8px;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border)}
        .role-btn{background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:8px 12px;display:flex;align-items:center;gap:10px;width:100%;text-align:left;cursor:pointer;transition:all 0.2s;text-decoration:none;color:var(--ink)}
        .role-btn:hover{background:var(--mint);border-color:var(--leaf)}
        .role-btn i{color:var(--forest);font-size:1.1rem}
        .role-name{font-size:.8rem;font-weight:600}
        .role-cred{font-size:.72rem;color:var(--muted);font-family:'Courier New',monospace}
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-split">
            <div class="auth-left">
                <h1 class="auth-left-brand">TechMada<span>Système RH Interne</span></h1>
                <div class="auth-left-text">
                    <strong>Simplifiez la gestion de vos congés.</strong>
                    Accédez à votre espace pour soumettre vos demandes, consulter votre solde ou valider les absences de votre équipe.
                </div>
                
                <div style="margin-top: 2rem;">
                    <p style="color: var(--white); font-size: 0.8rem; margin-bottom: 0.5rem; font-weight: 500;">Comptes de test (cliquer pour remplir) :</p>
                    <div class="auth-roles">
                        <button type="button" class="role-btn" onclick="fillForm('admin@techmada.mg', 'admin123')">
                            <i class="bi bi-shield-lock"></i>
                            <div>
                                <div class="role-name">Administrateur</div>
                                <div class="role-cred">admin@techmada.mg / admin123</div>
                            </div>
                        </button>
                        <button type="button" class="role-btn" onclick="fillForm('rh@techmada.mg', 'rh123')">
                            <i class="bi bi-person-check"></i>
                            <div>
                                <div class="role-name">Responsable RH</div>
                                <div class="role-cred">rh@techmada.mg / rh123</div>
                            </div>
                        </button>
                        <button type="button" class="role-btn" onclick="fillForm('jean.dupont@techmada.mg', 'user123')">
                            <i class="bi bi-person"></i>
                            <div>
                                <div class="role-name">Employé</div>
                                <div class="role-cred">jean.dupont@techmada.mg / user123</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="auth-right">
                <h3 class="auth-title">Connexion</h3>
                <p class="auth-sub">Entrez vos identifiants pour continuer.</p>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" style="font-size: .8rem;"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('/login') ?>" method="post" id="loginForm">
                    <?= csrf_field() ?>
                    <div class="f-group">
                        <label class="f-label">Adresse email</label>
                        <input type="email" name="email" id="email" class="f-input" placeholder="nom@techmada.mg" required>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Mot de passe</label>
                        <input type="password" name="password" id="password" class="f-input" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fillForm(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
