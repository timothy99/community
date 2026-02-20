<?php

// 오프셋 계산
function getOffset($page, $rows)
{
    $offset = ($page-1)*$rows;
    if ($offset < 0) {
        $offset = 0;
    }

    return $offset;
}
