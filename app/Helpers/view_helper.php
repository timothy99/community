<?php

use App\Models\User\MenuModel;
use App\Models\Console\SettingsModel;
use App\Models\User\LanguageModel;

// 사용자 뷰 (메뉴바가 상단에 있음)
function uview(string $view_file, array $proc_result = array())
{
    $menu_model = new MenuModel();
    $language_model = new LanguageModel();
    helper('config');

    $config_info = getConfigInfoCached();
    $proc_result['config_info'] = $config_info;

    $model_result = $menu_model->getMenuList();
    $menu_list = $model_result['list'];
    $proc_result['menu_list'] = $menu_list;

    // URL의 첫번째 세그먼트(locale)에서 직접 언어 설정
    $language = service('request')->getUri()->getSegment(1);

    $model_result = $language_model->getLanguageList();
    $list = $model_result['list'];
    $proc_result['language_list'] = $list;

    // URL의 첫번째 세그먼트가 유효한 언어 코드인지 체크하여 proc_result에 language 값 저장
    $language_array = array_column($list, 'language_code');
    if (! in_array($language, $language_array)) {
        $language = 'kr';
    }

    $selected_language = $language; // 선택된 언어를 proc_result에 저장하여 뷰에서 사용할 수 있도록 함
    // 첫 번째 세그먼트가 유효한 언어 코드가 아니면 기본값 사용
    if (empty($language)) {
        $language = 'kr';
    }

    // 자동번역 대상 언어인지 체크하여 proc_result에 autotranslate_yn 값 저장
    $proc_result['autotranslate_yn'] = 'N';
    foreach ($list as $no => $val) {
        if ($val->autotranslate_yn == 'Y' && $val->language_code == $language) {
            $proc_result['autotranslate_yn'] = 'Y';
            $language = 'kr'; // 자동번역 대상 언어인 경우, 실제 번역은 한국어로 처리
            break;
        }
    }

    $proc_result['language'] = $language;
    $proc_result['selected_language'] = $selected_language;

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
function aview(string $view_file, array $proc_result = array())
{
    $settings_model = new SettingsModel();
    helper('config');

    $config_info = getConfigInfoCached();
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
