<?php

use App\Models\Usr\MenuModel;

// 사용자 뷰 (메뉴바가 상단에 있음)
function uview($view_file, $proc_result = array())
{
    $menu_model = new MenuModel();

    $model_result = $menu_model->getMenuList();
    $menu_list = $model_result["list"];
    $proc_result["menu_list"] = $menu_list;

    $view_result = null;

    $view_result .= view("/usr/include/header", $proc_result);
    $view_result .= view("/usr/include/top", $proc_result);
    $view_result .= view("/usr/include/menu", $proc_result);
    $view_result .= view($view_file, $proc_result);
    $view_result .= view("/usr/include/footer", $proc_result);

    return $view_result;
}

// 관리자(admin) 뷰 - 메뉴바가 좌측에 있음
function aview($view_file, $proc_result = array())
{
    $view_result = null;

    $view_result .= view("/csl/include/header", $proc_result);
    $view_result .= view("/csl/include/top", $proc_result);
    $view_result .= view("/csl/include/menu", $proc_result);
    $view_result .= view($view_file, $proc_result);
    $view_result .= view("/csl/include/footer", $proc_result);

    return $view_result;
}

// 회원가입용 뷰
function mview($view_file, $proc_result = array())
{
    $view_result = null;

    $view_result .= view("/lgn/include/header", $proc_result);
    $view_result .= view($view_file, $proc_result);
    $view_result .= view("/lgn/include/footer", $proc_result);

    return $view_result;
}