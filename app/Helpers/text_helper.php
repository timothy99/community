<?php

// html특수태그 의 줄바꿈 처리 함수 
function nl2br_only($string)
{
    $convert = str_replace("&#13;&#10;", "<br>", $string);

    return $convert;
}

// html특수태그 의 줄바꿈 처리 함수 
function nl2br_rn($string)
{
    $convert = str_replace("&#13;&#10;", "\r\n", $string);

    return $convert;
}

// html 헤드의 메타정보 데이터 생성
function create_meta($title)
{
    $title_arr = explode(" > ", $title);
    $nav_title = $title_arr[count($title_arr)-1];

    $html_meta = array();
    $html_meta["nav_title"] = $nav_title;
    $html_meta["meta"]["title"] = $title;
    $html_meta["meta"]["keywords"] = str_replace(" > ", ",", $html_meta["meta"]["title"]);
    $html_meta["meta"]["description"] = str_replace(" > ", "의 ", $html_meta["meta"]["title"]);
    $html_meta["og"]["type"] = "website";
    $html_meta["og"]["title"] = $html_meta["meta"]["title"];
    $html_meta["og"]["description"] = $html_meta["meta"]["description"];
    $html_meta["og"]["image"] = env("app.baseURL")."/resource/usr/image/logo.png";
    $html_meta["og"]["url"] = current_url();

    // 빵조각을 생성하기 위한 텍스트 변경
    $bread = "";
    $title = $html_meta["meta"]["title"];
    $title_arr = explode(" > ", $title);
    foreach ($title_arr as $no => $val) {
        if ($val == "홈") {
            $bread_html = "<a href=\"/\" class=\"ico_home\">홈</a>";
        } else if ($no == count($title_arr)-1) {
            $bread_html = "<em>$val</em>";
        } else {
            $bread_html = "<span>$val</span>";
        }
        $bread .= $bread_html;
    }

    $html_meta["bread"] = "<div class=\"lnb\"><div class=\"inner\">$bread</div></div>";

    return $html_meta;
}

// 1,000 이상의 숫자는 999로 처리하기
function digit3number($number)
{
    $string = "";

    $convert = $number;
    if ($number >= 1000) {
        $convert = 999;
        $string = "+".$convert;
    } else {
        $string = (string)$convert;
    }

    return $string;
}

// 휴대전화 번호를 3-3-4 또는 3-4-4로 포맷팅하는 함수
function format_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (strlen($phone) == 11) {
        $phone_string = preg_replace("/^(\d{3})(\d{4})(\d{4})$/", "$1-$2-$3", $phone);
    } elseif (strlen($phone) == 10) {
        $phone_string = preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $phone);
    } else {
        $phone_string = $phone;
    }

    return $phone_string;
}


// N이나 Y등 코드를 입력하면 그걸 정해진 텍스트로 보여주는 함수
function code_replace($category, $text)
{
    $replace = array();
    $replace["display_yn"]["Y"] = "노출";
    $replace["display_yn"]["N"] = "미노출";
    $replace["email_yn"]["Y"] = "수신";
    $replace["email_yn"]["N"] = "미수신";
    $replace["sms_yn"]["Y"] = "수신";
    $replace["sms_yn"]["N"] = "미수신";

    return isset($replace[$category][$text]) ? $replace[$category][$text] : $text;
}
