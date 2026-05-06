<?php

// html특수태그 의 줄바꿈 처리 함수 
function nl2br_only($string)
{
    $convert = str_replace('&#13;&#10;', '<br>', $string);

    return $convert;
}

// html특수태그 의 줄바꿈 처리 함수 
function nl2br_rn($string)
{
    $convert = str_replace('&#13;&#10;', '\r\n', $string);

    return $convert;
}

// html 헤드의 메타정보 데이터 생성
function create_meta($title, $description = '')
{
    $config_model = new \App\Models\User\ConfigModel();
    $model_result = $config_model->getConfigInfo();
    $config_info = $model_result['info'];

    $title_arr = explode(' > ', $title);
    $nav_title = $title_arr[count($title_arr)-1];

    $html_meta = array();
    $html_meta['nav_title'] = $nav_title;
    $html_meta['meta']['title'] = $title;
    $html_meta['meta']['description'] = $description ?: str_replace(' > ', '의 ', $html_meta['meta']['title']);
    $html_meta['og']['type'] = 'website';
    $html_meta['og']['title'] = $html_meta['meta']['title'];
    $html_meta['og']['description'] = $html_meta['meta']['description'];
    $html_meta['og']['image'] = env('app.baseURL').'/file/view/'.$config_info->company_logo;
    $html_meta['og']['url'] = current_url();
    $html_meta['og']['site_name'] = $config_info->title;
    $html_meta['og']['locale'] = code_replace('locale', getUserSessionInfo('language'));
    $html_meta['canonical'] = current_url();
    $html_meta['favicon'] = env('app.baseURL').'/favicon.ico';

    return $html_meta;
}

// 1,000 이상의 숫자는 999로 처리하기
function digit3number($number)
{
    $string = '';

    $convert = $number;
    if ($number >= 1000) {
        $convert = 999;
        $string = '+'.$convert;
    } else {
        $string = (string)$convert;
    }

    return $string;
}

// 휴대전화 번호를 3-3-4 또는 3-4-4로 포맷팅하는 함수
function format_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (strlen($phone) == 11) {
        $phone_string = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '$1-$2-$3', $phone);
    } elseif (strlen($phone) == 10) {
        $phone_string = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1-$2-$3', $phone);
    } else {
        $phone_string = $phone;
    }

    return $phone_string;
}


// N이나 Y등 코드를 입력하면 그걸 정해진 텍스트로 보여주는 함수
function code_replace($category, $text)
{
    $replace = array();
    $replace['display_yn']['Y'] = '노출';
    $replace['display_yn']['N'] = '미노출';
    $replace['email_yn']['Y'] = '수신';
    $replace['email_yn']['N'] = '미수신';
    $replace['sms_yn']['Y'] = '수신';
    $replace['sms_yn']['N'] = '미수신';
    $replace['category_yn']['Y'] = '사용';
    $replace['category_yn']['N'] = '미사용';
    $replace['user_write']['Y'] = '가능';
    $replace['user_write']['N'] = '불가능';
    $replace['comment_write']['Y'] = '가능';
    $replace['comment_write']['N'] = '불가능';
    $replace['hit_yn']['Y'] = '사용';
    $replace['hit_yn']['N'] = '미사용';
    $replace['heart_yn']['Y'] = '사용';
    $replace['heart_yn']['N'] = '미사용';
    $replace['reg_date_yn']['Y'] = '사용';
    $replace['reg_date_yn']['N'] = '미사용';
    $replace['hit_edit_yn']['Y'] = '사용';
    $replace['hit_edit_yn']['N'] = '미사용';
    $replace['form_style_yn']['Y'] = '사용';
    $replace['form_style_yn']['N'] = '미사용';
    $replace['pdf_yn']['Y'] = '사용';
    $replace['pdf_yn']['N'] = '미사용';
    $replace['youtube_yn']['Y'] = '사용';
    $replace['youtube_yn']['N'] = '미사용';
    $replace['authority_role']['list'] = '목록';
    $replace['authority_role']['view'] = '상세';
    $replace['authority_role']['write'] = '쓰기';
    $replace['authority_role']['edit'] = '수정';
    $replace['authority_role']['delete'] = '삭제';
    $replace['url_link_yn']['Y'] = '사용';
    $replace['url_link_yn']['N'] = '미사용';
    $replace['main_image_yn']['Y'] = '사용';
    $replace['main_image_yn']['N'] = '미사용';
    $replace['notice_yn']['Y'] = '공지';
    $replace['notice_yn']['N'] = '일반';
    $replace['url_target']['_self'] = '현재창';
    $replace['url_target']['_blank'] = '새창';
    $replace['locale']['kor'] = 'ko_KR';
    $replace['locale']['eng'] = 'en_US';
    $replace['locale']['jpn'] = 'ja_JP';
    $replace['locale']['chn'] = 'zh_CN';
    $replace['language']['kor'] = '한국어';
    $replace['language']['eng'] = '영어';
    $replace['language']['jpn'] = '일본어';
    $replace['language']['chn'] = '중국어';
    $replace['secret_comment_yn']['Y'] = '사용';
    $replace['secret_comment_yn']['N'] = '미사용';

    return isset($replace[$category][$text]) ? $replace[$category][$text] : $text;
}
