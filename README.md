# Suivi Employé - TechMada

Système de gestion des congés pour TechMada.

## Installation

1. Cloner le projet
2. Exécuter les migrations via XAMPP : `/opt/lampp/bin/php spark migrate`
3. Exécuter le seeder : `/opt/lampp/bin/php spark db:seed MainSeeder`
4. Lancer le serveur : `./start.sh` (ou `/opt/lampp/bin/php spark serve`)

> **Note importante** : L'extension SQLite3 est disponible dans le PHP de XAMPP mais peut manquer dans le PHP par défaut de votre système. Utilisez toujours le chemin complet `/opt/lampp/bin/php`.

## Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Admin | `admin@techmada.mg` | `admin123` |
| RH | `rh@techmada.mg` | `rh123` |
| Employé | `jean.dupont@techmada.mg` | `user123` |

## Fonctionnalités implémentées

- Authentification avec redirection selon le rôle
- **Employé** : Dashboard, Soumission de demande, Liste des demandes, Solde en temps réel
- **RH** : Validation/Refus des demandes, Mise à jour automatique du solde
- **Admin** : Gestion des employés (CRUD), Gestion des départements, Dashboard absences du mois

Test complets fini [Ok]