<?php

namespace App\Models\Console;

use CodeIgniter\Model;

class LanguageModel extends Model
{
    public function getLanguageList()
    {
        helper('config');

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $list = getLanguageListCached(false);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    public function getLanguageUseList()
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


    public function procLanguageUpdate($data)
    {
        helper('config');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $language_yn = $data['language_yn'];
        $language_use = $data['language_use'];
        $language_autotranslate = $data['language_autotranslate'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('config');
        $builder->set('language_yn', $language_yn);
        $builder->update();

        // 모든 데이터를 N으로 업데이트 후, Y인 데이터만 Y로 업데이트
        $builder = $db->table('config_language');
        $builder->set('use_yn', 'N');
        $builder->update();

        $builder = $db->table('config_language');
        $builder->set('use_yn', 'Y');
        $builder->whereIn('language_code', $language_use);
        $builder->update();

        $builder = $db->table('config_language');
        $builder->set('autotranslate_yn', 'N');
        $builder->update();

        $builder = $db->table('config_language');
        $builder->set('autotranslate_yn', 'Y');
        $builder->whereIn('language_code', $language_autotranslate);
        $builder->update();

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
