<?= $this->extend('layout/app') ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/vendor/fullcalendar/main.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/employe.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/calendar.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="f-breadcrumb">
    <a href="<?= base_url('employe') ?>">Dashboard</a>
    <span class="sep">/</span>
    <span class="current">Calendrier & statistiques</span>
</div>

<div class="calendar-page-head">
    <h1 class="page-title">Calendrier hebdomadaire interactif</h1>
    <p class="page-sub">Visualisez vos congés, consultez vos statistiques et ajoutez une nouvelle demande directement depuis le calendrier.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="employee-stat-card">
            <div class="employee-stat-icon icon-forest">
                <i class="bi bi-calendar2-check"></i>
            </div>
            <div class="employee-stat-value"><?= esc($resume_stats['total_demandes']) ?></div>
            <div class="employee-stat-label">Demandes totales</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="employee-stat-card">
            <div class="employee-stat-icon icon-warn">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="employee-stat-value"><?= esc($resume_stats['en_attente']) ?></div>
            <div class="employee-stat-label">En attente</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="employee-stat-card">
            <div class="employee-stat-icon icon-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="employee-stat-value"><?= esc($resume_stats['approuvees']) ?></div>
            <div class="employee-stat-label">Approuvées</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="employee-stat-card">
            <div class="employee-stat-icon icon-info">
                <i class="bi bi-bar-chart"></i>
            </div>
            <div class="employee-stat-value"><?= esc($resume_stats['jours_demandes']) ?></div>
            <div class="employee-stat-label">Jours demandés</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="data-card calendar-card">
            <div class="data-card-head">
                <div>
                    <h3>Mon calendrier</h3>
                    <p class="calendar-card-subtitle">Cliquez sur une date ou sélectionnez une plage pour préparer une demande.</p>
                </div>
                <span class="calendar-help-badge"><i class="bi bi-mouse"></i> Ajout rapide</span>
            </div>
            <div class="calendar-card-body">
                <div id="calendarFormFeedback" class="calendar-feedback d-none"></div>
                <div id="employe-calendar"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="data-card">
            <div class="data-card-head">
                <h3>Demandes par type</h3>
            </div>
            <div class="chart-card-body">
                <canvas id="typeCongeChart"></canvas>
            </div>
        </div>

        <div class="data-card">
            <div class="data-card-head">
                <h3>Résumé par type</h3>
            </div>
            <div class="type-stats-list">
                <?php foreach ($stats_par_type as $type_stat): ?>
                    <div class="type-stat-row">
                        <div>
                            <div class="type-stat-name"><?= esc($type_stat['libelle']) ?></div>
                            <div class="type-stat-meta"><?= esc($type_stat['total_jours']) ?> jour(s) demandé(s)</div>
                        </div>
                        <div class="type-stat-total">
                            <span><?= esc($type_stat['total_demandes']) ?></span>
                            <small>demande(s)</small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="data-card mt-4">
    <div class="data-card-head">
        <h3>Historique récent</h3>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Type</th>
                <th>Période</th>
                <th>Durée</th>
                <th>Statut</th>
                <th>Motif</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($historique_conges)): ?>
                <tr>
                    <td colspan="5" class="text-center">Aucune demande enregistrée pour le moment.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($historique_conges as $conge): ?>
                    <tr>
                        <td><span class="type-badge"><?= esc($conge['type_libelle']) ?></span></td>
                        <td class="td-muted">Du <?= date('d/m/Y', strtotime($conge['date_debut'])) ?> au <?= date('d/m/Y', strtotime($conge['date_fin'])) ?></td>
                        <td class="td-mono"><?= esc($conge['nb_jours']) ?> j</td>
                        <td><span class="statut s-<?= esc($conge['statut']) ?>"><?= esc($conge['statut']) ?></span></td>
                        <td class="td-muted"><?= esc($conge['motif'] ?: 'Aucun motif') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="calendarCongeModal" class="f-modal-overlay">
    <div class="f-modal">
        <div class="f-modal-head">
            <h5>Nouvelle demande depuis le calendrier</h5>
            <button type="button" class="f-modal-close" id="calendarModalCloseButton">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="f-modal-body">
            <div class="calendar-modal-note">
                Les dates sélectionnées sont préremplies. Vous pouvez encore les ajuster avant l'envoi.
            </div>

            <div id="calendarModalFeedback" class="calendar-feedback d-none"></div>

            <form id="calendarCongeForm" action="<?= base_url('employe/api/conges') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="f-label">Type de congé</label>
                    <select name="type_conge_id" class="f-select" required>
                        <?php foreach ($soldes as $solde): ?>
                            <option value="<?= esc($solde['type_conge_id']) ?>">
                                <?= esc($solde['libelle']) ?> (<?= esc((int) $solde['jours_attribues'] - (int) $solde['jours_pris']) ?> j dispo)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="f-label">Date de début</label>
                        <input type="date" name="date_debut" id="calendarDateDebut" class="f-input" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="f-label">Date de fin</label>
                        <input type="date" name="date_fin" id="calendarDateFin" class="f-input" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="f-label">Motif</label>
                    <textarea name="motif" id="calendarMotif" class="f-input" rows="3" placeholder="Précisez si besoin le motif de votre demande..."></textarea>
                </div>

                <div class="btn-row justify-content-end">
                    <button type="button" class="btn-secondary" id="calendarCancelButton">Annuler</button>
                    <button type="submit" class="btn-forest" id="calendarSubmitButton">Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
window.calendarConfig = {
    locale: 'fr',
    events: <?= json_encode($calendar_events, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
    typeStats: <?= json_encode($stats_par_type, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
};
</script>
<script src="<?= base_url('assets/vendor/chartjs/chart.umd.js') ?>"></script>
<script src="<?= base_url('assets/vendor/fullcalendar/main.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/fullcalendar/locales-all.min.js') ?>"></script>
<script src="<?= base_url('assets/js/calendar.js') ?>"></script>
<?= $this->endSection() ?>
