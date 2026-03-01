<?php

namespace App\Models\Console;

use CodeIgniter\Model;

class MemberModel extends Model
{
    public function getMemberList($data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];

        $db = $this->db;
        $builder = $db->table('member');
        $builder->where('del_yn', 'N');
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        $builder->orderBy('member_idx', 'desc');
        $builder->limit($rows, getOffset($page, $rows));
        $cnt = $builder->countAllResults(false);
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $list[$no]->list_no = $cnt-$no-(($page-1)*$rows);
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 1);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;

        return $proc_result;
    }

    public function getMemberInfo($data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $member_id = $data['member_id'];

        $db = $this->db;
        $builder = $db->table('member');
        $builder->where('del_yn', 'N');
        $builder->where('member_id', $member_id);
        $info = $builder->get()->getRow();

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->upd_date_txt = convertTextToDate($info->upd_date, 1, 1);
        $info->email_yn_txt = code_replace("email_yn", $info->email_yn);
        $info->sms_yn_txt = code_replace("sms_yn", $info->sms_yn);
        $info->last_login_date_txt = convertTextToDate($info->last_login_date, 1, 1);
        $info->member_point_txt = number_format($info->member_point);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procMemberInsert($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '슬라이드가 등록되었습니다.';
        $insert_id = 0;

        $title = $data['title'];
        $sub_title = $data['sub_title'];
        $contents = $data['contents'];
        $member_file = $data['member_file'];
        $order_no = $data['order_no'];
        $url_link = $data['url_link'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $display_yn = $data['display_yn'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('member');
        $builder->set('title', $title);
        $builder->set('sub_title', $sub_title);
        $builder->set('contents', $contents);
        $builder->set('member_file', $member_file);
        $builder->set('order_no', $order_no);
        $builder->set('url_link', $url_link);
        $builder->set('start_date', $start_date);
        $builder->set('end_date', $end_date);
        $builder->set('display_yn', $display_yn);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;
        $model_result['insert_id'] = $insert_id;

        return $model_result;
    }

    public function procMemberUpdate($data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $member_id = $data['member_id'];
        $member_name = $data['member_name'];
        $member_nickname = $data['member_nickname'];
        $email_yn = $data['email_yn'];
        $sms_yn = $data['sms_yn'];
        $email = $data['email'];
        $phone = $data['phone'];
        $post_code = $data['post_code'];
        $addr1 = $data['addr1'];
        $addr2 = $data['addr2'];
        $auth_group = $data['auth_group'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('member');
        $builder->set('member_name', $member_name);
        $builder->set('member_nickname', $member_nickname);
        $builder->set('email_yn', $email_yn);
        $builder->set('sms_yn', $sms_yn);
        $builder->set('email', $email);
        $builder->set('phone', $phone);
        $builder->set('post_code', $post_code);
        $builder->set('addr1', $addr1);
        $builder->set('addr2', $addr2);
        $builder->set('auth_group', $auth_group);

        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('member_id', $member_id);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

    public function procMemberDelete($data)
    {
        $session_member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '삭제가 잘 되었습니다';

        $member_id = $data['member_id'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('member');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $session_member_id);
        $builder->set('upd_date', $today);
        $builder->where('member_id', $member_id);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

}
