# Suivi Employé - TechMada

Système de gestion des congés pour TechMada.

## Installation

1. Cloner le projet
2. Configurer la base de données dans `.env` (déjà configuré pour MySQL local)
3. Exécuter les migrations : `php spark migrate`
4. Exécuter le seeder : `php spark db:seed MainSeeder`
5. Lancer le serveur : `php spark serve`

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
