<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\InquiryModel;
use App\Models\User\SpreadsheetModel;

class Inquiry extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/inquiry/list');
    }

    public function list()
    {
        $inquiry_model = new InquiryModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'name';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $inquiry_model->getInquiryList($data);
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

        return aview('/console/inquiry/list', $proc_result);
    }

    public function view()
    {
        $inquiry_model = new InquiryModel();

        $result = true;
        $message = '정상';

        $inquiry_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['inquiry_idx'] = $inquiry_idx;

        $model_result = $inquiry_model->getInquiryInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/inquiry/view', $proc_result);
    }

    public function delete()
    {
        $inquiry_model = new InquiryModel();

        $result = true;
        $message = '정상처리';

        $inquiry_idx = $this->request->getPost('inquiry_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($inquiry_idx == null) {
            $result = false;
            $message = '문의 정보가 없습니다.';
        }

        $data = array();
        $data['inquiry_idx'] = $inquiry_idx;

        if ($result == true) {
            $model_result = $inquiry_model->procInquiryDelete($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/inquiry/list';

        return $this->response->setJSON($proc_result);
    }

    // 엑셀다운로드
    public function excel()
    {
        $inquiry_model = new InquiryModel();
        $spreadsheet_model = new SpreadsheetModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        // $search_rows = $this->request->getGet('search_rows') ?? 10; // 엑셀다운로드는 줄 수 제한 없음
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'name';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = 999999;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $inquiry_model->getInquiryList($data);
        $list = $model_result['list'];

        $content_list = array();
        foreach ($list as $no => $val) {
            $content = array();
            $content[] = $val->list_no;
            $content[] = $val->name;
            $content[] = $val->phone;
            $content[] = $val->email;
            $content[] = $val->contents;
            $content[] = $val->ins_date_txt;
            $content_list[] = $content;
        }

        $header_list = array('번호', '이름', '전화번호', '이메일', '내용', '등록일'); // 엑셀 헤더명
        $filename = '문의관리_'.date('Ymd_His').'.xlsx'; // 파일명
        $spreadsheet_model->procExcelWrite($content_list, $filename, $header_list); // 엑셀파일 생성 및 다운로드
    }

}
