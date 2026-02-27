<?php

// 오프셋 계산
function getOffset($page, $rows)
{
    // 숫자가 아닌 값을 정수로 변환
    $page = is_numeric($page) ? (int)$page : 1;
    $rows = is_numeric($rows) ? (int)$rows : 10;

    // 최소값 보장
    if ($page < 1) {
        $page = 1;
    }
    if ($rows < 1) {
        $rows = 10;
    }

    $offset = ($page - 1) * $rows;
    if ($offset < 0) {
        $offset = 0;
    }

    return $offset;
}