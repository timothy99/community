<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\User\FileModel;

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

    public function procConfigUpdate($data)
    {
        $result = true;
        $message = '입력이 잘 되었습니다';

        $title = $data['title'];
        $description = $data['description'];
        $company_logo = $data['company_logo'];
        $phone = $data['phone'];
        $fax = $data['fax'];
        $email = $data['email'];
        $work_hour = $data['work_hour'];
        $post_code = $data['post_code'];
        $addr1 = $data['addr1'];
        $addr2 = $data['addr2'];
        $biz_no = $data['biz_no'];
        $smtp_yn = $data['smtp_yn'];
        $manager_email = $data['manager_email'];
        $admin_two_factor_yn = $data['admin_two_factor_yn'];
        $smtp_mail = $data['smtp_mail'];
        $smtp_user = $data['smtp_user'];
        $smtp_pass = $data['smtp_pass'];
        $smtp_port = $data['smtp_port'];
        $smtp_name = $data['smtp_name'];
        $program_ver = $data['program_ver'];
        $smtp_host = $data['smtp_host'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('config');
        $builder->set('title', $title);
        $builder->set('description', $description);
        $builder->set('company_logo', $company_logo);
        $builder->set('phone', $phone);
        $builder->set('fax', $fax);
        $builder->set('email', $email);
        $builder->set('work_hour', $work_hour);
        $builder->set('post_code', $post_code);
        $builder->set('addr1', $addr1);
        $builder->set('addr2', $addr2);
        $builder->set('biz_no', $biz_no);
        $builder->set('smtp_yn', $smtp_yn);
        $builder->set('manager_email', $manager_email);
        $builder->set('admin_two_factor_yn', $admin_two_factor_yn);
        $builder->set('smtp_mail', $smtp_mail);
        $builder->set('smtp_user', $smtp_user);
        $builder->set('smtp_pass', $smtp_pass);
        $builder->set('smtp_port', $smtp_port);
        $builder->set('smtp_name', $smtp_name);
        $builder->set('program_ver', $program_ver);
        $builder->set('smtp_host', $smtp_host);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

}
