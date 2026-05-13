<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');

// Authentication
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

// Employe routes
$routes->group('employe', ['filter' => 'auth:employe'], function($routes) {
    $routes->get('/', 'Employe::index');
    $routes->get('conges', 'Employe::conges');
    $routes->post('conges/soumettre', 'Employe::soumettre');
    $routes->get('conges/annuler/(:num)', 'Employe::annuler/$1');
    $routes->get('profil', 'Employe::profil');
    $routes->post('profil/update', 'Employe::updateProfil');
});

// RH routes
$routes->group('rh', ['filter' => 'auth:rh'], function($routes) {
    $routes->get('/', 'RH::index');
    $routes->get('demandes', 'RH::demandes');
    $routes->post('demandes/traiter', 'RH::traiter');
    $routes->get('soldes', 'RH::soldes');
});

// Admin routes
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('/', 'Admin::index');
    // CRUD Employes
    $routes->get('employes', 'Admin::listEmployes');
    $routes->get('employes/create', 'Admin::createEmploye');
    $routes->post('employes/store', 'Admin::storeEmploye');
    $routes->get('employes/edit/(:num)', 'Admin::editEmploye/$1');
    $routes->post('employes/update/(:num)', 'Admin::updateEmploye/$1');
    $routes->get('employes/toggle/(:num)', 'Admin::toggleEmploye/$1');
    
    // CRUD Departements
    $routes->get('departements', 'Admin::listDepartements');
    $routes->post('departements/store', 'Admin::storeDepartement');
    // CRUD Types de Congé
    $routes->get('types-conge', 'Admin::listTypesConge');
    $routes->post('types-conge/store', 'Admin::storeTypeConge');
    $routes->get('types-conge/edit/(:num)', 'Admin::editTypeConge/$1');
    $routes->post('types-conge/update/(:num)', 'Admin::updateTypeConge/$1');
    $routes->get('types-conge/delete/(:num)', 'Admin::deleteTypeConge/$1');

    // Gestion des Soldes
    $routes->get('soldes', 'Admin::listSoldes');
    $routes->post('soldes/update', 'Admin::updateSolde');
});
