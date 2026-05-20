<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Models\DepartementModel;
use CodeIgniter\HTTP\ResponseInterface;

class Employe extends BaseController
{
    public function index()
    {
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        
        $employeId = session()->get('id');
        $currentYear = date('Y');

        $data = [
            'title'      => 'Tableau de bord - Employé',
            'lastConges' => $congeModel->select('conges.*, types_conge.libelle as type_libelle')
                                      ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                                      ->where('employe_id', $employeId)
                                      ->orderBy('created_at', 'DESC')
                                      ->limit(5)
                                      ->findAll(),
            'soldes'     => $soldeModel->select('soldes.*, types_conge.libelle')
                                      ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                                      ->where('employe_id', $employeId)
                                      ->where('annee', $currentYear)
                                      ->findAll()
        ];

        return view('employe/dashboard', $data);
    }

    public function conges()
    {
        $congeModel = new CongeModel();
        $typeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();

        $employeId = session()->get('id');
        $currentYear = date('Y');

        $data = [
            'title'      => 'Mes Congés',
            'conges'     => $congeModel->getEmployeConges($employeId),
            'types'      => $typeModel->findAll(),
            'soldes'     => $soldeModel->select('soldes.*, types_conge.libelle')
                                      ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                                      ->where('employe_id', $employeId)
                                      ->where('annee', $currentYear)
                                      ->findAll()
        ];

        return view('employe/conges', $data);
    }

    public function calendar()
    {
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        $employeId = (int) session()->get('id');
        $currentYear = date('Y');

        $conges = $congeModel->getEmployeConges($employeId);
        $soldes = $soldeModel->select('soldes.*, types_conge.libelle')
                             ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                             ->where('employe_id', $employeId)
                             ->where('annee', $currentYear)
                             ->orderBy('types_conge.libelle', 'ASC')
                             ->findAll();

        $statsParTypeBrutes = $congeModel->getEmployeStatsParType($employeId);
        $statsParTypeMap = [];

        foreach ($statsParTypeBrutes as $statParType) {
            $statsParTypeMap[(int) $statParType['type_conge_id']] = [
                'total_demandes' => (int) $statParType['total_demandes'],
                'total_jours'    => (int) $statParType['total_jours'],
            ];
        }

        $statsParType = [];

        foreach ($soldes as $solde) {
            $typeCongeId = (int) $solde['type_conge_id'];
            $typeStats = $statsParTypeMap[$typeCongeId] ?? [
                'total_demandes' => 0,
                'total_jours'    => 0,
            ];

            $statsParType[] = [
                'type_conge_id'  => $typeCongeId,
                'libelle'        => $solde['libelle'],
                'total_demandes' => $typeStats['total_demandes'],
                'total_jours'    => $typeStats['total_jours'],
                'jours_restants' => (int) $solde['jours_attribues'] - (int) $solde['jours_pris'],
            ];
        }

        $resumeStats = [
            'total_demandes' => count($conges),
            'en_attente'     => 0,
            'approuvees'     => 0,
            'jours_demandes' => 0,
        ];

        foreach ($conges as $conge) {
            $resumeStats['jours_demandes'] += (int) $conge['nb_jours'];

            if ($conge['statut'] === 'en_attente') {
                $resumeStats['en_attente']++;
            }

            if ($conge['statut'] === 'approuvee') {
                $resumeStats['approuvees']++;
            }
        }

        $data = [
            'title'             => 'Calendrier et statistiques',
            'resume_stats'      => $resumeStats,
            'stats_par_type'    => $statsParType,
            'historique_conges' => array_slice($conges, 0, 8),
            'calendar_events'   => array_map(fn (array $conge): array => $this->formatterEvenementCalendrier($conge), $conges),
            'soldes'            => $soldes,
        ];

        return view('employe/calendar', $data);
    }

