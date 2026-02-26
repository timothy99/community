<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\ConfigModel;

class Config extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/config/view');
    }

    public function view()
    {
        $config_model = new ConfigModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('/console/config/view', $proc_result);
    }

    public function edit()
    {
        $config_model = new ConfigModel();

        $result = true;
        $message = '정상';

        $config_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['config_idx'] = $config_idx;

        $model_result = $config_model->getConfigInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/config/edit', $proc_result);
    }

    public function update()
    {
        $config_model = new ConfigModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $company_logo = $this->request->getPost('company_logo_hidden', FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = $this->request->getPost('phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $fax = $this->request->getPost('fax', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->getPost('email', FILTER_SANITIZE_SPECIAL_CHARS);
        $work_hour = $this->request->getPost('work_hour', FILTER_SANITIZE_SPECIAL_CHARS);
        $post_code = $this->request->getPost('post_code', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr1 = $this->request->getPost('addr1', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr2 = $this->request->getPost('addr2', FILTER_SANITIZE_SPECIAL_CHARS);
        $biz_no = $this->request->getPost('biz_no', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_yn = $this->request->getPost('smtp_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_mail = $this->request->getPost('smtp_mail', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_user = $this->request->getPost('smtp_user', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_pass = $this->request->getPost('smtp_pass', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_port = $this->request->getPost('smtp_port', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_name = $this->request->getPost('smtp_name', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($title == null) { $result = false; $message = '회사명을 입력해주세요.'; }
        if ($phone == null) { $result = false; $message = '전화번호를 입력해주세요.'; }
        if ($email == null) { $result = false; $message = '이메일을 입력해주세요.'; }
        if ($work_hour == null) { $result = false; $message = '업무시간을 입력해주세요.'; }
        if ($post_code == null) { $result = false; $message = '우편번호를 입력해주세요.'; }
        if ($biz_no == null) { $result = false; $message = '사업자등록번호를 입력해주세요.'; }
        if ($smtp_yn == null) { $result = false; $message = '메일발송기능 사용여부를 입력해주세요.'; }

        $data = array();
        $data['title'] = $title;
        $data['company_logo'] = $company_logo;
        $data['phone'] = $phone;
        $data['fax'] = $fax;
        $data['email'] = $email;
        $data['work_hour'] = $work_hour;
        $data['post_code'] = $post_code;
        $data['addr1'] = $addr1;
        $data['addr2'] = $addr2;
        $data['biz_no'] = $biz_no;
        $data['smtp_yn'] = $smtp_yn;
        $data['smtp_mail'] = $smtp_mail;
        $data['smtp_user'] = $smtp_user;
        $data['smtp_pass'] = $smtp_pass;
        $data['smtp_port'] = $smtp_port;
        $data['smtp_name'] = $smtp_name;

        if ($result == true) {
            $model_result = $config_model->procConfigUpdate($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/config/view';

        return json_encode($proc_result);
    }

}
