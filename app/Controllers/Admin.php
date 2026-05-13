<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\DepartementModel;
use App\Models\CongeModel;
use App\Models\TypeCongeModel;
use App\Models\SoldeModel;

class Admin extends BaseController
{
    public function index()
    {
        $congeModel = new CongeModel();
        $employeModel = new EmployeModel();
        
        $currentMonth = date('m');
        $currentYear = date('Y');

        $absencesMois = $congeModel->select('conges.*, employes.nom, employes.prenom, types_conge.libelle')
                                  ->join('employes', 'employes.id = conges.employe_id')
                                  ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                                  ->where('conges.statut', 'approuvee')
                                  ->groupStart()
                                    ->where("strftime('%m', date_debut) = '$currentMonth' OR strftime('%m', date_fin) = '$currentMonth'")
                                  ->groupEnd()
                                  ->findAll();

        $data = [
            'title'         => 'Dashboard Admin',
            'totalEmployes' => $employeModel->countAll(),
            'demandesAttente' => $congeModel->where('statut', 'en_attente')->countAllResults(),
            'absencesMois'  => $absencesMois
        ];

        return view('admin/dashboard', $data);
    }

    public function listHistorique()
    {
        $congeModel = new CongeModel();
        $depModel = new DepartementModel();
        
        // Filtres
        $statut = $this->request->getGet('statut');
        $departementId = $this->request->getGet('departement_id');
        
        $query = $congeModel->select('conges.*, employes.nom, employes.prenom, employes.departement_id, types_conge.libelle')
                            ->join('employes', 'employes.id = conges.employe_id')
                            ->join('types_conge', 'types_conge.id = conges.type_conge_id');

        if (!empty($statut) && $statut !== 'all') {
            $query->where('conges.statut', $statut);
        }

        if (!empty($departementId)) {
            $query->where('employes.departement_id', $departementId);
        }

        $data = [
            'title'         => 'Historique des demandes',
            'demandes'      => $query->orderBy('conges.created_at', 'DESC')->findAll(),
            'departements'  => $depModel->findAll(),
            'filtreStatut'  => $statut,
            'filtreDep'     => $departementId
        ];

        return view('admin/historique', $data);
    }

    public function listEmployes()
    {
        $model = new EmployeModel();
        $depModel = new DepartementModel();
        $data = [
            'title'        => 'Gestion des Employés',
            'employes'     => $model->select('employes.*, departements.nom as dep_nom')
                                    ->join('departements', 'departements.id = employes.departement_id', 'left')
                                    ->findAll(),
            'departements' => $depModel->findAll()  // Needed by the inline form in index.php
        ];
        return view('admin/employes/index', $data);
    }

    public function createEmploye()
    {
        $depModel = new DepartementModel();
        $data = [
            'title'        => 'Ajouter un Employé',
            'departements' => $depModel->findAll()
        ];
        return view('admin/employes/create', $data);
    }

    public function storeEmploye()
    {
        $model = new EmployeModel();
        $typeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();

        $data = $this->request->getPost();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['actif'] = 1;

        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        // Initialiser les soldes pour le nouvel employé
        $employeId = $model->insertID();
        $types = $typeModel->findAll();
        $currentYear = date('Y');

        foreach ($types as $type) {
            $soldeModel->insert([
                'employe_id'      => $employeId,
                'type_conge_id'   => $type['id'],
                'annee'           => $currentYear,
                'jours_attribues' => $type['jours_annuels'],
                'jours_pris'      => 0
            ]);
        }

        return redirect()->to('/admin/employes')->with('success', 'Employé créé avec succès.');
    }

    public function editEmploye($id)
    {
        $model = new EmployeModel();
        $depModel = new DepartementModel();
        
        $data = [
            'title'        => 'Modifier un Employé',
            'employe'      => $model->find($id),
            'departements' => $depModel->findAll()
        ];

        if (!$data['employe']) {
            return redirect()->to('/admin/employes')->with('error', 'Employé introuvable.');
        }

        return view('admin/employes/edit', $data);
    }

