<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\PopupModel;
use App\Models\Console\LanguageModel;

class Popup extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/popup/list');
    }

    public function list()
    {
        $popup_model = new PopupModel();
        $language_model = new LanguageModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'title';
        $search_language = $this->request->getGet('search_language', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;
        $data['search_language'] = $search_language;

        $model_result = $popup_model->getPopupList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

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
        $proc_result['language_list'] = $language_list;

        return aview('/console/popup/list', $proc_result);
    }

    public function write()
    {
        $language_model = new LanguageModel();

        $result = true;
        $message = '정상';

        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

        $info = new \stdClass();
        $info->popup_idx = 0;
        $info->title = '';
        $info->popup_file = '';
        $info->url_link = '';
        $info->disabled_hours = 24;
        $info->display_yn = 'Y';
        $info->start_date_txt = '2000-01-01';
        $info->end_date_txt = '9999-12-31';
        $info->popup_file_info = null;
        $info->language = '';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['language_list'] = $language_list;

        return aview('console/popup/edit', $proc_result);
    }

    public function update()
    {
        $popup_model = new PopupModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $popup_idx = $this->request->getPost('popup_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $url_link = $this->request->getPost('url_link', FILTER_SANITIZE_SPECIAL_CHARS);
        $popup_file = $this->request->getPost('popup_file_hidden', FILTER_SANITIZE_SPECIAL_CHARS);
        $disabled_hours = $this->request->getPost('disabled_hours', FILTER_SANITIZE_SPECIAL_CHARS);
        $display_yn = $this->request->getPost('display_yn');
        $start_date_txt = $this->request->getPost('start_date').' 00:00:00';
        $end_date_txt = $this->request->getPost('end_date').' 23:59:59';
        $language = $this->request->getPost('language', FILTER_SANITIZE_SPECIAL_CHARS);

        $start_date = convertTextToDate($start_date_txt, 2, 3);
        $end_date = convertTextToDate($end_date_txt, 2, 3);

        if ($title == null) {
            $result = false;
            $message = '제목을 입력해주세요.';
        }

        if ($popup_file == null) {
            $result = false;
            $message = '팝업 이미지를 올려주세요.';
        }

        if ($url_link == null) {
            $result = false;
            $message = '링크를 입력해주세요.';
        }

        if ($disabled_hours == null) {
            $result = false;
            $message = '다시 보지 않음 시간을 입력해주세요.';
        }

        $data = array();
        $data['popup_idx'] = $popup_idx;
        $data['title'] = $title;
        $data['popup_file'] = $popup_file;
        $data['url_link'] = $url_link;
        $data['disabled_hours'] = $disabled_hours;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['display_yn'] = $display_yn;
        $data['language'] = $language;

        if ($result == true) {
            if ($popup_idx == 0) {
                $model_result = $popup_model->procPopupInsert($data);
                $popup_idx = $model_result['insert_id'];
            } else {
                $model_result = $popup_model->procPopupUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/popup/view/'.$popup_idx;
        $proc_result['popup_idx'] = $popup_idx;

        return $this->response->setJSON($proc_result);
    }

    public function view()
    {
        $popup_model = new PopupModel();

        $result = true;
        $message = '정상';

        $popup_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['popup_idx'] = $popup_idx;

        $model_result = $popup_model->getPopupInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/popup/view', $proc_result);
    }

    public function edit()
    {
        $popup_model = new PopupModel();
        $language_model = new LanguageModel();

        $result = true;
        $message = '정상';

        $popup_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['popup_idx'] = $popup_idx;

        $model_result = $popup_model->getPopupInfo($data);
        $info = $model_result['info'];

        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['language_list'] = $language_list;

        return aview('console/popup/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = "정상처리 되었습니다.";

        $popup_model = new PopupModel();

        $popup_idx = $this->request->getPost("popup_idx", FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data["popup_idx"] = $popup_idx;

        $model_result = $popup_model->procPopupDelete($data);
        $result = $model_result["result"];
        $message = $model_result["message"];

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["return_url"] = "/csl/popup/list";

        return $this->response->setJSON($proc_result);
    }

}