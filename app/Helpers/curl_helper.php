<?php

// 컬 헬퍼(POST)
function getCurlPost($data)
{
    $url = $data["url"]; // URL
    $data_json = $data["data_json"];

    $result = true;
    $message = "문자가 전송되었습니다.";

    //헤더정보
    $headers = array(
        "cache-control: no-cache",
        "content-type: application/json; charset=utf-8"
    );

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data_json); //JSON 데이터
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    //curl 에러 확인
    if(curl_errno($ch)){
        logMessage(curl_error($ch));
        $result = false;
        $message = "문자 발송에 실패하였습니다.";
    } else {
        // logMessage($response);
    }

    curl_close ($ch);

    $model_result = array();
    $model_result["result"] = $result;
    $model_result["message"] = $message;

    return $model_result;
}

// 컬 헬퍼(GET)
function getCurlGet($data)
{
    $url = $data["url"]; // URL

    $result = true;
    $message = "문자가 전송되었습니다.";

    //헤더정보
    $headers = array(
        "cache-control: no-cache",
        "content-type: application/json; charset=utf-8"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $response = curl_exec($ch);

    //curl 에러 확인
    if(curl_errno($ch)){
        logMessage(curl_error($ch));
        $result = false;
        $message = "문자 발송에 실패하였습니다.";
    } else {
        // logMessage($response);
    }

    curl_close ($ch);

    $model_result = array();
    $model_result["result"] = $result;
    $model_result["message"] = $message;
    $model_result["response"] = $response;

    return $model_result;
}