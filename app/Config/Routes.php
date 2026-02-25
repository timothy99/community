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
$routes->get('/member/logout', 'User\Member::logout');
$routes->get('/member/find/id', 'User\Member::findId');
$routes->post('/member/send/id', 'User\Member::sendId');
$routes->get('/member/find/password', 'User\Member::findPassword');
$routes->post('/member/send/password', 'User\Member::sendPassword');
$routes->get('/member/reset/password/(:any)', 'User\Member::resetPassword/$1');
$routes->post('/member/update/password', 'User\Member::updatePassword');