    public function soumettre()
    {
        $resultat = $this->traiterDemandeConge([
            'type_conge_id' => $this->request->getPost('type_conge_id'),
            'date_debut'    => $this->request->getPost('date_debut'),
            'date_fin'      => $this->request->getPost('date_fin'),
            'motif'         => $this->request->getPost('motif'),
        ]);

        if (! $resultat['success']) {
            return redirect()->back()->withInput()->with('error', $resultat['message']);
        }

        return redirect()->to('/employe/conges')->with('success', $resultat['message']);
    }

    public function soumettreApi(): ResponseInterface
    {
        $payload = $this->request->getPost();

        if ($payload === []) {
            $payload = (array) ($this->request->getJSON(true) ?? []);
        }

        $resultat = $this->traiterDemandeConge($payload);
        $statusCode = $resultat['success'] ? 201 : 422;

        $resultat['csrf_token'] = csrf_token();
        $resultat['csrf_hash'] = csrf_hash();

        return $this->response
                    ->setStatusCode($statusCode)
                    ->setJSON($resultat);
    }

    public function annuler($id)
    {
        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);

        if (!$conge || $conge['employe_id'] != session()->get('id')) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        if (!in_array($conge['statut'], ['en_attente', 'approuvee'])) {
            return redirect()->back()->with('error', 'Cette demande ne peut plus être annulée.');
        }

        // Si c'était approuvé, recréditer le solde
        if ($conge['statut'] === 'approuvee') {
            $soldeModel = new SoldeModel();
            $annee = date('Y', strtotime($conge['date_debut']));
            $solde = $soldeModel->where([
                'employe_id'    => $conge['employe_id'],
                'type_conge_id' => $conge['type_conge_id'],
                'annee'         => $annee
            ])->first();

            if ($solde) {
                $soldeModel->update($solde['id'], [
                    'jours_pris' => $solde['jours_pris'] - $conge['nb_jours']
                ]);
            }
        }

        $congeModel->update($id, ['statut' => 'annulee']);

