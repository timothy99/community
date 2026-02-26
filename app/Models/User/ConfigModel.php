<?php

namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\Console\FileModel;

class ConfigModel extends Model
{
    public function getConfigInfo()
    {
        $file_model = new FileModel();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $db = $this->db;
        $builder = $db->table('config');
        $info = $builder->get()->getRow();

        if ($info->company_logo != null) {
            $info->company_logo_info = $file_model->getFileInfo($info->company_logo);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

}
