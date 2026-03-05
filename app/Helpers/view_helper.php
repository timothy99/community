<?php

use App\Models\User\MenuModel;
use App\Models\User\ConfigModel;
use App\Models\Console\SettingsModel;

// 사용자 뷰 (메뉴바가 상단에 있음)
function uview($view_file, $proc_result = array())
{
    $menu_model = new MenuModel();
    $config_model = new ConfigModel();

    $model_result = $config_model->getConfigInfo();
    $config_info = $model_result['info'];
    $proc_result['config_info'] = $config_info;

    $model_result = $menu_model->getMenuList();
    $menu_list = $model_result['list'];
    $proc_result['menu_list'] = $menu_list;

    $language = service('request')->getCookie('language') ?? 'kor';
    $proc_result['language'] = $language;

    $proc_result["login_yn"] = loginCheck(); // 로그인 상태 여부 저장

    $view_result = null;

    $view_file = str_replace('/user/', '/user/'.$language.'/', $view_file);

    $view_result .= view('/user/'.$language.'/include/header', $proc_result);
    $view_result .= view('/user/'.$language.'/include/top', $proc_result);
    $view_result .= view('/user/'.$language.'/include/menu', $proc_result);
    $view_result .= view($view_file, $proc_result);
    $view_result .= view('/user/'.$language.'/include/footer', $proc_result);

    return $view_result;
}

// 관리자(admin) 뷰 - 메뉴바가 좌측에 있음
function aview($view_file, $proc_result = array())
{
    $config_model = new ConfigModel();
    $settings_model = new SettingsModel();

    $model_result = $config_model->getConfigInfo();
    $config_info = $model_result['info'];
    $proc_result['config_info'] = $config_info;

    $data = array();
    $data['search_page'] = 1;
    $data['search_rows'] = 9999;
    $data['search_condition'] = '';
    $data['search_text'] = '';
    $model_result = $settings_model->getBoardList($data);
    $board_list = $model_result['list'];
    $proc_result['board_list'] = $board_list;

    $proc_result["login_yn"] = loginCheck(); // 로그인 상태 여부 저장

    $view_result = null;

    $view_result .= view('/console/include/header', $proc_result);
    $view_result .= view('/console/include/top', $proc_result);
    $view_result .= view('/console/include/menu', $proc_result);
    $view_result .= view($view_file, $proc_result);
    $view_result .= view('/console/include/footer', $proc_result);

    return $view_result;
}

// 로그인 여부 확인
function loginCheck()
{
    $login_yn = false;
    // 로그인 상태 여부
    $auth_group = getUserSessionInfo("auth_group");
    if ($auth_group != "guest") {
        $login_yn = true;
    }

    return $login_yn;
}
