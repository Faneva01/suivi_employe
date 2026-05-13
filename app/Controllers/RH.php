<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\EmployeModel;
use App\Models\DepartementModel;

class RH extends BaseController
{
    public function index()
    {
        return redirect()->to('/rh/demandes');
    }

    public function demandes()
    {
        $congeModel = new CongeModel();
        $depModel = new DepartementModel();
        
        // Filtres
        $statut = $this->request->getGet('statut') ?? 'en_attente';
        $departementId = $this->request->getGet('departement_id');
        
        $query = $congeModel->select('conges.*, employes.nom, employes.prenom, employes.departement_id, types_conge.libelle')
                            ->join('employes', 'employes.id = conges.employe_id')
                            ->join('types_conge', 'types_conge.id = conges.type_conge_id');

        if ($statut !== 'all') {
            $query->where('conges.statut', $statut);
        }

        if (!empty($departementId)) {
            $query->where('employes.departement_id', $departementId);
        }

        $data = [
            'title'         => 'Demandes à traiter',
            'demandes'      => $query->orderBy('conges.created_at', 'DESC')->findAll(),
            'departements'  => $depModel->findAll(),
            'filtreStatut'  => $statut,
            'filtreDep'     => $departementId
        ];

        return view('rh/demandes', $data);
    }

    public function traiter()
    {
        $id = $this->request->getPost('id');
        $action = $this->request->getPost('action'); // approuvee | refusee
        $commentaire = $this->request->getPost('commentaire_rh');

        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $conge = $congeModel->find($id);
        if (!$conge) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        if ($action === 'approuvee') {
            $employeId = $conge['employe_id'];
            $typeCongeId = $conge['type_conge_id'];
            $nbJours = $conge['nb_jours'];
            $annee = date('Y', strtotime($conge['date_debut']));

            $solde = $soldeModel->where([
                'employe_id'    => $employeId,
                'type_conge_id' => $typeCongeId,
                'annee'         => $annee
            ])->first();

            if (!$solde || ($solde['jours_pris'] + $nbJours > $solde['jours_attribues'])) {
                return redirect()->back()->with('error', 'Solde insuffisant pour approuver cette demande.');
            }

            // Mettre à jour le solde
            $soldeModel->update($solde['id'], [
                'jours_pris' => $solde['jours_pris'] + $nbJours
            ]);
        }

        $congeModel->update($id, [
            'statut'         => $action,
            'commentaire_rh' => $commentaire,
            'traite_par'     => session()->get('id')
        ]);

        return redirect()->back()->with('success', 'Demande traitée avec succès.');
    }

    // Voir les soldes des employés
    public function soldes()
    {
        $soldeModel = new SoldeModel();
        $employeModel = new EmployeModel();
        $depModel = new DepartementModel();

        $departementId = $this->request->getGet('departement_id');

        $query = $soldeModel->select('soldes.*, employes.nom as emp_nom, employes.prenom as emp_prenom, employes.departement_id, types_conge.libelle as type_libelle')
                            ->join('employes', 'employes.id = soldes.employe_id')
                            ->join('types_conge', 'types_conge.id = soldes.type_conge_id');

        if (!empty($departementId)) {
            $query->where('employes.departement_id', $departementId);
        }

        $data = [
            'title'        => 'Soldes des employés',
            'soldes'       => $query->orderBy('employes.nom', 'ASC')->findAll(),
            'departements' => $depModel->findAll(),
            'filtreDep'    => $departementId
        ];

        return view('rh/soldes', $data);
    }
}
