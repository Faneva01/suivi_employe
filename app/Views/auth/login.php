<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Connexion - TechMada RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/login.css') ?>" rel="stylesheet">
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
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
