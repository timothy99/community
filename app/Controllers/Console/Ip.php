<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\IpModel;

class Ip extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/ip/list');
    }

    public function list()
    {
        $ip_model = new IpModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'ip';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $ip_model->getIpList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        $search_arr = array();
        $search_arr['search_condition'] = $search_condition;
        $search_arr['search_text'] = $search_text;
        $search_arr['search_page'] = $search_page;
        $search_arr['search_rows'] = $search_rows;
        $search_arr['cnt'] = $cnt;
        $paging_info = getPagingInfo($search_arr);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;
        $proc_result['paging_info'] = $paging_info;
        $proc_result['data'] = $data;

        return aview('/console/ip/list', $proc_result);
    }

    public function write()
    {
        $result = true;
        $message = '정상';

        $info = new \stdClass();
        $info->ip_idx = 0;
        $info->ip = '';
        $info->memo = '';
        $info->environment_mode = 'production';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/ip/edit', $proc_result);
    }

    public function update()
    {
        $ip_model = new IpModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $ip_idx = $this->request->getPost('ip_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $ip = $this->request->getPost('ip', FILTER_SANITIZE_SPECIAL_CHARS);
        $environment_mode = $this->request->getPost('environment_mode', FILTER_SANITIZE_SPECIAL_CHARS);
        $memo = $this->request->getPost('memo', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['ip_idx'] = $ip_idx;
        $data['ip'] = $ip;
        $data['environment_mode'] = $environment_mode;
        $data['memo'] = $memo;

        if ($result == true) {
            if ($ip_idx == 0) {
                $model_result = $ip_model->procIpInsert($data);
                $ip_idx = $model_result['insert_id'];
            } else {
                $model_result = $ip_model->procIpUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/ip/view/'.$ip_idx;
        $proc_result['ip_idx'] = $ip_idx;

        return $this->response->setJSON($proc_result);
    }

    public function view()
    {
        $ip_model = new IpModel();

        $result = true;
        $message = '정상';

        $ip_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['ip_idx'] = $ip_idx;

        $model_result = $ip_model->getIpInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/ip/view', $proc_result);
    }

    public function edit()
    {
        $ip_model = new IpModel();

        $result = true;
        $message = '정상';

        $ip_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['ip_idx'] = $ip_idx;

        $model_result = $ip_model->getIpInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/ip/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = "정상처리 되었습니다.";

        $ip_model = new IpModel();

        $ip_idx = $this->request->getPost("ip_idx", FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data["ip_idx"] = $ip_idx;

        $model_result = $ip_model->procIpDelete($data);
        $result = $model_result["result"];
        $message = $model_result["message"];

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["return_url"] = "/csl/ip/list";

        return $this->response->setJSON($proc_result);
    }

}