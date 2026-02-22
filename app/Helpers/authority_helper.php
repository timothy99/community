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