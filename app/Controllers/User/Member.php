<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

use App\Models\User\MemberModel;

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

        return uview('/user/member/login');
    }

    public function register()
    {
        // 이미 로그인을 한 상태라면 메인으로 보낸다.
        $auth_group = getUserSessionInfo('auth_group');
        if ($auth_group != 'guest') {
            return redirect()->to('/');
        }

        return uview('/user/member/register');
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

        $return_url = '/member/login';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = $return_url;

        return $this->response->setJSON($proc_result);
    }

}