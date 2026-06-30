<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Libraries\SnsOAuth;
use App\Models\User\ConfigModel;
use App\Models\User\SnsModel;

class Sns extends BaseController
{
    /** 허용된 SNS provider 목록 (apple은 UI만 표시, 미구현) */
    private const ALLOWED_PROVIDERS = ['naver', 'kakao', 'google', 'apple'];

    private function getConfigInfo(): object
    {
        $config_model = new ConfigModel();
        $model_result = $config_model->getConfigInfo();

        return $model_result['info'];
    }

    private function isProviderEnabled(string $provider): bool
    {
        $config_info = $this->getConfigInfo();
        $social_login_yn = property_exists($config_info, 'social_login_yn') ? $config_info->social_login_yn : 'N';

        if ($social_login_yn !== 'Y') {
            return false;
        }

        $provider_column = [
            'kakao'  => 'sns_kakao_use_yn',
            'naver'  => 'sns_naver_use_yn',
            'google' => 'sns_google_use_yn',
            'apple'  => 'sns_apple_use_yn',
        ];

        if (!isset($provider_column[$provider])) {
            return false;
        }

        $column = $provider_column[$provider];
        $use_yn = property_exists($config_info, $column) ? $config_info->{$column} : 'N';

        return $use_yn === 'Y';
    }

