<?php

function redirect_alert($message, $url)
{
    echo '<script>alert("'.$message.'"); location.href="'.$url.'" </script>';
}

function _alert($message)
{
    echo '<script>alert("'.$message.'"); </script>';
}
