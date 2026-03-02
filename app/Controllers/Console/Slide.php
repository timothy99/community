<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\SlideModel;

class Slide extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/slide/list');
    }

    public function list()
    {
        $slide_model = new SlideModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'title';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $slide_model->getSlideList($data);
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

        return aview('/console/slide/list', $proc_result);
    }

    public function write()
    {
        $result = true;
        $message = '정상';

        $info = new \stdClass();
        $info->slide_idx = 0;
        $info->order_no = 0;
        $info->title = '';
        $info->sub_title = '';
        $info->contents = '';
        $info->slide_file = '';
        $info->url_link = '';
        $info->display_yn = 'Y';
        $info->start_date_txt = '2000-01-01 00:00';
        $info->end_date_txt = '9999-12-31 23:59';
        $info->slide_file_info = null;

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/slide/edit', $proc_result);
    }

    public function update()
    {
        $slide_model = new SlideModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $slide_idx = $this->request->getPost('slide_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $sub_title = $this->request->getPost('sub_title', FILTER_SANITIZE_SPECIAL_CHARS);
        $contents = $this->request->getPost('contents', FILTER_SANITIZE_SPECIAL_CHARS);
        $url_link = $this->request->getPost('url_link', FILTER_SANITIZE_SPECIAL_CHARS);
        $order_no = $this->request->getPost('order_no', FILTER_SANITIZE_SPECIAL_CHARS);
        $slide_file = $this->request->getPost('slide_file_hidden', FILTER_SANITIZE_SPECIAL_CHARS);
        $display_yn = $this->request->getPost('display_yn');
        $start_date_txt = $this->request->getPost('start_date').' 00:00:00';
        $end_date_txt = $this->request->getPost('end_date').' 23:59:59';

        $start_date = convertTextToDate($start_date_txt, 2, 3);
        $end_date = convertTextToDate($end_date_txt, 2, 3);

        if ($title == null) {
            $result = false;
            $message = '제목을 입력해주세요.';
        }

        if ($slide_file == null) {
            $result = false;
            $message = '이미지 파일을 올려주세요.';
        }

        if ($url_link == null) {
            $result = false;
            $message = '링크를 입력해주세요.';
        }

        if ($order_no == null) {
            $result = false;
            $message = '정렬순서를 입력해주세요.';
        }

        $data = array();
        $data['slide_idx'] = $slide_idx;
        $data['title'] = $title;
        $data['sub_title'] = $sub_title;
        $data['contents'] = $contents;
        $data['slide_file'] = $slide_file;
        $data['order_no'] = $order_no;
        $data['url_link'] = $url_link;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['display_yn'] = $display_yn;

        if ($result == true) {
            if ($slide_idx == 0) {
                $model_result = $slide_model->procSlideInsert($data);
                $slide_idx = $model_result['insert_id'];
            } else {
                $model_result = $slide_model->procSlideUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/slide/view/'.$slide_idx;
        $proc_result['slide_idx'] = $slide_idx;

        return $this->response->setJSON($proc_result);
    }

    public function view()
    {
        $slide_model = new SlideModel();

        $result = true;
        $message = '정상';

        $slide_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['slide_idx'] = $slide_idx;

        $model_result = $slide_model->getSlideInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/slide/view', $proc_result);
    }

    public function edit()
    {
        $slide_model = new SlideModel();

        $result = true;
        $message = '정상';

        $slide_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['slide_idx'] = $slide_idx;

        $model_result = $slide_model->getSlideInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/slide/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = "정상처리 되었습니다.";

        $slide_model = new SlideModel();

        $slide_idx = $this->request->getPost("slide_idx", FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data["slide_idx"] = $slide_idx;

        $model_result = $slide_model->procSlideDelete($data);
        $result = $model_result["result"];
        $message = $model_result["message"];

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["return_url"] = "/csl/slide/list";

        return $this->response->setJSON($proc_result);
    }

}