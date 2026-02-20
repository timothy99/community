<?php

function array_pivot($list)
{
    $pivot = [];

    foreach ($list as $item) {
        foreach ($item as $key => $value) {
            if (isset($pivot[$key]) === false) {
                $pivot[$key] = [];
            }

            $pivot[$key][] = $value;
        }
    }

    return $pivot;
}

function array_pivot_map($list)
{
    foreach ($list as $no => $val) {
        $item = (array)$val;
        $list[$no] = $item;
    }
    $pivot_array = call_user_func_array("array_map", array_merge(array(null), $list));

    return $pivot_array;
}