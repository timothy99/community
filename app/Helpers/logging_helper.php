<?php

use Config\Database;
use CodeIgniter\HTTP\UserAgent;

//헤더 정보 만들기
function headerInfo()
{
    $user_agent = new UserAgent();
    $request = \Config\Services::request();

    $device = $user_agent->isMobile() == false ? 'PC' : $user_agent->getMobile(); // 모바일 접속여부
    $browser = $user_agent->getBrowser(); // 브라우저
    $version = $user_agent->getVersion(); // 브라우저의 버전
    $referrer = $user_agent->getReferrer(); // 레퍼러
    $platform = $user_agent->getPlatform(); // 플랫폼(윈도우 버전등)
    $ip = $request->getIPAddress(); // 접속IP
    $uri = $request->getUri()->getPath(); // 접근한 페이지

    $header_string = $device.'|'.$browser.'|'.$version.'|'.$referrer.'|'.$platform.'|'.$ip.'|'.$uri; // 풀버전
    $header_string = $ip.'|'.$uri; // 테스트용 단축 버전

    return $header_string;
}

// 로그 남기기
function logMessage($data)
{
    $header_string = headerInfo();
    ob_start();
    print_r($header_string.' ---> ');
    print_r($data);
    $data_log = ob_get_clean();
    log_message('error', $data_log);

    return true;
}

// var_dump로 로그 남기기
function logMessageDump($data)
{
    $header_string = headerInfo();
    ob_start();
    print_r($header_string.' ---> ');
    var_dump($data);
    $data_log = ob_get_clean();
    log_message('error', $data_log);

    return true;
}

// 쿼리 남기기
function logQuery($data)
{
    $header_string = headerInfo();
    ob_start();

    print_r($header_string.' ---> ');
    print_r($data);

    $data_log = ob_get_clean();
    $data_log = str_replace('SELECT ', 'select ', $data_log);
    $data_log = str_replace('UPDATE ', 'update ', $data_log);
    $data_log = str_replace('INSERT ', 'insert ', $data_log);
    $data_log = str_replace('DELETE ', 'delete ', $data_log);
    $data_log = str_replace(' SET ', ' set ', $data_log);
    $data_log = str_replace(' FROM ', ' from ', $data_log);
    $data_log = str_replace(' JOIN ', ' join ', $data_log);
    $data_log = str_replace(' ON ', ' on ', $data_log);
    $data_log = str_replace('WHERE', 'where', $data_log);
    $data_log = str_replace(' AND ', ' and ', $data_log);
    $data_log = str_replace(' INTO ', ' into ', $data_log);
    $data_log = str_replace(' VALUES ', ' values ', $data_log);
    $data_log = str_replace('`', '', $data_log);
    $data_log = str_replace(chr(10), ' ', $data_log);  // \n (줄바꿈)
    $data_log = str_replace(chr(13), ' ', $data_log);  // \r (캐리지 리턴)

    log_message('error', $data_log);

    return true;
}

// 가장 마지막 쿼리 로그
function logLastQuery()
{
    $db = Database::connect();
    $last_query = $db->getLastQuery()->getQuery();
    logQuery($last_query);

    return true;
}

/**
 * 쿼리 모니터링용 - insert, update, delete 만 기본적으로 로그에 남긴다.
 * Evnets.php 에 쿼리가 실행될때마다 이 함수가 실행
 */
function logModifyQuery()
{
    $db = Database::connect();
    $last_query = $db->getLastQuery()->getQuery();
    $last_query_lower = strtolower($last_query);

    $log_yn = false;
    $insert_position = stripos($last_query_lower, 'nsert');
    $update_position = stripos($last_query_lower, 'pdate');
    $delete_position = stripos($last_query_lower, 'elete');
    $hit_query = stripos($last_query_lower, 'hit_cnt+1'); // hit쿼리인 경우

    if ($insert_position > 0 || $update_position > 0 || $delete_position > 0) {
        $log_yn = true;
    }

    if ($hit_query > 0) { // hit 쿼리는 저장하지 않는다.
        $log_yn = false;
    }

    if ($log_yn == true) { // 최종적으로 로그를 남겨야 할경우 로그를 남긴다.
        logQuery($last_query);
    }

    return true;
}
