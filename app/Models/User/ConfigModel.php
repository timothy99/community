<?php

namespace App\Models\User;

use CodeIgniter\Model;

class ConfigModel extends Model
{
    public function getConfigInfo()
    {
        helper('config');

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $info = getConfigInfoCached();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

}
