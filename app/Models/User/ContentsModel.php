<?php

namespace App\Models\User;

use CodeIgniter\Model;

class ContentsModel extends Model
{
    public function getContentsInfo(array $data)
    {
        helper('config');

        $result = true;
        $message = "목록 불러오기가 완료되었습니다.";

        $contents_id = $data["contents_id"];
        $config_info = getConfigInfoCached();
        $isMultilingual = (($config_info?->language_yn ?? 'N') === 'Y');

        $search_language = 'kr';
        if ($isMultilingual) {
            $search_language = getRequestLanguageFromUri();
        }

        $db = $this->db;
        $builder = $db->table('contents');
        $builder->where('del_yn', 'N');
        $builder->where('contents_id', $contents_id);
        $builder->where('language', $search_language);
        $info = $builder->get()->getRow();

        if ($isMultilingual && $info == null && $search_language !== 'kr') {
            $builder = $db->table('contents');
            $builder->where('del_yn', 'N');
            $builder->where('contents_id', $contents_id);
            $builder->where('language', 'kr');
            $info = $builder->get()->getRow();
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["info"] = $info;

        return $proc_result;
    }

}
