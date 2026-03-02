<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

use App\Models\User\MemberModel;
use App\Models\User\MailModel;

class Member extends BaseController
{
    public function index()
    {
        return redirect()->to('/');
    }

    public function login()
    {
        // 이미 로그인을 한 상태라면 메인으로 보낸다.
        $auth_group = getUserSessionInfo('auth_group');
        if ($auth_group != 'guest') {
            return redirect()->to('/');
        }

        $proc_result = array();
        $proc_result['html_meta'] = create_meta('홈 > 로그인');

        return uview('/user/member/login', $proc_result);
    }

    public function signin()
    {
        $member_model = new MemberModel();

        $result = true;
        $message = '정상처리';

        $member_id = $this->request->getPost('member_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_password = $this->request->getPost('member_password', FILTER_SANITIZE_SPECIAL_CHARS);
        $ip_address = $this->request->getIPAddress();
        $return_url = getUserSessionInfo('previous_url');

        if ($member_id == null) {
            $result = false;
            $message = '아이디를 입력해주세요';
        }

        if ($member_password == null) {
            $result = false;
            $message = '암호를 입력해주세요';
        }

        if ($result == true) {
            $data = array();
            $data['member_id'] = $member_id;
            $data['member_password'] = $member_password;
            $data['ip_address'] = $ip_address;

            $model_result = $member_model->getMemberLoginInfo($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
            $member_info = $model_result['member_info'];

            if (isset($member_info->member_idx) == false) {
                $result = false;
                $message = '회원정보가 없거나 아이디 또는 암호가 틀립니다. 회원정보를 확인하세요.';
                $member_info = new \stdClass();
                $auth_group = 'guest';
            } else {
                setUserSessionInfo('member_idx', $member_info->member_idx);
                setUserSessionInfo('member_id', $member_info->member_id);
                setUserSessionInfo('member_nickname', $member_info->member_nickname);
                setUserSessionInfo('auth_group', $member_info->auth_group);

                $auth_group = $member_info->auth_group;
            }

            $auth_group = getUserSessionInfo('auth_group');
            if (in_array($auth_group, ['관리자', '최고관리자']) == true) {
                $return_url = '/csl';
            }
        } else {
            $result = false;
            $message = '회원정보가 없습니다. 회원정보를 확인하세요.';
            $member_info = new \stdClass();
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = $return_url;
        $proc_result['member_info'] = $member_info;

        return $this->response->setJSON($proc_result);
    }

    public function register()
    {
        // 이미 로그인을 한 상태라면 메인으로 보낸다.
        $auth_group = getUserSessionInfo('auth_group');
        if ($auth_group != 'guest') {
            return redirect()->to('/');
        }

        $proc_result = array();
        $proc_result['html_meta'] = create_meta('홈 > 회원가입');

        return uview('/user/member/register', $proc_result);
    }

    public function registerDuplicate()
    {
        $member_model = new MemberModel();

        $member_id = $this->request->getPost('member_id', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['member_id'] = $member_id;

        $model_result = $member_model->getMemberIdDuplicate($data);

        return $this->response->setJSON($model_result);
    }

    // 회원가입정보 DB입력 처리, update로 하면 계속 로그인창으로 리다이렉션 되므로 process로 변경
    public function registerProcess()
    {
        $member_model = new MemberModel();

        $member_id = $this->request->getPost('member_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_password = $this->request->getPost('member_password', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_password_confirm = $this->request->getPost('member_password_confirm', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_name = $this->request->getPost('member_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_nickname = $this->request->getPost('member_nickname', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);
        $email_yn = $this->request->getPost('email_yn', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'N';
        $sms_yn = $this->request->getPost('sms_yn', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'N';
        $phone = $this->request->getPost('phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $post_code = $this->request->getPost('post_code', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr1 = $this->request->getPost('addr1', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr2 = $this->request->getPost('addr2', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['member_id'] = $member_id;
        $data['member_password'] = $member_password;
        $data['member_password_confirm'] = $member_password_confirm;
        $data['member_name'] = $member_name;
        $data['member_nickname'] = $member_nickname;
        $data['email'] = $email;
        $data['email_yn'] = $email_yn;
        $data['sms_yn'] = $sms_yn;
        $data['phone'] = $phone;
        $data['post_code'] = $post_code;
        $data['addr1'] = $addr1;
        $data['addr2'] = $addr2;

        $model_result = $member_model->checkSigninInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $db = db_connect();
        $db->transStart();

        // 아이디 중복여부 확인 
        if ($result == true) {
            $model_result = $member_model->getMemberIdDuplicate($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        // 이메일 중복여부 확인
        if ($result == true && $email != null) {
            $model_result = $member_model->getEmailDuplicate($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        if ($result == true) {
            $model_result = $member_model->procMemberUpdate($data, $db);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            $result = false;
            $message = 'DB 처리 중 오류가 발생했습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/member/login';

        return $this->response->setJSON($proc_result);
    }

    // 로그아웃
    public function logout()
    {
        session_destroy();

        return redirect()->to('/');
    }

    // 아이디 찾기
    public function findId()
    {
        return uview('/user/member/findId');
    }

    // 아이디 찾기 결과 이메일 발송 처리
    public function sendId()
    {
        $member_model = new MemberModel();
        $mail_model = new MailModel();

        $result = true;
        $message = '해당 메일이 있는 경우 아이디 정보를 이메일로 보냅니다. 메일함을 확인해주세요.';

        $member_name = $this->request->getPost('member_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->getPost('email', FILTER_SANITIZE_EMAIL);

        $data = array();
        $data['member_name'] = $member_name;
        $data['email'] = $email;

        $model_result = $member_model->getMemberInfoByNameAndEmail($data);
        $result = $model_result['result'];
        $info = $model_result['info'];

        if ($result == true) {
            $email = $info->email;
            $member_name = $info->member_name;
            $member_id = $info->member_id;

            $title = env('app.sitename').'에서 요청하신 아이디 정보입니다'; // 제목
            $contents = '요청하신 아이디 정보는 '.$member_id.' 입니다. <br> <a href="'.env('app.baseURL').'/member/login">로그인하러가기</a>'; // 내용

            $email_data = array();
            $email_data['receive_email'] = $email;
            $email_data['title'] = $title;
            $email_data['contents'] = $contents;

            $model_result = $mail_model->getMailConfig();
            $mail_config = $model_result['mail_config'];

            $model_result = $mail_model->procMailSend($email_data, $mail_config);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/member/login';

        return $this->response->setJSON($proc_result);
    }

    // 암호 찾기
    public function findPassword()
    {
        return uview('/user/member/findPassword');
    }

    // 암호 초기화 메일 보내기
    public function sendPassword()
    {
        $member_model = new MemberModel();
        $mail_model = new MailModel();

        $result = true;
        $message = '해당 메일이 있는 경우 아이디 정보를 이메일로 보냅니다. 메일함을 확인해주세요.';

        $email = $this->request->getPost('email');
        $member_id = $this->request->getPost('member_id');

        $data = array();
        $data['email'] = $email;
        $data['member_id'] = $member_id;

        $model_result = $member_model->getPasswordInfo($data);
        $info = $model_result['info'];

        $db = db_connect();
        $db->transStart();

        // 정보가 있는 경우 패스워드 초기화 정보를 등록한다.
        if ($info != null) {
            $model_result = $member_model->procResetDelete($data, $db);
            $model_result = $member_model->procResetInsert($data, $db);
            $reset_key = $model_result['reset_key'];
        }

        // 회원정보가 있는 경우 메일을 보낸다.
        if ($info != null) {
            $email = $info->email; // 받는사람
            $title = env('app.sitename').'에서 요청하신 초기화 정보입니다'; // 제목
            $contents = '초기화를 요청하셨습니다.<br><a href="'.env('app.baseURL').'/member/reset/password/'.$reset_key.'" target="_blank">여기</a>를 눌러 초기화를 진행하세요.'; // 내용

            $email_data = array();
            $email_data['receive_email'] = $email;
            $email_data['title'] = $title;
            $email_data['contents'] = $contents;

            $model_result = $mail_model->getMailConfig();
            $mail_config = $model_result['mail_config'];

            $model_result = $mail_model->procMailSend($email_data, $mail_config);
        } else {
            // 검색해서 나오는 이메일 정보가 없는 경우 이메일을 보내지 않는다.
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            $result = false;
            $message = 'DB 처리 중 오류가 발생했습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/member/login';

        return $this->response->setJSON($proc_result);
    }

    public function resetPassword($reset_key)
    {
        $member_model = new MemberModel();

        $data = array();
        $data['reset_key'] = $reset_key;

        $model_result = $member_model->getResetInfo($data);
        $info = $model_result['info'];

        if ($info == null) {
            redirect_alert('초기화 정보가 없습니다. 메일 요청을 여러번 하셨다면 가장 최근의 링크를 선택해주세요.', '/');
            exit;
        }

        $today = date('YmdHis');
        if ($info->expire_date < $today) {
            redirect_alert('유효기간이 만료되었습니다. 다시 요청해주세요.', '/');
        }

        return uview('/user/member/resetPassword', $data);
    }

    public function updatePassword()
    {
        $member_model = new MemberModel();

        $result = true;
        $message = '정상처리';

        $reset_key = $this->request->getPost('reset_key', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_id = $this->request->getPost('member_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->getPost('email', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_password = (string)$this->request->getPost('member_password', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_password_confirm = $this->request->getPost('member_password_confirm', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($member_id == null) {
            $result = false;
            $message = '아이디를 입력해주세요.';
        }

        if ($email == null) {
            $result = false;
            $message = '이메일을 입력해주세요.';
        }

        if ($member_password != $member_password_confirm) {
            $result = false;
            $message = '입력된 비밀번호가 다릅니다.';
        }

        if (strlen($member_password) < 8) {
            $result = false;
            $message = '입력된 비밀번호는 8자리 이상이어야 합니다.';
        }

        $data = array();
        $data['reset_key'] = $reset_key;
        $data['member_password'] = $member_password;

        $model_result = $member_model->getResetInfo($data);
        $info = $model_result['info'];

        if ($info === null) {
            $result = false;
            $message = '초기화 정보가 없습니다';
        }

        $today = date('YmdHis');
        if ($info->expire_date < $today) {
            $result = false;
            $message = '유효기간이 만료되었습니다. 다시 요청해주세요.';
        }

        if ($result == true) {
            $model_result = $member_model->procPasswordReset($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/member/login';

        return $this->response->setJSON($proc_result);
    }


}