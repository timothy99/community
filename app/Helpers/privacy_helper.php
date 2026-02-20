<?php

use Config\Database;

//헤더 정보 만들기
function privacyInsert($memo)
{
    $request = \Config\Services::request();

    $ip = $request->getIPAddress(); // 접속IP
    $uri = $_SERVER["app.baseURL"].$_SERVER["REQUEST_URI"]; // 접근한 페이지
    $today = date("YmdHis");
    $member_id = getUserSessionInfo("member_id");

    $db = Database::connect();
    $builder = $db->table("privacy");
    $builder->set("http_link", $uri);
    $builder->set("memo", $memo);
    $builder->set("ip_addr", $ip);
    $builder->set("del_yn", "N");
    $builder->set("ins_id", $member_id);
    $builder->set("ins_date", $today);
    $builder->set("upd_id", $member_id);
    $builder->set("upd_date", $today);
    $result = $builder->insert();

    return $result;
}
