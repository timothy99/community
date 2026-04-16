<?php

namespace App\Models\User;

use CodeIgniter\Model;

class LanguageModel extends Model
{
    public function getLanguageList()
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $db = $this->db;
        $builder = $db->table('language');
        $builder->where('use_yn', 'Y');
        $builder->orderBy('language_idx', 'asc');
        $list = $builder->get()->getResult();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

}
