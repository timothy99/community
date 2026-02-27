<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\MemberModel;

class Member extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/member/list');
    }

    public function list()
    {
        $member_model = new MemberModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'member_name';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $member_model->getMemberList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        $search_arr = array();
        $search_arr['search_condition'] = $search_condition;
        $search_arr['search_text'] = $search_text;
        $search_arr['search_page'] = $search_page;
        $search_arr['search_rows'] = $search_rows;
        $http_query = http_build_query($search_arr);
        $search_arr['cnt'] = $cnt;
        $paging_info = getPagingInfo($search_arr);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;
        $proc_result['paging_info'] = $paging_info;
        $proc_result['data'] = $data;
        $proc_result['http_query'] = $http_query;

        return aview('/console/member/list', $proc_result);
    }

    public function update()
    {
        $member_model = new MemberModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $member_id = $this->request->getPost('member_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_name = $this->request->getPost('member_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $member_nickname = $this->request->getPost('member_nickname', FILTER_SANITIZE_SPECIAL_CHARS);
        $email_yn = $this->request->getPost('email_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $sms_yn = $this->request->getPost('sms_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->getPost('email', FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = $this->request->getPost('phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $post_code = $this->request->getPost('post_code', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr1 = $this->request->getPost('addr1', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr2 = $this->request->getPost('addr2', FILTER_SANITIZE_SPECIAL_CHARS);
        $auth_group = $this->request->getPost('auth_group', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($member_name == null) { $result = false; $message = '이름을 입력해주세요.'; }
        if ($member_nickname == null) { $result = false;$message = '별명을 입력해주세요.'; }
        if ($email == null) { $result = false; $message = '이메일을 입력해주세요.'; }
        if ($phone == null) { $result = false; $message = '전화번호를 입력해주세요.';}
        if ($post_code == null) { $result = false; $message = '주소를 입력해주세요.';}

        $data = array();
        $data['member_id'] = $member_id;
        $data['member_name'] = $member_name;
        $data['member_nickname'] = $member_nickname;
        $data['email_yn'] = $email_yn;
        $data['sms_yn'] = $sms_yn;
        $data['email'] = $email;
        $data['phone'] = $phone;
        $data['post_code'] = $post_code;
        $data['addr1'] = $addr1;
        $data['addr2'] = $addr2;
        $data['auth_group'] = $auth_group;

        $model_result = $member_model->procMemberUpdate($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/member/view/'.$member_id;
        $proc_result['member_id'] = $member_id;

        return json_encode($proc_result);
    }

    public function view($member_id)
    {
        $member_model = new MemberModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['member_id'] = $member_id;

        $model_result = $member_model->getMemberInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/member/view', $proc_result);
    }

    public function edit($member_id)
    {
        $member_model = new MemberModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['member_id'] = $member_id;

        $model_result = $member_model->getMemberInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/member/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = '정상처리 되었습니다.';

        $member_model = new MemberModel();

        $member_id = $this->request->getPost('member_id', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['member_id'] = $member_id;

        $model_result = $member_model->procMemberDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/member/list';

        return json_encode($proc_result);
    }

}