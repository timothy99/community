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

$routes->get('/csl/member', 'Console\Member::index');
$routes->get('/csl/member/list', 'Console\Member::list');
$routes->get('/csl/member/view/(:any)', 'Console\Member::view');
$routes->get('/csl/member/edit/(:any)', 'Console\Member::edit');
$routes->post('/csl/member/update', 'Console\Member::update');
$routes->post('/csl/member/delete', 'Console\Member::delete');
$routes->get('/csl/member/excel', 'Console\Member::excel');

$routes->post('/file/upload/general', 'User\File::uploadGeneral');
$routes->post('/file/upload/board', 'User\File::uploadBoard');
$routes->post('/file/upload/image', 'User\File::uploadImage');
$routes->post('/file/upload/original', 'User\File::uploadOriginal');
$routes->post('/file/upload/dropzone', 'User\File::uploadDropzone');
$routes->get('/file/view/(:any)', 'User\File::view/$1');
