<?php

namespace App\Models\User;

use CodeIgniter\Model;

class ContentsModel extends Model
{
    public function getContentsInfo($data)
    {
        $result = true;
        $message = "목록 불러오기가 완료되었습니다.";

        $contents_id = $data["contents_id"];

        $db = $this->db;
        $builder = $db->table("contents");
        $builder->where("del_yn", "N");
        $builder->where("contents_id", $contents_id);
        $info = $builder->get()->getRow();

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["info"] = $info;

        return $proc_result;
    }

}
