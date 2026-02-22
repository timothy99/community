<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'User\Home::index');
$routes->get('/home', 'User\Home::index');
$routes->get('/home/main', 'User\Home::main');

$routes->get("/csl", "Console\Slide::index");