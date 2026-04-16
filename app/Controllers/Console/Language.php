<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\LanguageModel;
use App\Models\Console\ConfigModel;

class Language extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/language/edit');
    }

    public function edit()
    {
        $language_model = new LanguageModel();
        $config_model = new ConfigModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $config_info = $model_result['info'];

        $model_result = $language_model->getLanguageList();
        $list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $config_info;
        $proc_result['list'] = $list;

        return aview('console/language/edit', $proc_result);
    }

    public function update()
    {
        $language_model = new LanguageModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $language_yn = $this->request->getPost('language_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $language_use = $this->request->getPost('language_use', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['language_yn'] = $language_yn;
        $data['language_use'] = $language_use;

        if ($result == true) {
            $model_result = $language_model->procLanguageUpdate($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/language/edit';

        return $this->response->setJSON($proc_result);
    }

}
