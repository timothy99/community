<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\InquiryModel;

class Inquiry extends BaseController
{
    public function index()
    {
        return redirect()->to('/inquiry/write');
    }

    public function write()
    {
        $proc_result = array();
        $proc_result['html_meta'] = create_meta('홈 > 문의하기');

        return uview('/user/inquiry/write', $proc_result);
    }

    public function update()
    {
        $inquiry_model = new InquiryModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $name = $this->request->getPost('name', FILTER_SANITIZE_SPECIAL_CHARS);
        $contents = $this->request->getPost('contents', FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = $this->request->getPost('phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->getPost('email', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($name == null || trim($name) == '') {
            $result = false;
            $message = '이름을 입력해주세요.';
        }

        if ($result == true && ($phone == null || trim($phone) == '')) {
            $result = false;
            $message = '전화번호를 입력해주세요.';
        }

        if ($result == true && ($email == null || trim($email) == '')) {
            $result = false;
            $message = '이메일을 입력해주세요.';
        }

        if ($result == true && ($contents == null || trim($contents) == '')) {
            $result = false;
            $message = '문의내용을 입력해주세요.';
        }

        $data = array();
        $data['name'] = $name;
        $data['contents'] = $contents;
        $data['phone'] = $phone;
        $data['email'] = $email;

        if ($result == true) {
            $model_result = $inquiry_model->procInquiryInsert($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/';

        return $this->response->setJSON($proc_result);
    }

}
