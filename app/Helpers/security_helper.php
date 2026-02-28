<?php

/**
 * @author 배진모
 * @see 원하는 요청에 따른 랜덤 문자열 생성
 * @param string $method - 랜덤문자열 생성 방식
 * @param string $length - 랜덤문자열 길이
 * @return string $random_string - 랜덤 문자열
 */
function getRandomString($method, $length)
{
    /**
     * 0 - 모든 문자
     * 1 - 숫자만
     * 2 - 대문자만
     * 3 - 소문자만
     * 4 - 숫자+대문자+소문자
     */
    $characters_number = '0123456789';
    $characters_number2 = '123456789'; // 0이 빠진 숫자
    $characters_lower = 'abcdefghijklmnopqrstuvwxyz';
    $characters_upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_etc = '~!@#<>?,.%^&*(_+=-'; // 특수문자
    $characters_special = '-_'; // 지정문자

    $characters = '';
    if ($method == 0) { // 모든문자
        $characters = $characters_number.$characters_lower.$characters_upper.$characters_etc;
    } elseif ($method == 1) { // 숫자만
        $characters = $characters_number;
    } elseif ($method == 2) { // 대문자만
        $characters = $characters_upper;
    } elseif ($method == 3) { // 소문자만
        $characters = $characters_lower;
    } elseif ($method == 4) { // 숫자+대문자+소문자
        $characters = $characters_number.$characters_lower.$characters_upper;
    } elseif ($method == 5) { // 숫자+대문자+소문자+지정문자
        $characters = $characters_number.$characters_lower.$characters_upper.$characters_special;
    } elseif ($method == 6) { // 0이 빠진 숫자
        $characters = $characters_number2;
    }

    $random_string = '';

    while (strlen($random_string) < $length) {
        $tmp = mt_rand(0, strlen($characters) - 1);
        $random_string .= substr($characters, $tmp, 1);
    }

    return $random_string;
}

/**
 * @author 배진모
 * @see 단방향 SHA512방식 암호화 문자열 제공
 * @param string $password - 암호화 이전 평문 문자열
 * @return string $password_enc - 암호화된 문자열
 */
function getPasswordEncrypt($password)
{
    $password_enc = base64_encode(hash('sha512', $password, true)); // 평문인 암호를 암호화 하여 저장

    return $password_enc;
}

/**
 * @author 배진모
 * @see 암호화
 * @param string $text - 암호화 이전 평문 문자열
 * @return string $encrypted - 암호화된 문자열
 */
function getTextEncrypt($text)
{
    $encryption_key = env('encryption.key', null);
    $encryption_iv = env('encryption.iv', null);
    $encryption_way = env('encryption.way', null);

    $encrypted = @openssl_encrypt($text, $encryption_way, $encryption_key, true, $encryption_iv); // 평문인 암호를 암호화 하여 저장
    $encrypted = base64_encode($encrypted);

    return $encrypted;
}

/**
 * @author 배진모
 * @see 복호화
 * @param string $encrypted - 암호화된 문자열
 * @return string $text - 복호화된 문자열
 */
function getTextDecrypt($encrypted)
{
    $encryption_key = env('encryption.key', null);
    $encryption_iv = env('encryption.iv', null);
    $encryption_way = env('encryption.way', null);

    $encrypted = base64_decode($encrypted);
    $text = @openssl_decrypt($encrypted, $encryption_way, $encryption_key, true, $encryption_iv); // 복호화

    return $text;
}

// SNS로그인을 위한 랜덤 상태 코드 생성
function getSnsState()
{
    $mt = microtime();
    $rand = mt_rand();

    return md5($mt.$rand);
}

// jwt 토큰 decoding
function getJwtTokenDecode($token)
{
    $decode_object = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));

    return $decode_object;
}

/**
 * @author 배진모
 * @see HTML 콘텐츠에서 위험한 태그 제거 (XSS 방지)
 * @param string $html - 정화할 HTML 문자열
 * @param array $allowed_tags - 허용할 태그 배열 (선택사항)
 * @return string - 정화된 HTML 문자열
 */
function sanitizeHtml($html, $allowed_tags = null)
{
    // 기본 허용 태그 (썸머노트 에디터에서 사용 가능한 안전한 태그들)
    if ($allowed_tags === null) {
        $allowed_tags = [
            'p', 'br', 'span', 'div', 'strong', 'em', 'u', 's', 'del', 'ins',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li',
            'a', 'img',
            'table', 'thead', 'tbody', 'tr', 'td', 'th',
            'blockquote', 'pre', 'code',
            'sub', 'sup',
            'hr',
        ];
    }

    // 태그를 문자열 형식으로 변환
    $allowed_tags_str = '<' . implode('><', $allowed_tags) . '>';

    // 1단계: 위험한 태그 제거 (허용된 태그만 남김)
    $html = strip_tags($html, $allowed_tags_str);

    // 2단계: 위험한 이벤트 속성 제거 (on* 이벤트)
    $html = preg_replace('/(<[^>]+)\s+on\w+\s*=\s*(["\'])[^"\']*\2/i', '$1', $html);

    // 3단계: javascript: 프로토콜 제거
    $html = preg_replace('/(<a[^>]+href\s*=\s*["\'])javascript:[^"\']*(["\'])/i', '$1#$2', $html);

    // 4단계: data: 프로토콜 제거 (img 태그 제외)
    $html = preg_replace('/(<(?!img)[^>]+\s+\w+\s*=\s*["\'])data:[^"\']*(["\'])/i', '$1#$2', $html);

    return $html;
}

/**
 * @author 배진모
 * @see CSS 콘텐츠에서 위험한 코드 제거 (XSS 방지)
 * @param string $css - 정화할 CSS 문자열
 * @return string - 정화된 CSS 문자열
 */
function sanitizeCss($css)
{
    // CSS 코드에서 위험한 요소 제거
    // 1. javascript: 프로토콜 제거
    $css = preg_replace('/javascript:/i', '', $css);

    // 2. expression 제거 (IE의 위험한 CSS 기능)
    $css = preg_replace('/expression\s*\(/i', '', $css);

    // 3. @import 제거 (외부 파일 임포트 차단)
    $css = preg_replace('/@import/i', '', $css);

    // 4. behavior 제거 (IE의 위험한 CSS 기능)
    $css = preg_replace('/behavior\s*:/i', '', $css);

    // 5. vbscript: 프로토콜 제거
    $css = preg_replace('/vbscript:/i', '', $css);

    return $css;
}