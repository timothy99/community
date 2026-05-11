<?php

// uri에 따라 권한 체크 후 로그인이 필요한 경우 리다이렉션
function checkAuthority($segments)
{
    $user_session = getUserSession();
    $auth_group = $user_session->auth_group;

    $segment0 = $segments[0] ?? null;
    $segment1 = $segments[1] ?? null;
    $segment2 = $segments[2] ?? null;

    $auth_group_arr = ["관리자", "최고관리자"];

    // 관리자 페이지인데, 로그인을 안했다면 로그인 페이지로 보낸다. 
    if ($segment0 == "csl" && in_array($auth_group, $auth_group_arr) == false) {
        header("Location: /member/login");
        exit;
    }

    // 반드시 로그인이 필요한 페이지
    $uri = $segment0."/".$segment1;

    $login_arr = array();
    $login_arr[] = "member/view";
    $login_arr[] = "member/leave";
    $login_arr[] = "member/delete";
    $login_arr[] = "member/edit";
    if (in_array($uri, $login_arr) == true && $auth_group == "guest") {
        header("Location: /member/login");
        exit;
    }

    // 등록수정삭제등에는 로그인이 필요하다.
    $authority_arr = ["write", "update", "delete", "edit"];
    if (in_array($segment2, $authority_arr) && $auth_group == "guest") {
        header("Location: /member/login");
        exit;
    }
}

/*
    이전 url을 세션에 저장한다.
    1. ajax로 들어온 경우 저장하지 않는다.
    2. 이전 url과 현재 url이 같으면 저장하지 않는다.
    3. 이전페이지가 로그인 페이지면 입력하지 않는다.
*/
function setPreviousUrl()
{
    $previous_url = previous_url();
    $current_url = current_url();
    $url_save_yn = true;

    $request = \Config\Services::request();
    $is_ajax = $request->isAJAX(); // ajax 확인
    $is_post = $request->getMethod() == "post"; // post 확인

    // 이전페이지가 로그인 페이지면 입력하지 않는다.
    if (strpos($previous_url, "/member/login") > 0) {
        $url_save_yn = false;
    }

    // ajax로 들어온 호출일 경우 이전 url을 저장하지 않는다.
    if ($is_ajax == true) {
        $url_save_yn = false;
    }

    // post로 들어온 호출일 경우 이전 url을 저장하지 않는다.
    if ($is_post == true) {
        $url_save_yn = false;
    }

    // 이전 url과 현재 url이 같을 경우 이전 url을 저장하지 않는다.
    if ($previous_url == $current_url) {
        $url_save_yn = false;
    }

    if ($url_save_yn == true) {
        setUserSessionInfo("previous_url", $previous_url); // 이전 url
    }

    setUserSessionInfo("current_url", $current_url); // 현재 사용자가 보고 있는 화면 url
}

/*
    1. 환경설정의 공사중 여부를 확인하여 공사중이면 공사중 페이지로 바로 리다이렉션
    2. 만약 IP가 등록된 IP가 아니라면 공사중 페이지로 바로 리다이렉션
*/
function checkConstruction()
{
    // 이미 공사중 페이지라면 무한 리다이렉션 방지
    $current_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if ($current_path === '/construction') {
        return;
    }

    $db = \Config\Database::connect();
    $builder = $db->table('config');
    $info = $builder->get()->getRow();
    $construction_yn = $info->construction_yn;

    $ip_addr = $_SERVER['REMOTE_ADDR'] ?? '';
    $builder = $db->table('ip');
    $builder->where('ip', $ip_addr);
    $ip_info = $builder->get()->getRow();
    $ip = $ip_info->ip ?? '';

    // 공사중이고 등록된 IP가 아닌 경우 공사중 페이지로 리다이렉션
    if ($construction_yn == "Y" && $ip !== $ip_addr) {
        header("Location: /construction");
        exit;
    }
}

/*
    1. 관리자 접속시 IP확인이 활성화 되어 있다면 등록된 IP가 아닌 경우 관리자 페이지 접속 불가
    2. 관리자는 /csl로 시작하는 페이지에 접속하기 때문에 /csl로 시작하는 페이지에 접속할 때 IP체크를 한다.
    3. 등록된 IP가 아닌 경우 메인 페이지로 리다이렉션. 경고창 보여주지 않음
*/
function checkAdminIp()
{
    $current_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (strpos($current_path, '/csl') === 0) {
        $db = \Config\Database::connect();
        $builder = $db->table('config');
        $info = $builder->get()->getRow();
        $admin_ip_check_yn = $info->admin_ip_check_yn;

        if ($admin_ip_check_yn == "Y") {
            $ip_addr = $_SERVER['REMOTE_ADDR'] ?? '';
            $builder = $db->table('ip');
            $builder->where('ip', $ip_addr);
            $ip_info = $builder->get()->getRow();
            $ip = $ip_info->ip ?? '';

            if ($ip !== $ip_addr) {
                header("Location: /");
                exit;
            }
        }
    }
}
