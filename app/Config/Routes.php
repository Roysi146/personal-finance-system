<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication routes
$routes->get('/login', 'AuthController::login');
$routes->post('/login/auth', 'AuthController::loginAuth');
$routes->get('/register', 'AuthController::register');
$routes->post('/register/store', 'AuthController::store');
$routes->get('/logout', 'AuthController::logout');

$routes->get('dashboard', 'DashboardController::index');

// Transaction routes
$routes->get('transaction/create', 'TransactionController::create');
$routes->post('transaction/store', 'TransactionController::store');
$routes->get('transaction/delete/(:num)', 'TransactionController::delete/$1');
$routes->get('/transaction/edit/(:num)', 'TransactionController::edit/$1');
$routes->post('/transaction/update/(:num)', 'TransactionController::update/$1');

// Route khusus Admin
$routes->get('/admin', 'AdminController::index');