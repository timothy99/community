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
