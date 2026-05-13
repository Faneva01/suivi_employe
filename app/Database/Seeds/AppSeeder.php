<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AppSeeder extends Seeder
{
    public function run()
    {
        // 1. Departements
        $deps = [
            ['nom' => 'IT', 'description' => 'Département Informatique'],
            ['nom' => 'RH', 'description' => 'Ressources Humaines'],
            ['nom' => 'Ventes', 'description' => 'Département Commercial'],
        ];
        $this->db->table('departements')->insertBatch($deps);

        // 2. Types de congé
        $types = [
            ['libelle' => 'Congé Annuel', 'jours_annuels' => 30, 'deductible' => 1],
            ['libelle' => 'Maladie', 'jours_annuels' => 10, 'deductible' => 0],
            ['libelle' => 'Exceptionnel', 'jours_annuels' => 5, 'deductible' => 1],
        ];
        $this->db->table('types_conge')->insertBatch($types);

        // 3. Employes
        $employes = [
            [
                'nom' => 'Admin',
                'prenom' => 'System',
                'email' => 'admin@techmada.mg',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'departement_id' => 1,
                'date_embauche' => '2020-01-01',
                'actif' => 1,
            ],
            [
                'nom' => 'Responsable',
                'prenom' => 'RH',
                'email' => 'rh@techmada.mg',
                'password' => password_hash('rh123', PASSWORD_DEFAULT),
                'role' => 'rh',
                'departement_id' => 2,
                'date_embauche' => '2021-06-15',
                'actif' => 1,
            ],
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'email' => 'jean.dupont@techmada.mg',
                'password' => password_hash('emp123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => 1,
                'date_embauche' => '2022-03-10',
                'actif' => 1,
            ],
            [
                'nom' => 'Durand',
                'prenom' => 'Marie',
                'email' => 'marie.durand@techmada.mg',
                'password' => password_hash('emp123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => 3,
                'date_embauche' => '2023-01-20',
                'actif' => 1,
            ],
        ];
        $this->db->table('employes')->insertBatch($employes);

        // 4. Soldes initialisés pour 2026
        $all_employes = $this->db->table('employes')->get()->getResult();
        $all_types = $this->db->table('types_conge')->get()->getResult();
        $annee_actuelle = 2026;

        $soldes = [];
        foreach ($all_employes as $emp) {
            foreach ($all_types as $type) {
                $soldes[] = [
                    'employe_id' => $emp->id,
                    'type_conge_id' => $type->id,
                    'annee' => $annee_actuelle,
                    'jours_attribues' => $type->jours_annuels,
                    'jours_pris' => 0,
                ];
            }
        }
        $this->db->table('soldes')->insertBatch($soldes);
    }
}
