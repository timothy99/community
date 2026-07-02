<?php

// 세션정보 전체 반환
function getSession()
{
    $session = \Config\Services::session();

    return $session;
}

// 세션중 사용자가 생성한 세션의 전체 정보 갖고오기
function getUserSession()
{
    // 세션의 정보중 아이디를 갖고 옵니다.
    $session = \Config\Services::session();
    $user_session = $session->get('user_session');

    return $user_session;
}

// 세션중 사용자가 생성한 세션의 지정된 정보 갖고오기
function getUserSessionInfo($info_id)
{
    $user_session = getUserSession(); // 세션의 정보중 아이디를 갖고 옵니다.
    $info_value = isset($user_session->{$info_id}) == true ? $user_session->{$info_id} : null;

    return $info_value;
}

// 세션정보 저장
function setUserSession($session_info)
{
    // 세션의 정보중 아이디를 갖고 옵니다.
    $session = getSession();
    $session->set('user_session', $session_info);

    return true;
}

// 세션정보의 특정 값에 저장
function setUserSessionInfo($session_id, $session_value)
{
    // 세션의 정보중 아이디를 갖고 옵니다.
    $user_session = getUserSession();
    $user_session->{$session_id} = $session_value;

    return true;
}

// 언제 어디서나 기본 세션 생성하기
function setBaseSession()
{
    // 세션이 비어 있으면 언어설정만 한국어로
    $session_info = new \stdClass(); // 기본 만들기
    $session_info->member_idx = 0; // 인덱스
    $session_info->member_id = "guest"; // 아이디
    $session_info->member_nickname = "손님"; // 별명
    $session_info->auth_group = "guest"; // 권한 그룹
    $session_info->layer_closed = array(); // 레이어 닫은거 먼저 빈 정보 생성
    setUserSession($session_info); // 세션 넣기

    return true; // 세션정보 반환
}

// 현재 요청 URL의 첫 번째 세그먼트를 기준으로 사용자 언어를 판별
function getRequestLanguageFromUri()
{
    helper('config');

    $language = 'kr';
    $request = service('request');
    $firstSegment = $request->getUri()->getSegment(1);

    $config = getConfigInfoCached();
    $isMultilingual = (($config?->language_yn ?? 'N') === 'Y');

    if ($isMultilingual) {
        $validLocales = getLanguageLocaleCodesCached(true);
        if (in_array($firstSegment, $validLocales, true)) {
            $language = $firstSegment;
        }
    }

    return $language;
}