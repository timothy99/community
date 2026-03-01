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
$routes->get('/csl/member/view/(:any)', 'Console\Member::view/$1');
$routes->get('/csl/member/edit/(:any)', 'Console\Member::edit/$1');
$routes->post('/csl/member/update', 'Console\Member::update');
$routes->post('/csl/member/delete', 'Console\Member::delete');
$routes->get('/csl/member/excel', 'Console\Member::excel');

$routes->post('/file/upload/general', 'User\File::uploadGeneral');
$routes->post('/file/upload/board', 'User\File::uploadBoard');
$routes->post('/file/upload/image', 'User\File::uploadImage');
$routes->post('/file/upload/original', 'User\File::uploadOriginal');
$routes->post('/file/upload/dropzone', 'User\File::uploadDropzone');
$routes->get('/file/view/(:any)', 'User\File::view/$1');

$routes->get("/contents/(:any)", "User\Contents::view/$1");

$routes->get("/csl/contents/list", "Console\Contents::list");
$routes->get("/csl/contents/write", "Console\Contents::write");
$routes->get("/csl/contents/edit/(:num)", "Console\Contents::edit/$1");
$routes->post("/csl/contents/update", "Console\Contents::update");
$routes->get("/csl/contents/view/(:num)", "Console\Contents::view/$1");
$routes->post("/csl/contents/delete", "Console\Contents::delete");

$routes->get("/csl/menu/list", "Console\Menu::list");
$routes->get("/csl/menu/write/(:num)", "Console\Menu::write/$1");
$routes->get("/csl/menu/edit/(:num)", "Console\Menu::edit/$1");
$routes->post("/csl/menu/update", "Console\Menu::update");
$routes->get("/csl/menu/view/(:num)", "Console\Menu::view/$1");
$routes->post("/csl/menu/delete", "Console\Menu::delete");

$routes->get("/csl/settings/board/list", "Console\Settings::boardList");
$routes->get("/csl/settings/board/write", "Console\Settings::boardWrite");
$routes->post("/csl/settings/board/update", "Console\Settings::boardUpdate");
$routes->get("/csl/settings/board/view/(:alphanum)", "Console\Settings::boardView/$1");
$routes->get("/csl/settings/board/edit/(:alphanum)", "Console\Settings::boardEdit/$1");
$routes->post("/csl/settings/board/delete", "Console\Settings::boardDelete");

$routes->get('/csl/popup', 'Console\Popup::index');
$routes->get('/csl/popup/list', 'Console\Popup::list');
$routes->get('/csl/popup/write', 'Console\Popup::write');
$routes->post('/csl/popup/update', 'Console\Popup::update');
$routes->get('/csl/popup/view/(:num)', 'Console\Popup::view/$1');
$routes->get('/csl/popup/edit/(:num)', 'Console\Popup::edit/$1');
$routes->post('/csl/popup/delete', 'Console\Popup::delete');

$routes->get('/csl/inquiry', 'Console\Inquiry::index');
$routes->get('/csl/inquiry/list', 'Console\Inquiry::list');
$routes->get('/csl/inquiry/view/(:num)', 'Console\Inquiry::view/$1');

$routes->get('/csl/board', 'Console\Board::index');
$routes->get('/csl/board/(:alphanum)/list', 'Console\Board::list/$1');
$routes->get('/csl/board/(:alphanum)/write', 'Console\Board::write/$1');
$routes->post('/csl/board/(:alphanum)/update', 'Console\Board::update/$1');
$routes->get('/csl/board/(:alphanum)/view/(:num)', 'Console\Board::view/$1/$2');
$routes->get('/csl/board/(:alphanum)/edit/(:num)', 'Console\Board::edit/$1/$2');
$routes->post('/csl/board/(:alphanum)/delete', 'Console\Board::delete/$1');

$routes->get('/csl/settings/board/(:alphanum)/admin/list', 'Console\Settings::boardAdminList/$1');
$routes->post('/csl/settings/board/(:alphanum)/admin/search', 'Console\Settings::boardAdminSearch');
$routes->post('/csl/settings/board/(:alphanum)/admin/insert', 'Console\Settings::boardAdminInsert');
$routes->post('/csl/settings/board/(:alphanum)/admin/delete', 'Console\Settings::boardAdminDelete');
