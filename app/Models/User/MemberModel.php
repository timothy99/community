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

    // 이메일 중복여부 확인
    public function getEmailDuplicate($data)
    {
        $result = true;
        $message = '중복된 이메일이 없습니다.';

        $email = $data['email'];

        $db = $this->db;
        $builder = $db->table('member');
        $builder->select('count(*) as cnt');
        $builder->where('del_yn', 'N');
        $builder->where('email', $email);
        $info = $builder->get()->getFirstRow();

        $cnt = $info->cnt;
        if ($cnt > 0) {
            $result = false;
            $message = '이메일이 중복되었습니다. 다른 이메일을 선택해주세요.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

    // 회원정보 입력
    public function procMemberUpdate($data, $db)
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

    // 회원 로그인 결과
    public function getMemberLoginInfo($data)
    {
        $result = true;
        $message = '정상처리';

        $today = date('YmdHis');

        $member_id = $data['member_id'];
        $member_password = $data['member_password'];
        $ip_address = $data['ip_address'];

        $member_password_enc = getPasswordEncrypt($member_password);

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('member');
        $builder->where('del_yn', 'N');
        $builder->where('member_id', $member_id);
        $builder->where('member_password', $member_password_enc);
        $list = $builder->get()->getResult();
        $cnt = count($list);

        if ($cnt == 1) {
            $member_info = $list[0];

            $builder = $db->table('member');
            $builder->set('last_login_date', $today);
            $builder->set('last_login_ip', $ip_address);
            $builder->where('member_id', $member_id);
            $result = $builder->update();
        } else {
            $result = false;
            $message = '회원정보가 다릅니다. 다시 확인해주세요.';
            $member_info = new \stdClass();
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['member_info'] = $member_info;

        return $proc_result;
    }

    // 이름과 이메일을 기준으로 회원 테이블에서 회원 정보찾기
    public function getMemberInfoByNameAndEmail($data)
    {
        $result = true;
        $message = '정상처리';

        $member_name = $data['member_name'];
        $email = $data['email'];

        $db = $this->db;
        $builder = $db->table('member');
        $builder->where('del_yn', 'N');
        $builder->where('member_name', $member_name);
        $builder->where('email', $email);
        $list = $builder->get()->getResult();
        $cnt = count($list);

        if ($cnt == 1) {
            $info = $list[0];
        } else {
            $result = false;
            $message = '회원정보가 다릅니다. 다시 확인해주세요.';
            $info = new \stdClass();
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    // 아이디와 이메일을 기준으로 회원 테이블에서 회원 정보찾기
    public function getPasswordInfo($data)
    {
        $result = true;
        $message = '정상처리';

        $email = $data['email'];
        $member_id = $data['member_id'];

        $db = $this->db;
        $builder = $db->table('member');
        $builder->where('del_yn', 'N');
        $builder->where('email', $email);
        $builder->where('member_id', $member_id);
        $info = $builder->get()->getRow();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    // 기존 리셋정보 삭제
    public function procResetDelete($data, $db)
    {
        $result = true;
        $message = '정상처리';

        $email = $data['email'];
        $member_id = $data['member_id'];

        $builder = $db->table('member_reset');
        $builder->where('email', $email);
        $builder->where('member_id', $member_id);
        $result = $builder->delete();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

    // 리셋정보 등록
    public function procResetInsert($data, $db)
    {
        $result = true;
        $message = '정상처리';

        $email = $data['email'];
        $member_id = $data['member_id'];

        $reset_key = getRandomString(4, 32); // 보안을 위한 랜덤문자 생성
        $expire_date = date('YmdHis', strtotime('+15 minutes'));

        $builder = $db->table('member_reset');
        $builder->set('member_id', $member_id);
        $builder->set('email', $email);
        $builder->set('reset_key', $reset_key);
        $builder->set('expire_date', $expire_date);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['insert_id'] = $insert_id;
        $proc_result['reset_key'] = $reset_key;

        return $proc_result;
    }

    // 리셋키로 리셋정보 찾기
    public function getResetInfo($data)
    {
        $result = true;
        $message = "정상처리";

        $reset_key = $data["reset_key"];

        $db = $this->db;
        $builder = $db->table("member_reset");
        $builder->where("reset_key", $reset_key);
        $info = $builder->get()->getRow();

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["info"] = $info;

        return $proc_result;
    }

    public function procPasswordReset($data)
    {
        $result = true;
        $message = "암호초기화가 완료되었습니다. 다시 로그인해주세요.";

        $model_result = $this->getResetInfo($data);
        $info = $model_result["info"];
        $member_id = $info->member_id;
        $email = $info->email;

        $member_password = $data["member_password"];
        $member_password_enc = getPasswordEncrypt($member_password);

        $db = $this->db;
        $db->transStart();
        $builder = $db->table("member");
        $builder->set("member_password", $member_password_enc);
        $builder->where("email", $email);
        $builder->where("member_id", $member_id);
        $result = $builder->update();

        if ($db->transStatus() === false) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            $db->transRollback();
        } else {
            $db->transCommit();
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;

        return $proc_result;
    }

}
