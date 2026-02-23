<?php

namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\Common\DateModel;

class MemberModel extends Model
{
    public function checkSigninInfo($data)
    {
        $result = true;
        $message = '회원가입이 완료되었습니다.';

        $member_id = $data['member_id'];
        $member_password = $data['member_password'];
        $member_password_confirm = $data['member_password_confirm'];
        $member_name = $data['member_name'];
        $member_nickname = $data['member_nickname'];
        $phone = $data['phone'];
        $email = $data['email'];
        $post_code = $data['post_code'];

        if ($member_id == null) {
            $result = false;
            $message = '아이디를 입력해주세요.';
        }

        if ($member_name == null) {
            $result = false;
            $message = '이름을 입력해주세요.';
        }

        if ($member_nickname == null) {
            $result = false;
            $message = '닉네임을 입력해주세요.';
        }

        if ($phone == null) {
            $result = false;
            $message = '전화번호를 입력해주세요.';
        }

        if ($email == null) {
            $result = false;
            $message = '이메일을 입력해주세요.';
        }

        if ($post_code == null) {
            $result = false;
            $message = '주소를 입력해주세요.';
        }

        if ($member_password != $member_password_confirm) {
            $result = false;
            $message = '입력된 비밀번호가 다릅니다.';
        }

        if (strlen($member_password) < 8) {
            $result = false;
            $message = '입력된 비밀번호는 8자리 이상이어야 합니다.';
        }

        if (strlen($member_id) < 6) {
            $result = false;
            $message = '아이디는 6자리 이상 입력해야 합니다.';
        }

        $restrict_id_arr = ['master', 'guest', 'adm', 'root', 'test'];
        foreach ($restrict_id_arr as $no => $val) {
            $restrict_position = strrpos($member_id, $val) ?? 0;
            if ($restrict_position > -1) {
                $result = false;
                $message = '사용이 불가한 아이디입니다. 다른 아이디를 입력해주세요.';
            }
        }

        // 영어 소문자, 숫자만 입력이 가능하도록 한다.
        $pattern_result = preg_match('/[^a-z0-9]/', $member_id);
        if ($pattern_result == true) {
            $result = false;
            $message = '아이디는 영어 소문자, 숫자만 입력이 가능합니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

    public function getMemberIdDuplicate($data)
    {
        $result = true;
        $message = '중복된 아이디가 없습니다.';

        $member_id = $data['member_id'];

        $db = $this->db;
        $builder = $db->table('member');
        $builder->select('count(*) as cnt');
        $builder->where('del_yn', 'N');
        $builder->where('member_id', $member_id);
        $info = $builder->get()->getFirstRow();

        $cnt = $info->cnt;
        if ($cnt > 0) {
            $result = false;
            $message = '아이디가 중복되었습니다. 다른 아이디를 선택해주세요.';
        }

        if (strlen($member_id) < 6) {
            $result = false;
            $message = '아이디는 6자리 이상 입력해야 합니다.';
        }

        $restrict_id_arr = ['master', 'guest', 'adm', 'root', 'test'];
        foreach($restrict_id_arr as $no => $val) {
            $restrict_position = strrpos($member_id, $val) ?? 0;
            if ($restrict_position > -1) {
                $result = false;
                $message = '사용이 불가한 아이디입니다. 다른 아이디를 입력해주세요.';
            }
        }

        // 영어 소문자, 숫자만 입력이 가능하도록 한다.
        $pattern_result = preg_match('/[^a-z0-9]/', $member_id);
        if ($pattern_result == true) {
            $result = false;
            $message = '아이디는 영어 소문자, 숫자만 입력이 가능합니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

    // 회원정보 입력
    public function procMemberUpdate($data, $db = null)
    {
        $result = true;
        $message = '정상처리';

        $today = date('YmdHis');

        $member_id = $data['member_id'];
        $member_password = $data['member_password'];
        $member_name = $data['member_name'];
        $member_nickname = $data['member_nickname'];
        $email = $data['email'];
        $email_yn = $data['email_yn'];
        $sms_yn = $data['sms_yn'];
        $phone = $data['phone'];
        $post_code = $data['post_code'];
        $addr1 = $data['addr1'];
        $addr2 = $data['addr2'];

        $auth_group = '일반';
        $member_password_enc = getPasswordEncrypt($member_password);

        if ($db === null) {
            $db = $this->db;
        }
        $builder = $db->table('member');
        $builder->set('member_id', $member_id);
        $builder->set('member_password', $member_password_enc);
        $builder->set('member_name', $member_name);
        $builder->set('member_nickname', $member_nickname);
        $builder->set('email', $email);
        $builder->set('email_yn', $email_yn);
        $builder->set('sms_yn', $sms_yn);
        $builder->set('phone', $phone);
        $builder->set('post_code', $post_code);
        $builder->set('addr1', $addr1);
        $builder->set('addr2', $addr2);
        $builder->set('auth_group', $auth_group);
        $builder->set('last_login_date', $today);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $member_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['insert_id'] = $insert_id;

        return $proc_result;
    }

}