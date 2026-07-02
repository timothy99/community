<?php

namespace App\Models\Console;

use CodeIgniter\Model;

class SocialModel extends Model
{
    public function procSocialUpdate($data)
    {
        helper('config');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $social_login_yn = $data['social_login_yn'];
        $sns_kakao_use_yn = $data['sns_kakao_use_yn'] ?? 'N';
        $sns_naver_use_yn = $data['sns_naver_use_yn'] ?? 'N';
        $sns_google_use_yn = $data['sns_google_use_yn'] ?? 'N';
        $sns_apple_use_yn = $data['sns_apple_use_yn'] ?? 'N';

        if ($social_login_yn !== 'Y') {
            $sns_kakao_use_yn = 'N';
            $sns_naver_use_yn = 'N';
            $sns_google_use_yn = 'N';
            $sns_apple_use_yn = 'N';
        }

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('config');
        $builder->set('social_login_yn', $social_login_yn);
        $builder->set('sns_kakao_use_yn', $sns_kakao_use_yn);
        $builder->set('sns_naver_use_yn', $sns_naver_use_yn);
        $builder->set('sns_google_use_yn', $sns_google_use_yn);
        $builder->set('sns_apple_use_yn', $sns_apple_use_yn);
        $builder->set('sns_kakao_client_id', $data['sns_kakao_client_id'] ?? '');
        $builder->set('sns_kakao_client_secret', $data['sns_kakao_client_secret'] ?? '');
        $builder->set('sns_naver_client_id', $data['sns_naver_client_id'] ?? '');
        $builder->set('sns_naver_client_secret', $data['sns_naver_client_secret'] ?? '');
        $builder->set('sns_google_client_id', $data['sns_google_client_id'] ?? '');
        $builder->set('sns_google_client_secret', $data['sns_google_client_secret'] ?? '');
        $builder->set('sns_apple_client_id', $data['sns_apple_client_id'] ?? '');
        $builder->set('sns_apple_team_id', $data['sns_apple_team_id'] ?? '');
        $builder->set('sns_apple_key_id', $data['sns_apple_key_id'] ?? '');
        $builder->set('sns_apple_private_key', $data['sns_apple_private_key'] ?? '');
        $builder->update();

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

}
