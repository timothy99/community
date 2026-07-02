<?php

namespace App\Models\User;

use CodeIgniter\Model;

class LanguageModel extends Model
{
    public function getLanguageList()
    {
        helper('config');

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $list = getLanguageListCached(true);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

}
