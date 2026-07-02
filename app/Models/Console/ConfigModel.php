<?php

namespace App\Models\Console;

use CodeIgniter\Model;

class ConfigModel extends Model
{
    private array $configFields = array(
        'company_logo',
        'construction_yn',
        'login_required_yn',
        'smtp_yn',
        'manager_email',
        'admin_two_factor_yn',
        'smtp_mail',
        'smtp_user',
        'smtp_pass',
        'smtp_port',
        'smtp_name',
        'program_ver',
        'smtp_host',
        'admin_ip_check_yn',
    );

    private array $environmentLanguageFields = array(
        'title',
        'description',
        'phone',
        'fax',
        'email',
        'work_hour',
        'post_code',
        'addr1',
        'addr2',
        'biz_no',
    );

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

    public function procConfigUpdate(array $data)
    {
        helper('config');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $updateData = array();
        foreach ($this->configFields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            $model_result = array();
            $model_result['result'] = true;
            $model_result['message'] = $message;

            return $model_result;
        }

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('config');
        foreach ($updateData as $field => $value) {
            $builder->set($field, $value);
        }
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        if ($result === true) {
            clearConfigInfoCache();
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

    public function getEnvironmentLanguageInfo(string $languageCode)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $info = null;

        $builder = $this->db->table('config_language');
        $builder->where('language_code', $languageCode);
        $info = $builder->get()->getRow();

        if (! is_object($info)) {
            $result = false;
            $message = '해당 언어 정보를 찾을 수 없습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procEnvironmentCommonUpdate(array $data)
    {
        helper('config');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $updateData = array();
        $fields = array('company_logo', 'program_ver');
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            $model_result = array();
            $model_result['result'] = true;
            $model_result['message'] = $message;

            return $model_result;
        }

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('config');
        foreach ($updateData as $field => $value) {
            $builder->set($field, $value);
        }
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        if ($result === true) {
            clearConfigInfoCache();
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

    public function procEnvironmentLanguageUpdate(string $languageCode, array $data)
    {
        helper('config');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $updateData = array();
        foreach ($this->environmentLanguageFields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            $model_result = array();
            $model_result['result'] = true;
            $model_result['message'] = $message;

            return $model_result;
        }

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('config_language');
        foreach ($updateData as $field => $value) {
            $builder->set($field, $value);
        }
        $builder->where('language_code', $languageCode);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        if ($result === true) {
            clearConfigInfoCache();
            clearLanguageCache();
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

}
