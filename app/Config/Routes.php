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
$routes->get('/csl/slide/write', 'Console\Slide::write');
$routes->post('/csl/slide/update', 'Console\Slide::update');
$routes->get('/csl/slide/view/(:num)', 'Console\Slide::view/$1');
$routes->get('/csl/slide/edit/(:num)', 'Console\Slide::edit/$1');
$routes->post('/csl/slide/delete', 'Console\Slide::delete');

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

$routes->post('/csl/file/upload/general', 'Console\File::uploadGeneral');
$routes->post('/csl/file/upload/board', 'Console\File::uploadBoard');
$routes->post('/csl/file/upload/image', 'Console\File::uploadImage');
$routes->post('/csl/file/upload/original', 'Console\File::uploadOriginal');
$routes->post('/csl/file/upload/dropzone', 'Console\File::uploadDropzone');
$routes->get('/csl/file/view/(:any)', 'Console\File::view/$1');

$routes->get('/csl/config/view', 'Console\Config::view');
$routes->get('/csl/config/edit', 'Console\Config::edit');
$routes->post('/csl/config/update', 'Console\Config::update');