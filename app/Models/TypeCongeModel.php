<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table            = 'types_conge';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['libelle', 'jours_annuels', 'deductible'];

    // Validation
    protected $validationRules      = [
        'libelle'       => 'required|min_length[2]|is_unique[types_conge.libelle,id,{id}]',
        'jours_annuels' => 'required|numeric',
    ];
}
