<?php

namespace App\Models\User;

use CodeIgniter\Model;

class InquiryModel extends Model
{
    public function procInquiryInsert($data)
    {
        $today = date('YmdHis');

        $result = true;
        $message = '문의가 성공적으로 등록되었습니다.';
        $insert_id = 0;

        $name = $data['name'];
        $contents = $data['contents'];
        $phone = $data['phone'];
        $email = $data['email'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('mng_inquiry');
        $builder->set('name', $name);
        $builder->set('contents', $contents);
        $builder->set('phone', $phone);
        $builder->set('email', $email);
        $builder->set('del_yn', 'N');
        $builder->set('ins_date', $today);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;
        $model_result['insert_id'] = $insert_id;

        return $model_result;
    }
}
