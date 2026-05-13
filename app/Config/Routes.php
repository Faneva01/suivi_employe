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
});

// RH routes
$routes->group('rh', ['filter' => 'auth:rh'], function($routes) {
    $routes->get('/', 'RH::index');
    $routes->get('demandes', 'RH::demandes');
    $routes->post('demandes/traiter', 'RH::traiter');
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
    // etc.
});
