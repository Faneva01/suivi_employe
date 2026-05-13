<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table            = 'departements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'description'];

    // Validation
    protected $validationRules      = [
        'nom' => 'required|min_length[2]|is_unique[departements.nom,id,{id}]',
    ];
}
