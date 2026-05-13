<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

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
            'lastConges' => $congeModel->where('employe_id', $employeId)->orderBy('created_at', 'DESC')->limit(5)->findAll(),
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

    public function soumettre()
    {
        $rules = [
            'type_conge_id' => 'required|numeric',
            'date_debut'    => 'required|valid_date',
            'date_fin'      => 'required|valid_date',
            'motif'         => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir correctement le formulaire.');
        }

        $typeCongeId = $this->request->getPost('type_conge_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');

        if (strtotime($dateDebut) > strtotime($dateFin)) {
            return redirect()->back()->withInput()->with('error', 'La date de début doit être antérieure à la date de fin.');
        }

        // Calcul nb jours (calendaires pour simplifier comme dit dans le sujet)
        $diff = strtotime($dateFin) - strtotime($dateDebut);
        $nbJours = round($diff / (60 * 60 * 24)) + 1;

        $employeId = session()->get('id');
        $currentYear = date('Y');

        // Vérifier le solde
        $soldeModel = new SoldeModel();
        $restant = $soldeModel->getRestant($employeId, $typeCongeId, $currentYear);

        if ($nbJours > $restant) {
            return redirect()->back()->withInput()->with('error', "Solde insuffisant ($nbJours demandés, $restant disponibles).");
        }

        // Vérifier chevauchements
        $congeModel = new CongeModel();
        $overlap = $congeModel->where('employe_id', $employeId)
                             ->whereIn('statut', ['en_attente', 'approuvee'])
                             ->groupStart()
                                ->where("date_debut <= '$dateFin' AND date_fin >= '$dateDebut'")
                             ->groupEnd()
                             ->first();
        
        if ($overlap) {
            return redirect()->back()->withInput()->with('error', "Vous avez déjà une demande qui chevauche ces dates.");
        }

        $congeModel->insert([
            'employe_id'    => $employeId,
            'type_conge_id' => $typeCongeId,
            'date_debut'    => $dateDebut,
            'date_fin'      => $dateFin,
            'nb_jours'      => $nbJours,
            'motif'         => $this->request->getPost('motif'),
            'statut'        => 'en_attente',
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/employe/conges')->with('success', 'Demande soumise avec succès.');
    }

    public function annuler($id)
    {
        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);

        if (!$conge || $conge['employe_id'] != session()->get('id')) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        if ($conge['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être annulées.');
        }

        $congeModel->update($id, ['statut' => 'annulee']);

        return redirect()->back()->with('success', 'Demande annulée.');
    }
}
