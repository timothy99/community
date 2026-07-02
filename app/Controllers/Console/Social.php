<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\ConfigModel;
use App\Models\Console\SocialModel;

class Social extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/config/sns');
    }

    public function configSns()
    {
        $config_model = new ConfigModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $config_info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $config_info;

        return aview('console/social/edit', $proc_result);
    }

    public function configSnsUpdate()
    {
        $social_model = new SocialModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $social_login_yn = $this->request->getPost('social_login_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $sns_kakao_use_yn = $this->request->getPost('sns_kakao_use_yn', FILTER_SANITIZE_SPECIAL_CHARS) === 'Y' ? 'Y' : 'N';
        $sns_naver_use_yn = $this->request->getPost('sns_naver_use_yn', FILTER_SANITIZE_SPECIAL_CHARS) === 'Y' ? 'Y' : 'N';
        $sns_google_use_yn = $this->request->getPost('sns_google_use_yn', FILTER_SANITIZE_SPECIAL_CHARS) === 'Y' ? 'Y' : 'N';
        $sns_apple_use_yn = $this->request->getPost('sns_apple_use_yn', FILTER_SANITIZE_SPECIAL_CHARS) === 'Y' ? 'Y' : 'N';

        $sns_kakao_client_id = trim((string)$this->request->getPost('sns_kakao_client_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_kakao_client_secret = trim((string)$this->request->getPost('sns_kakao_client_secret', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_naver_client_id = trim((string)$this->request->getPost('sns_naver_client_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_naver_client_secret = trim((string)$this->request->getPost('sns_naver_client_secret', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_google_client_id = trim((string)$this->request->getPost('sns_google_client_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_google_client_secret = trim((string)$this->request->getPost('sns_google_client_secret', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_apple_client_id = trim((string)$this->request->getPost('sns_apple_client_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_apple_team_id = trim((string)$this->request->getPost('sns_apple_team_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_apple_key_id = trim((string)$this->request->getPost('sns_apple_key_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $sns_apple_private_key = trim((string)$this->request->getPost('sns_apple_private_key', FILTER_SANITIZE_SPECIAL_CHARS));

        $data = array();
        $data['social_login_yn'] = $social_login_yn;
        $data['sns_kakao_use_yn'] = $sns_kakao_use_yn;
        $data['sns_naver_use_yn'] = $sns_naver_use_yn;
        $data['sns_google_use_yn'] = $sns_google_use_yn;
        $data['sns_apple_use_yn'] = $sns_apple_use_yn;
        $data['sns_kakao_client_id'] = $sns_kakao_client_id;
        $data['sns_kakao_client_secret'] = $sns_kakao_client_secret;
        $data['sns_naver_client_id'] = $sns_naver_client_id;
        $data['sns_naver_client_secret'] = $sns_naver_client_secret;
        $data['sns_google_client_id'] = $sns_google_client_id;
        $data['sns_google_client_secret'] = $sns_google_client_secret;
        $data['sns_apple_client_id'] = $sns_apple_client_id;
        $data['sns_apple_team_id'] = $sns_apple_team_id;
        $data['sns_apple_key_id'] = $sns_apple_key_id;
        $data['sns_apple_private_key'] = $sns_apple_private_key;

        if ($result == true) {
            $model_result = $social_model->procSocialUpdate($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/config/sns';

        return $this->response->setJSON($proc_result);
    }

}
