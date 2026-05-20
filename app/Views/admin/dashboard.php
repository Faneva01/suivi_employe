<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="data-card p-4 text-center">
            <div style="font-size: .8rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em;">Employés inscrits</div>
            <div style="font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 700; color: var(--info); line-height: 1;"><?= $totalEmployes ?></div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="data-card p-4 text-center" style="background: var(--warn-bg); border-color: var(--warn-br);">
            <div style="font-size: .8rem; color: var(--warn); text-transform: uppercase; letter-spacing: .05em;">Demandes en attente</div>
            <div style="font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 700; color: var(--warn); line-height: 1;"><?= $demandesAttente ?></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="data-card">
            <div class="data-card-head">
                <h3>Congés par mois (<?= date('Y') ?>)</h3>
            </div>
            <div class="p-3">
                <canvas id="chartMois" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="data-card">
            <div class="data-card-head">
                <h3>Distribution par jour</h3>
            </div>
            <div class="p-3">
                <canvas id="chartJours" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="data-card-head">
        <h3>Absences du mois en cours</h3>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Type</th>
                <th>Dates</th>
                <th>Jours</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($absencesMois)): ?>
                <tr><td colspan="4" class="text-center">Aucune absence ce mois-ci.</td></tr>
            <?php else: ?>
                <?php foreach ($absencesMois as $a): ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="avatar av-green" style="width:28px;height:28px;font-size:.6rem"><?= strtoupper(substr($a['prenom'],0,1).substr($a['nom'],0,1)) ?></div>
                                <span style="font-weight:500"><?= $a['prenom'] ?> <?= $a['nom'] ?></span>
                            </div>
                        </td>
                        <td><span class="type-badge"><?= $a['libelle'] ?></span></td>
                        <td class="td-muted">Du <?= date('d/m/Y', strtotime($a['date_debut'])) ?> au <?= date('d/m/Y', strtotime($a['date_fin'])) ?></td>
                        <td class="td-mono"><?= $a['nb_jours'] ?> j</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxMois = document.getElementById('chartMois').getContext('2d');
    new Chart(ctxMois, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Nombre de congés',
                data: <?= json_encode($chartMois) ?>,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        stepSize: 1,
                        font: { family: 'Manrope' }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Manrope' } }
                }
            }
        }
    });

    const ctxJours = document.getElementById('chartJours').getContext('2d');
    new Chart(ctxJours, {
        type: 'doughnut',
        data: {
            labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
            datasets: [{
                data: <?= json_encode($chartJours) ?>,
                backgroundColor: [
                    '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280', '#ec4899'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        boxWidth: 8, 
                        usePointStyle: true,
                        font: { size: 10, family: 'Manrope' } 
                    } 
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>
