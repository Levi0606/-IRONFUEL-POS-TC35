<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('logout', 'Auth::logout', ['filter' => 'auth']);

$routes->group('', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Home::index');
    $routes->get('dashboard', 'Home::index');

    $routes->get('products', 'Products::index');
    $routes->get('products/new', 'Products::form');
    $routes->post('products/save', 'Products::save');
    $routes->get('products/edit/(:num)', 'Products::form/$1');
    $routes->post('products/save/(:num)', 'Products::save/$1');
    $routes->post('products/delete/(:num)', 'Products::delete/$1');

    $routes->get('customers', 'Customers::index');
    $routes->get('customers/new', 'Customers::form');
    $routes->post('customers/save', 'Customers::save');
    $routes->get('customers/edit/(:num)', 'Customers::form/$1');
    $routes->post('customers/save/(:num)', 'Customers::save/$1');
    $routes->post('customers/delete/(:num)', 'Customers::delete/$1');

    $routes->get('users', 'Users::index');
    $routes->get('users/new', 'Users::form');
    $routes->post('users/save', 'Users::save');
    $routes->get('users/edit/(:num)', 'Users::form/$1');
    $routes->post('users/save/(:num)', 'Users::save/$1');
    $routes->post('users/delete/(:num)', 'Users::delete/$1');

    $routes->get('sales', 'Sales::index');
    $routes->get('sales/new', 'Sales::newSale');
    $routes->post('sales/record', 'Sales::record');
});