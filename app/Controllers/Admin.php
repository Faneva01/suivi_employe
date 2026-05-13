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
                                    ->where("MONTH(date_debut) = $currentMonth OR MONTH(date_fin) = $currentMonth")
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

    public function listEmployes()
    {
        $model = new EmployeModel();
        $data = [
            'title'    => 'Gestion des Employés',
            'employes' => $model->select('employes.*, departements.nom as dep_nom')
                                ->join('departements', 'departements.id = employes.departement_id', 'left')
                                ->findAll()
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
}