        return redirect()->back()->with('success', 'Demande annulée.');
    }

    // Profil
    public function profil()
    {
        $employeModel = new EmployeModel();
        $employeId = session()->get('id');
        $employe = $employeModel->find($employeId);

        $depModel = new DepartementModel();
        $data = [
            'title'        => 'Mon Profil',
            'employe'      => $employe,
            'departements' => $depModel->findAll()
        ];

        return view('employe/profil', $data);
    }

    public function updateProfil()
    {
        $employeModel = new EmployeModel();
        $employeId = session()->get('id');

        $data = $this->request->getPost();

        // Only allow updating certain fields
        $allowed = ['nom', 'prenom'];
        $updateData = [];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (!$employeModel->update($employeId, $updateData)) {
            return redirect()->back()->withInput()->with('errors', $employeModel->errors());
        }

        // Update session
        if (isset($updateData['nom'])) session()->set('nom', $updateData['nom']);
        if (isset($updateData['prenom'])) session()->set('prenom', $updateData['prenom']);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }

    private function traiterDemandeConge(array $payload): array
    {
        $validation = service('validation');
        $payloadNormalise = [
            'type_conge_id' => $payload['type_conge_id'] ?? null,
            'date_debut'    => $payload['date_debut'] ?? null,
            'date_fin'      => $payload['date_fin'] ?? null,
            'motif'         => trim((string) ($payload['motif'] ?? '')),
        ];

        $rules = [
            'type_conge_id' => 'required|numeric',
            'date_debut'    => 'required|valid_date',
            'date_fin'      => 'required|valid_date',
            'motif'         => 'permit_empty|max_length[1000]',
        ];

        if (! $validation->setRules($rules)->run($payloadNormalise)) {
            return [
                'success' => false,
                'message' => 'Veuillez remplir correctement le formulaire.',
                'errors'  => $validation->getErrors(),
            ];
        }

        $typeCongeId = (int) $payloadNormalise['type_conge_id'];
        $dateDebut = $payloadNormalise['date_debut'];
        $dateFin = $payloadNormalise['date_fin'];

        if (strtotime($dateDebut) > strtotime($dateFin)) {
            return [
                'success' => false,
                'message' => 'La date de début doit être antérieure ou égale à la date de fin.',
            ];
        }

        helper('date');
        $nbJours = calculate_business_days($dateDebut, $dateFin);

        if ($nbJours <= 0) {
            return [
                'success' => false,
                'message' => 'La demande doit comporter au moins 1 jour ouvrable.',
            ];
        }

        $employeId = (int) session()->get('id');
        $currentYear = date('Y');
        $typeCongeModel = new TypeCongeModel();
        $typeConge = $typeCongeModel->find($typeCongeId);

        if (! $typeConge) {
            return [
                'success' => false,
                'message' => 'Type de congé introuvable.',
            ];
        }

        $soldeModel = new SoldeModel();
        $restant = $soldeModel->getRestant($employeId, $typeCongeId, $currentYear);

        if ((int) $typeConge['deductible'] === 1 && $nbJours > $restant) {
            return [
                'success' => false,
                'message' => "Solde insuffisant ($nbJours demandés, $restant disponibles).",
            ];
        }

        $congeModel = new CongeModel();
        $chevauchement = $congeModel->where('employe_id', $employeId)
                                    ->whereIn('statut', ['en_attente', 'approuvee'])
                                    ->groupStart()
                                        ->where('date_debut <=', $dateFin)
                                        ->where('date_fin >=', $dateDebut)
                                    ->groupEnd()
                                    ->first();

        if ($chevauchement) {
            return [
                'success' => false,
                'message' => 'Vous avez déjà une demande qui chevauche ces dates.',
            ];
        }

        $congeData = [
            'employe_id'    => $employeId,
            'type_conge_id' => $typeCongeId,
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
            'nb_jours'      => $nbJours,
            'motif'         => $payloadNormalise['motif'],
            'statut'        => 'en_attente',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $congeModel->insert($congeData);
        $congeData['id'] = $congeModel->getInsertID();
        $congeData['type_libelle'] = $typeConge['libelle'];

        return [
            'success'        => true,
            'message'        => 'Demande soumise avec succès.',
            'calendar_event' => $this->formatterEvenementCalendrier($congeData),
        ];
    }

    private function formatterEvenementCalendrier(array $conge): array
    {
        $couleursStatut = $this->getCouleursCalendrierParStatut($conge['statut']);
        $dateFinExclusive = date('Y-m-d', strtotime($conge['date_fin'] . ' +1 day'));

        return [
            'id'              => (string) $conge['id'],
            'title'           => $conge['type_libelle'],
            'start'           => $conge['date_debut'],
            'end'             => $dateFinExclusive,
            'allDay'          => true,
            'backgroundColor' => $couleursStatut['background'],
            'borderColor'     => $couleursStatut['border'],
            'textColor'       => $couleursStatut['text'],
            'extendedProps'   => [
                'type_libelle'   => $conge['type_libelle'],
                'statut'         => $conge['statut'],
                'nb_jours'       => (int) $conge['nb_jours'],
                'motif'          => $conge['motif'] ?? '',
                'commentaire_rh' => $conge['commentaire_rh'] ?? '',
            ],
        ];
    }

    private function getCouleursCalendrierParStatut(string $statut): array
    {
        return match ($statut) {
            'approuvee' => [
                'background' => '#edf7f2',
                'border'     => '#8fd4aa',
                'text'       => '#1e6b3f',
            ],
            'refusee' => [
                'background' => '#fdf0ee',
                'border'     => '#f0b8b2',
                'text'       => '#c0392b',
            ],
            'annulee' => [
                'background' => '#f1efe8',
                'border'     => '#d4d2cc',
                'text'       => '#7a7a77',
            ],
            default => [
                'background' => '#fef9ee',
                'border'     => '#f5d98a',
                'text'       => '#b8750a',
            ],
        };
    }
}
