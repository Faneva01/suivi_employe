<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table            = 'soldes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['employe_id', 'type_conge_id', 'annee', 'jours_attribues', 'jours_pris'];

    // Validation
    protected $validationRules      = [
        'employe_id'    => 'required|numeric',
        'type_conge_id' => 'required|numeric',
        'annee'         => 'required|numeric',
    ];

    /**
     * Get remaining days for an employee and a specific leave type
     */
    public function getRestant($employeId, $typeCongeId, $annee)
    {
        $solde = $this->where([
            'employe_id'    => $employeId,
            'type_conge_id' => $typeCongeId,
            'annee'         => $annee
        ])->first();

        if (!$solde) return 0;

        return $solde['jours_attribues'] - $solde['jours_pris'];
    }
}
