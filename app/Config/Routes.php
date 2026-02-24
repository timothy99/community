<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'User\Home::index');
$routes->get('/home', 'User\Home::index');
$routes->get('/home/main', 'User\Home::main');

$routes->get('/csl', 'Console\Slide::index');
$routes->get('/csl/slide', 'Console\Slide::index');
$routes->get('/csl/slide/list', 'Console\Slide::list');

$routes->get('/member', 'User\Member::index');
$routes->get('/member/login', 'User\Member::login');
$routes->post('/member/signin', 'User\Member::signin');
$routes->get('/member/register', 'User\Member::register');
$routes->post('/member/register/duplicate', 'User\Member::registerDuplicate');
$routes->post('/member/register/process', 'User\Member::registerProcess');

