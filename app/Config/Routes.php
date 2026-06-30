<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─────────────────────────────────────────────────────────
// 다국어: DB에서 사용 중인 언어 코드를 읽어 {locale} 라우트 그룹에 적용
// Routes.php 최상단에서 config('App')->supportedLocales를 덮어써야
// CI4 Router가 {locale} 플레이스홀더를 올바른 정규식으로 변환한다.
// ─────────────────────────────────────────────────────────
try {
    $_db = \Config\Database::connect();
    $_config = $_db->table('config')->select('language_yn')->get()->getRow();
    $language_yn = $_config->language_yn ?? 'N';

    if ($language_yn === 'Y') {
        $builder = $_db->table('language');
        $builder->select('language_code');
        $builder->where('use_yn', 'Y');
        $builder->orderBy('language_idx', 'asc');
        $_localeRows = $builder->get()->getResultArray();
        $_localeCodes = array_column($_localeRows, 'language_code');

        if (! empty($_localeCodes)) {
            /** @var \Config\App $appConfig */
            $appConfig = config('App');
            $appConfig->supportedLocales = $_localeCodes;
            $appConfig->defaultLocale = $_localeCodes[0];
        }
    }

    unset($_db, $_config, $_localeRows, $_localeCodes);
} catch (\Throwable $e) {
    // DB 미준비(마이그레이션 등) 상태에서는 기본값 유지
}

// ─────────────────────────────────────────────────────────
// 사용자 GET 라우트 클로저
// - 다국어 OFF: /home/main
// - 다국어 ON : /kr/home/main  ← {locale} 그룹으로 자동 적용
//
// ※ {locale} 플레이스홀더는 CI4가 특별 처리하므로
//   내부 라우트의 $1, $2 파라미터 번호가 밀리지 않는다.
// ─────────────────────────────────────────────────────────
$userGetRoutes = static function (RouteCollection $routes): void {
    $routes->get('/', 'User\Home::index');
    $routes->get('home', 'User\Home::index');
    $routes->get('home/main', 'User\Home::main');

    $routes->get('member', 'User\Member::index');
    $routes->get('member/login', 'User\Member::login');
    $routes->get('member/register', 'User\Member::register');
    $routes->get('member/logout', 'User\Member::logout');
    $routes->get('member/find/id', 'User\Member::findId');
    $routes->get('member/find/password', 'User\Member::findPassword');
    $routes->get('member/reset/password/(:any)', 'User\Member::resetPassword/$1');
    $routes->get('member/mypage', 'User\Member::mypage');
    $routes->get('member/password/change', 'User\Member::passwordChange');
    $routes->get('member/authenticate', 'User\Member::authenticate');

    $routes->get('member/sns/(:alpha)/callback', 'User\Sns::callback/$1');
    $routes->get('member/sns/(:alpha)/connect', 'User\Sns::connect/$1');
    $routes->get('member/sns/(:alpha)', 'User\Sns::start/$1');

    $routes->get('file/view/(:any)', 'User\File::view/$1');
    $routes->get('file/download/(:any)', 'User\File::download/$1');

    $routes->get('contents/(:any)', 'User\Contents::view/$1');

    $routes->get('board', 'User\Board::index');
    $routes->get('board/(:alphanum)/list', 'User\Board::list/$1');
    $routes->get('board/(:alphanum)/write', 'User\Board::write/$1');
    $routes->get('board/(:alphanum)/view/(:num)', 'User\Board::view/$1/$2');
    $routes->get('board/(:alphanum)/edit/(:num)', 'User\Board::edit/$1/$2');

    $routes->get('inquiry', 'User\Inquiry::index');
    $routes->get('inquiry/write', 'User\Inquiry::write');

    $routes->get('product', 'User\Product::index');
    $routes->get('product/list', 'User\Product::list');
    $routes->get('product/view/(:num)', 'User\Product::view/$1');

    $routes->get('construction', 'User\Construction::index');
};

