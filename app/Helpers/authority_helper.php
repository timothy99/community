<?php

// uri에 따라 권한 체크 후 로그인이 필요한 경우 리다이렉션
function checkAuthority(array $segments)
{
    $user_session = getUserSession();
    $auth_group = $user_session->auth_group;

    $method = service('request')->getMethod();

    // language_yn='Y' 일 때 segments 앞에 locale 코드가 포함될 수 있으므로 제거
    $supportedLocales = config('App')->supportedLocales;
    if (! empty($segments) && in_array($segments[0], $supportedLocales, true)) {
        array_shift($segments);
    }

    $segment0 = $segments[0] ?? null;
    $segment1 = $segments[1] ?? null;

    helper('config');
    $config = getConfigInfoCached();
    $login_required_yn = $config?->login_required_yn ?? 'N';

    $auth_group_arr = ["관리자", "최고관리자"];
    // 관리자 페이지인데, 로그인을 안했다면 로그인 페이지로 보낸다. 
    if ($segment0 == "csl" && in_array($auth_group, $auth_group_arr) == false) {
        if ($method == "POST") { // ajax로 들어온 호출일 경우 로그인 페이지로 리다이렉션 하지 않고 json으로 결과 반환
            $proc_result = array();
            $proc_result["result"] = false;
            $proc_result["message"] = "로그인이 필요한 서비스입니다. 계속 문제가 발생한다면 새로고침하고 다시 입력해주세요.";
            echo json_encode($proc_result, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            header("Location: /member/login");
        }
        exit;
    }

    // 폐쇄몰 모드(로그인 필수)에서는 비로그인(guest) 사용자의 접근을 제한한다.
    if ($login_required_yn === 'Y' && $auth_group === 'guest') {
        $uri = $segment0 . "/" . $segment1;
        $segment2 = $segments[2] ?? null;
        $allowedLoginUris = array(
            'member/login',
            'member/signin',
            'member/find',
            'member/reset',
            'member/sns',
            'construction/',
            'file/view',
        );

        $isAllowed = false;
        foreach ($allowedLoginUris as $allowedUri) {
            if (strpos($uri, $allowedUri) === 0) {
                $isAllowed = true;
                break;
            }
        }

        // /member/find/id, /member/find/password 허용
        if ($segment0 === 'member' && $segment1 === 'find') {
            $isAllowed = true;
        }

        // /member/reset/password/{token} 허용
        if ($segment0 === 'member' && $segment1 === 'reset' && $segment2 === 'password') {
            $isAllowed = true;
        }

        if ($isAllowed === false) {
            if ($method == "POST") {
                $proc_result = array();
                $proc_result["result"] = false;
                $proc_result["message"] = "로그인이 필요한 서비스입니다. 로그인 후 다시 시도해주세요.";
                $proc_result["return_url"] = "/member/login";
                echo json_encode($proc_result, JSON_UNESCAPED_UNICODE);
                exit;
            }

            header("Location: /member/login");
            exit;
        }
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

    /*
     * 입력받은 method 가 post인 경우
     * insert, update, delete, edit 가 포함된 uri는 로그인이 필요한것으로 간주
    */
    if ($method == "POST") {
        $post_arr = array();
        $post_arr[] = "insert";
        $post_arr[] = "update";
        $post_arr[] = "delete";
        $post_arr[] = "edit";

        foreach ($post_arr as $value) {
            if (strpos($uri, $value) !== false && $auth_group == "guest") {
                $proc_result = array();
                $proc_result["result"] = false;
                $proc_result["message"] = "로그인이 필요한 서비스입니다. 계속 문제가 발생한다면 새로고침하고 다시 입력해주세요.";
                echo json_encode($proc_result, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
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
    if (strpos($previous_url, '/member/login') !== false) {
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

    helper('config');
    helper('security');

    $info = getConfigInfoCached();
    $construction_yn = $info?->construction_yn ?? 'N';

    $db = \Config\Database::connect();

    $ip_addr = \getClientIpAddress();
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
        helper('config');
        helper('security');

        $info = getConfigInfoCached();
        $admin_ip_check_yn = $info?->admin_ip_check_yn ?? 'N';

        $db = \Config\Database::connect();

        if ($admin_ip_check_yn == "Y") {
            $ip_addr = \getClientIpAddress();
            $builder = $db->table('ip');
            $builder->where('ip', $ip_addr);
            $ip_info = $builder->get()->getRow();
            $ip = $ip_info->ip ?? '';

            if ($ip !== $ip_addr) {
                // '/'로 보내면 User\Home::index → /home/main 체인이 발생하므로 로그인 페이지로 보낸다
                header('Location: /member/login');
                exit;
            }
        }
    }
}