    /** CSRF 방지용 state 생성 */
    private function generateState(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * SNS 로그인 시작 (로그인 모드)
     * GET /member/sns/{provider}
     */
    public function start(string $provider)
    {
        if (!in_array($provider, self::ALLOWED_PROVIDERS, true) || !$this->isProviderEnabled($provider)) {
            redirect_alert('지원하지 않거나 비활성화된 SNS입니다.', '/member/login');
            exit;
        }

        if ($provider === 'apple') {
            redirect_alert('애플 로그인은 현재 준비 중입니다.', '/member/login');
            exit;
        }

        $sns_oauth = new SnsOAuth();
        $state     = $this->generateState();

        setUserSessionInfo('sns_state', $state);
        setUserSessionInfo('sns_connect_mode', 'N');

        $auth_url = $sns_oauth->getAuthUrl($provider, $state);

        return redirect()->to($auth_url);
    }

    /**
     * SNS 연결 시작 (마이페이지 연결 모드)
     * GET /member/sns/{provider}/connect
     */
    public function connect(string $provider)
    {
        $auth_group = getUserSessionInfo('auth_group');
        if ($auth_group === 'guest' || $auth_group === null) {
            return redirect()->to('/member/login');
        }

        if (!in_array($provider, self::ALLOWED_PROVIDERS, true) || !$this->isProviderEnabled($provider)) {
            redirect_alert('지원하지 않거나 비활성화된 SNS입니다.', '/member/mypage');
            exit;
        }

        if ($provider === 'apple') {
            redirect_alert('애플 로그인은 현재 준비 중입니다.', '/member/mypage');
            exit;
        }

        $sns_oauth = new SnsOAuth();
        $state     = $this->generateState();

        setUserSessionInfo('sns_state', $state);
        setUserSessionInfo('sns_connect_mode', 'Y');

        $auth_url = $sns_oauth->getAuthUrl($provider, $state);

        return redirect()->to($auth_url);
    }

    /**
     * OAuth 콜백 처리
     * GET /member/sns/{provider}/callback
     */
    public function callback(string $provider)
    {
        if (!in_array($provider, self::ALLOWED_PROVIDERS, true) || !$this->isProviderEnabled($provider)) {
            redirect_alert('지원하지 않거나 비활성화된 SNS입니다.', '/member/login');
            exit;
        }

        if ($provider === 'apple') {
            redirect_alert('애플 로그인은 현재 준비 중입니다.', '/member/login');
            exit;
        }

        $code  = $this->request->getGet('code');
        $state = $this->request->getGet('state');
        $error = $this->request->getGet('error');

        // 사용자가 로그인 취소
        if ($error) {
            redirect_alert('SNS 로그인이 취소되었습니다.', '/member/login');
            exit;
        }

        // State 검증 (CSRF 방지)
        $saved_state  = getUserSessionInfo('sns_state');
        $connect_mode = getUserSessionInfo('sns_connect_mode');

        // State 즉시 클리어 (재사용 방지)
        setUserSessionInfo('sns_state', null);
        setUserSessionInfo('sns_connect_mode', null);

        if (empty($state) || $state !== $saved_state) {
            redirect_alert('보안 검증에 실패했습니다. 다시 시도해주세요.', '/member/login');
            exit;
        }

        if (empty($code)) {
            redirect_alert('인증 코드가 없습니다. 다시 시도해주세요.', '/member/login');
            exit;
        }

        $sns_oauth = new SnsOAuth();

        // 토큰 교환
        $access_token = $sns_oauth->getToken($provider, $code);
        if ($access_token === null) {
            redirect_alert('SNS 인증에 실패했습니다. 잠시 후 다시 시도해주세요.', '/member/login');
            exit;
        }

        // SNS 고유 ID 조회
        $sns_id = $sns_oauth->getSnsId($provider, $access_token);
        if ($sns_id === null) {
            redirect_alert('SNS 사용자 정보를 가져오지 못했습니다. 잠시 후 다시 시도해주세요.', '/member/login');
            exit;
        }

        $sns_model = new SnsModel();

        // ─── 연결 모드 (마이페이지에서 진입) ────────────────────────────────
        if ($connect_mode === 'Y') {
            $auth_group = getUserSessionInfo('auth_group');
            if ($auth_group === 'guest' || $auth_group === null) {
                redirect_alert('로그인이 필요합니다.', '/member/login');
                exit;
            }

            $member_idx = (int)getUserSessionInfo('member_idx');

            $data = [
                'sns_type'   => $provider,
                'sns_id'     => $sns_id,
                'member_idx' => $member_idx,
            ];

            // 이미 다른 계정에 연결된 SNS인지 확인
            $model_result = $sns_model->getSnsInfo($data);
            if ($model_result['info'] !== null) {
                redirect_alert('이미 다른 계정에 연결된 SNS 계정입니다.', '/member/mypage');
                exit;
            }

            // 연결 저장
            $model_result = $sns_model->procSnsInsert($data);

            if ($model_result['result'] === true) {
                redirect_alert('SNS 연결이 완료되었습니다.', '/member/mypage');
            } else {
                redirect_alert($model_result['message'], '/member/mypage');
            }
            exit;
        }

        // ─── 로그인 모드 ─────────────────────────────────────────────────────
        $data = [
            'sns_type' => $provider,
            'sns_id'   => $sns_id,
        ];

        $model_result = $sns_model->getMemberBySns($data);
        $member_info  = $model_result['info'];

        if ($member_info === null) {
            // 연결된 계정 없음 → 안내 페이지
            $provider_name = $this->getProviderName($provider);
            $proc_result   = [
                'provider'      => $provider,
                'provider_name' => $provider_name,
                'html_meta'     => create_meta('홈 > SNS 로그인'),
            ];

            return uview('/user/member/snsError', $proc_result);
        }

        // 로그인 처리
        $return_url = getUserSessionInfo('previous_url') ?? '/';
        if (empty($return_url) || strpos($return_url, '/member/') === 0) {
            $return_url = '/';
        }

        setUserSessionInfo('member_idx', $member_info->member_idx);
        setUserSessionInfo('member_id', $member_info->member_id);
        setUserSessionInfo('member_nickname', $member_info->member_nickname);
        setUserSessionInfo('auth_group', $member_info->auth_group);

        return redirect()->to($return_url);
    }

    /**
     * SNS 연결 해제
     * POST /member/sns/disconnect
     */
    public function disconnect()
    {
        $auth_group = getUserSessionInfo('auth_group');
        if ($auth_group === 'guest' || $auth_group === null) {
            return $this->response->setJSON([
                'result'  => false,
                'message' => '로그인이 필요합니다.',
            ]);
        }

        $sns_type = $this->request->getPost('sns_type', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!in_array($sns_type, self::ALLOWED_PROVIDERS, true) || $sns_type === 'apple') {
            return $this->response->setJSON([
                'result'  => false,
                'message' => '지원하지 않는 SNS입니다.',
            ]);
        }

        $sns_model  = new SnsModel();
        $member_idx = (int)getUserSessionInfo('member_idx');

        $data = [
            'member_idx' => $member_idx,
            'sns_type'   => $sns_type,
        ];

        $model_result = $sns_model->procSnsDelete($data);

        return $this->response->setJSON([
            'result'     => $model_result['result'],
            'message'    => $model_result['message'],
            'return_url' => '/member/mypage',
        ]);
    }

    /** SNS provider 한글명 반환 */
    private function getProviderName(string $provider): string
    {
        return match ($provider) {
            'naver'  => '네이버',
            'kakao'  => '카카오',
            'google' => '구글',
            'apple'  => '애플',
            default  => $provider,
        };
    }
}