// ─────────────────────────────────────────────────────────
// 콘솔(관리자) 라우트 - {locale} 그룹보다 먼저 등록하여 우선 매칭
// ─────────────────────────────────────────────────────────
$routes->get('/csl', 'Console\Slide::list');
$routes->get('/csl/slide', 'Console\Slide::index');
$routes->get('/csl/slide/list', 'Console\Slide::list');
$routes->get('/csl/slide/write', 'Console\Slide::write');
$routes->post('/csl/slide/update', 'Console\Slide::update');
$routes->get('/csl/slide/view/(:num)', 'Console\Slide::view/$1');
$routes->get('/csl/slide/edit/(:num)', 'Console\Slide::edit/$1');
$routes->post('/csl/slide/delete', 'Console\Slide::delete');

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
$routes->get('/csl/member/password/(:any)', 'Console\Member::password/$1');
$routes->post('/csl/member/password/update', 'Console\Member::passwordUpdate');
$routes->post('/csl/member/memo/update', 'Console\Member::memoUpdate');
$routes->post('/csl/member/memo/insert', 'Console\Member::memoInsert');
$routes->get('/csl/member/memo/view/(:num)', 'Console\Member::memoView/$1');
$routes->post('/csl/member/memo/delete', 'Console\Member::memoDelete');

$routes->get('/csl/contents/list', 'Console\Contents::list');
$routes->get('/csl/contents/write', 'Console\Contents::write');
$routes->get('/csl/contents/edit/(:num)', 'Console\Contents::edit/$1');
$routes->post('/csl/contents/update', 'Console\Contents::update');
$routes->get('/csl/contents/view/(:num)', 'Console\Contents::view/$1');
$routes->post('/csl/contents/delete', 'Console\Contents::delete');

$routes->get('/csl/menu/list', 'Console\Menu::list');
$routes->get('/csl/menu/write/(:num)', 'Console\Menu::write/$1');
$routes->get('/csl/menu/edit/(:num)', 'Console\Menu::edit/$1');
$routes->post('/csl/menu/update', 'Console\Menu::update');
$routes->get('/csl/menu/view/(:num)', 'Console\Menu::view/$1');
$routes->post('/csl/menu/delete', 'Console\Menu::delete');

$routes->get('/csl/ip/list', 'Console\Ip::list');
$routes->get('/csl/ip/write', 'Console\Ip::write');
$routes->post('/csl/ip/update', 'Console\Ip::update');
$routes->get('/csl/ip/view/(:num)', 'Console\Ip::view/$1');
$routes->get('/csl/ip/edit/(:num)', 'Console\Ip::edit/$1');
$routes->post('/csl/ip/delete', 'Console\Ip::delete');

$routes->get('/csl/popup/list', 'Console\Popup::list');
$routes->get('/csl/popup/write', 'Console\Popup::write');
$routes->post('/csl/popup/update', 'Console\Popup::update');
$routes->get('/csl/popup/view/(:num)', 'Console\Popup::view/$1');
$routes->get('/csl/popup/edit/(:num)', 'Console\Popup::edit/$1');
$routes->post('/csl/popup/delete', 'Console\Popup::delete');

$routes->get('/csl/board/(:alphanum)/list', 'Console\Board::list/$1');
$routes->get('/csl/board/(:alphanum)/write', 'Console\Board::write/$1');
$routes->get('/csl/board/(:alphanum)/view/(:num)', 'Console\Board::view/$1/$2');
$routes->get('/csl/board/(:alphanum)/edit/(:num)', 'Console\Board::edit/$1/$2');
$routes->post('/csl/board/(:alphanum)/update', 'Console\Board::update/$1');
$routes->post('/csl/board/(:alphanum)/delete', 'Console\Board::delete');
$routes->post('/csl/board/(:alphanum)/batch/delete', 'Console\Board::batchDelete/$1');

$routes->get('/csl/product/list', 'Console\Product::list');
$routes->get('/csl/product/write', 'Console\Product::write');
$routes->get('/csl/product/view/(:num)', 'Console\Product::view/$1');
$routes->get('/csl/product/edit/(:num)', 'Console\Product::edit/$1');
$routes->post('/csl/product/update', 'Console\Product::update');
$routes->post('/csl/product/delete', 'Console\Product::delete');
$routes->post('/csl/product/category', 'Console\Product::category');

$routes->get('/csl/category/list', 'Console\Category::list');
$routes->get('/csl/category/write/(:num)', 'Console\Category::write/$1');
$routes->post('/csl/category/update', 'Console\Category::update');
$routes->get('/csl/category/view/(:num)', 'Console\Category::view/$1');
$routes->get('/csl/category/edit/(:num)', 'Console\Category::edit/$1');
$routes->post('/csl/category/delete', 'Console\Category::delete');
$routes->get('/csl/category/sub', 'Console\Category::sub');

