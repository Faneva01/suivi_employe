<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table            = 'conges';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'employe_id', 'type_conge_id', 'date_debut', 'date_fin', 'nb_jours', 
        'motif', 'statut', 'commentaire_rh', 'created_at', 'traite_par'
    ];

    // Dates
    protected $useTimestamps = false; // We use created_at manually as per subject

    // Validation
    protected $validationRules      = [
        'employe_id'    => 'required|numeric',
        'type_conge_id' => 'required|numeric',
        'date_debut'    => 'required|valid_date',
        'date_fin'      => 'required|valid_date',
        'nb_jours'      => 'required|numeric',
    ];

    /**
     * Get leaves for an employee with type details
     */
    public function getEmployeConges($employeId)
    {
        return $this->select('conges.*, types_conge.libelle as type_libelle')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('employe_id', $employeId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