    public function updateEmploye($id)
    {
        $model = new EmployeModel();
        $data = $this->request->getPost();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (!$model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/admin/employes')->with('success', 'Employé mis à jour.');
    }

    public function toggleEmploye($id)
    {
        $model = new EmployeModel();
        $employe = $model->find($id);
        
        if ($employe) {
            $newStatus = $employe['actif'] == 1 ? 0 : 1;
            $model->update($id, ['actif' => $newStatus]);
            return redirect()->back()->with('success', 'Statut de l\'employé mis à jour.');
        }

        return redirect()->back()->with('error', 'Employé introuvable.');
    }

    // CRUD Departements
    public function listDepartements()
    {
        $model = new DepartementModel();
        $data = [
            'title'        => 'Gestion des Départements',
            'departements' => $model->findAll()
        ];
        return view('admin/departements/index', $data);
    }

    public function storeDepartement()
    {
        $model = new DepartementModel();
        if (!$model->insert($this->request->getPost())) {
            return redirect()->back()->with('error', 'Erreur lors de la création.');
        }
        return redirect()->back()->with('success', 'Département ajouté.');
    }

    // CRUD Types de Congé
    public function listTypesConge()
    {
        $model = new TypeCongeModel();
        $data = [
            'title' => 'Gestion des Types de Congé',
            'types' => $model->findAll()
        ];
        return view('admin/types_conge/index', $data);
    }

    public function storeTypeConge()
    {
        $model = new TypeCongeModel();
        if (!$model->insert($this->request->getPost())) {
            return redirect()->back()->with('error', 'Erreur lors de la création.');
        }
        return redirect()->back()->with('success', 'Type de congé ajouté.');
    }

    public function editTypeConge($id)
    {
        $model = new TypeCongeModel();
        $type = $model->find($id);
        if (!$type) {
            return redirect()->to('/admin/types-conge')->with('error', 'Type de congé introuvable.');
        }
        $data = [
            'title' => 'Modifier un type de congé',
            'type'  => $type
        ];
        return view('admin/types_conge/edit', $data);
    }

    public function updateTypeConge($id)
    {
        $model = new TypeCongeModel();
        if (!$model->update($id, $this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
        return redirect()->to('/admin/types-conge')->with('success', 'Type de congé mis à jour.');
    }

    public function deleteTypeConge($id)
    {
        $model = new TypeCongeModel();
        $model->delete($id);
        return redirect()->back()->with('success', 'Type de congé supprimé.');
    }

    // Gestion des soldes
    public function listSoldes()
    {
        $soldeModel = new SoldeModel();
        $employeModel = new EmployeModel();
        $typeModel = new TypeCongeModel();

        $soldes = $soldeModel->select('soldes.*, employes.nom as emp_nom, employes.prenom as emp_prenom, types_conge.libelle as type_libelle')
                             ->join('employes', 'employes.id = soldes.employe_id')
                             ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                             ->orderBy('employes.nom', 'ASC')
                             ->findAll();

        $data = [
            'title'         => 'Gestion des Soldes',
            'soldes'        => $soldes,
            'employes'      => $employeModel->findAll(),
            'typesConge'    => $typeModel->findAll(),
            'currentYear'   => date('Y')
        ];
        return view('admin/soldes/index', $data);
    }

    public function updateSolde()
    {
        $soldeModel = new SoldeModel();
        $id = $this->request->getPost('id');
        $joursAttribues = $this->request->getPost('jours_attribues');

        $solde = $soldeModel->find($id);
        if (!$solde) {
            return redirect()->back()->with('error', 'Solde introuvable.');
        }

        if ($joursAttribues < $solde['jours_pris']) {
            return redirect()->back()->with('error', 'Les jours attribués ne peuvent pas être inférieurs aux jours déjà pris.');
        }

        $soldeModel->update($id, ['jours_attribues' => $joursAttribues]);
        return redirect()->back()->with('success', 'Solde mis à jour avec succès.');
    }
}