$routes->get('/csl/inquiry/list', 'Console\Inquiry::list');
$routes->get('/csl/inquiry/view/(:num)', 'Console\Inquiry::view/$1');
$routes->get('/csl/inquiry/edit/(:num)', 'Console\Inquiry::edit/$1');
$routes->post('/csl/inquiry/update', 'Console\Inquiry::update');
$routes->post('/csl/inquiry/delete', 'Console\Inquiry::delete');
$routes->get('/csl/inquiry/excel', 'Console\Inquiry::excel');

$routes->post('/csl/comment/insert', 'Console\Comment::insert');
$routes->post('/csl/comment/delete', 'Console\Comment::delete');
$routes->post('/csl/comment/edit/(:num)', 'Console\Comment::edit');
$routes->post('/csl/comment/update', 'Console\Comment::update');

$routes->get('/csl/settings/board/list', 'Console\Settings::Boardlist');
$routes->get('/csl/settings/board/write', 'Console\Settings::BoardWrite');
$routes->post('/csl/settings/board/update', 'Console\Settings::BoardUpdate');
$routes->get('/csl/settings/board/edit/(:alphanum)', 'Console\Settings::BoardEdit/$1');
$routes->get('/csl/settings/board/view/(:alphanum)', 'Console\Settings::BoardView/$1');
$routes->post('/csl/settings/board/delete', 'Console\Settings::BoardDelete');
$routes->get('/csl/settings/board/(:alphanum)/admin/list', 'Console\Settings::BoardAdminList/$1');
$routes->post('/csl/settings/board/(:alphanum)/admin/search', 'Console\Settings::BoardAdminSearch');
$routes->post('/csl/settings/board/(:alphanum)/admin/insert', 'Console\Settings::BoardAdminInsert');
$routes->post('/csl/settings/board/(:alphanum)/admin/delete', 'Console\Settings::BoardAdminDelete');

$routes->get('/csl/language/edit', 'Console\Language::edit');
$routes->post('/csl/language/update', 'Console\Language::update');

$routes->get('/csl/social/edit', 'Console\Social::edit');
$routes->post('/csl/social/update', 'Console\Social::update');

// 다국어 OFF: 언어 코드 없는 URL (/home/main)
$userGetRoutes($routes);

// 다국어 ON: 언어 코드 포함 URL (/kr/home/main)
$routes->group('{locale}', $userGetRoutes);

// ─────────────────────────────────────────────────────────
// 사용자 POST 라우트 (언어 코드 prefix 없음)
// 폼 액션은 항상 prefix 없는 경로로 전송한다.
// ─────────────────────────────────────────────────────────
$routes->post('/member/signin', 'User\Member::signin');
$routes->post('/member/register/duplicate', 'User\Member::registerDuplicate');
$routes->post('/member/register/process', 'User\Member::registerProcess');
$routes->post('/member/send/id', 'User\Member::sendId');
$routes->post('/member/send/password', 'User\Member::sendPassword');
$routes->post('/member/update/password', 'User\Member::updatePassword');
$routes->post('/member/mypage/update', 'User\Member::mypageUpdate');
$routes->post('/member/password/change/update', 'User\Member::passwordChangeUpdate');
$routes->post('/member/authenticate/confirm', 'User\Member::authenticateConfirm');
$routes->post('/member/sns/disconnect', 'User\Sns::disconnect');

$routes->post('/file/upload/general', 'User\File::uploadGeneral');
$routes->post('/file/upload/board', 'User\File::uploadBoard');
$routes->post('/file/upload/image', 'User\File::uploadImage');
$routes->post('/file/upload/original', 'User\File::uploadOriginal');
$routes->post('/file/upload/dropzone', 'User\File::uploadDropzone');

$routes->post('/board/(:alphanum)/update', 'User\Board::update/$1');
$routes->post('/board/(:alphanum)/temp/save', 'User\Board::tempSave/$1');
$routes->post('/board/(:alphanum)/delete', 'User\Board::delete');

$routes->post('/main/popup/block', 'User\Home::popupBlock');

$routes->post('/comment/insert', 'User\Comment::insert');
$routes->post('/comment/delete', 'User\Comment::delete');
$routes->post('/comment/edit/(:num)', 'User\Comment::edit/$1');
$routes->post('/comment/update', 'User\Comment::update');

$routes->post('/inquiry/update', 'User\Inquiry::update');

// ─────────────────────────────────────────────────────────
// 배치 CLI 라우트 (웹 접근 불가, CLI 전용)
// 실행 예시: php public/index.php batch/housekeeping
// ─────────────────────────────────────────────────────────
$routes->cli('batch/(:segment)', 'Batch\Batch::$1');
