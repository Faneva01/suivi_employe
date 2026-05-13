<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // 1. Departements
        $departements = [
            [
                'nom'         => 'Informatique',
                'description' => 'Développement et maintenance des systèmes',
            ],
            [
                'nom'         => 'Ressources Humaines',
                'description' => 'Gestion du personnel',
            ],
            [
                'nom'         => 'Direction',
                'description' => 'Direction générale',
            ],
        ];
        $this->db->table('departements')->insertBatch($departements);

        // 2. Types de congé
        $typesConge = [
            [
                'libelle'       => 'Congé Annuel',
                'jours_annuels' => 30,
                'deductible'    => 1,
            ],
            [
                'libelle'       => 'Maladie',
                'jours_annuels' => 15,
                'deductible'    => 1,
            ],
            [
                'libelle'       => 'Exceptionnel',
                'jours_annuels' => 5,
                'deductible'    => 0,
            ],
        ];
        $this->db->table('types_conge')->insertBatch($typesConge);

        // 3. Employés
        $employes = [
            [
                'nom'            => 'Admin',
                'prenom'         => 'System',
                'email'          => 'admin@techmada.mg',
                'password'       => password_hash('admin123', PASSWORD_DEFAULT),
                'role'           => 'admin',
                'departement_id' => 3,
                'date_embauche'  => '2020-01-01',
                'actif'          => 1,
            ],
            [
                'nom'            => 'RH',
                'prenom'         => 'Responsable',
                'email'          => 'rh@techmada.mg',
                'password'       => password_hash('rh123', PASSWORD_DEFAULT),
                'role'           => 'rh',
                'departement_id' => 2,
                'date_embauche'  => '2021-06-01',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Dupont',
                'prenom'         => 'Jean',
                'email'          => 'jean.dupont@techmada.mg',
                'password'       => password_hash('user123', PASSWORD_DEFAULT),
                'role'           => 'employe',
                'departement_id' => 1,
                'date_embauche'  => '2022-03-15',
                'actif'          => 1,
            ],
            [
                'nom'            => 'Martin',
                'prenom'         => 'Alice',
                'email'          => 'alice.martin@techmada.mg',
                'password'       => password_hash('user123', PASSWORD_DEFAULT),
                'role'           => 'employe',
                'departement_id' => 1,
                'date_embauche'  => '2023-01-10',
                'actif'          => 1,
            ],
        ];
        $this->db->table('employes')->insertBatch($employes);

        // 4. Initialiser les soldes pour les employés (Dupont, Martin, RH)
        // On va le faire pour tous les employés sauf admin si on veut
        $allEmployes = $this->db->table('employes')->get()->getResult();
        $allTypes = $this->db->table('types_conge')->get()->getResult();
        $currentYear = date('Y');

        $soldes = [];
        foreach ($allEmployes as $emp) {
            foreach ($allTypes as $type) {
                $soldes[] = [
                    'employe_id'      => $emp->id,
                    'type_conge_id'   => $type->id,
                    'annee'           => $currentYear,
                    'jours_attribues' => $type->jours_annuels,
                    'jours_pris'      => 0,
                ];
            }
        }
        $this->db->table('soldes')->insertBatch($soldes);
    }
}
