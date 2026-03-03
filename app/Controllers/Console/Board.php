<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\BoardModel;

class Board extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/board/list');
    }

    public function list($board_id)
    {
        $board_model = new BoardModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'title';
        $category = $this->request->getGet('category', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

        $data = array();
        $data['board_id'] = $board_id;
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;
        $data['category'] = $category;

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];

        // 공지사항 표시가 안된 일반 데이터
        $data['notice_yn'] = 'N';
        $data['reg_date_yn'] = $board_config->reg_date_yn;
        $model_result = $board_model->getBoardList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        // 공지사항인 일반 데이터
        $data['notice_yn'] = 'Y';
        $data['reg_date_yn'] = $board_config->reg_date_yn;
        $model_result = $board_model->getBoardList($data);
        $notice_list = $model_result['list'];

        // 카테고리 데이터 원복
        $data['category'] = $category;

        $search_arr = array();
        $search_arr['search_condition'] = $search_condition;
        $search_arr['search_text'] = $search_text;
        $search_arr['search_page'] = $search_page;
        $search_arr['search_rows'] = $search_rows;
        $search_arr['category'] = $category;
        $search_arr['cnt'] = $cnt;
        $paging_info = getPagingInfo($search_arr);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['notice_list'] = $notice_list;
        $proc_result['cnt'] = $cnt;
        $proc_result['paging_info'] = $paging_info;
        $proc_result['data'] = $data;
        $proc_result['board_config'] = $board_config;

        return aview('/console/board/list', $proc_result);
    }

    public function write($board_id)
    {
        $board_model = new BoardModel();

        $result = true;
        $message = '정상';

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];

        $board_config->category_arr = explode("||", $board_config->category);

        $info = new \stdClass();
        $info->board_idx = 0;
        $info->board_id = $board_id;
        $info->category = '';
        $info->title = '';
        if ($board_config->form_style == 'Y') {
            $info->contents = $board_config->form_style;
        } else {
            $info->contents = '';
        }
        $info->main_image_id = '';
        $info->url_link = '';
        $info->pdf_file_id = '';
        $info->youtube_link = '';
        $info->notice_yn = 'N';
        $info->display_yn = 'Y';
        $info->hit_cnt = 0;
        $info->reg_date_txt = date('Y-m-d H:i:s');
        $info->main_image_info = null;
        $info->pdf_file_info = null;

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['board_config'] = $board_config;

        return aview('console/board/edit', $proc_result);
    }

    public function update()
    {
        $board_model = new BoardModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $board_idx = $this->request->getPost('board_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $board_id = $this->request->getPost('board_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $main_image_id = $this->request->getPost('main_image_hidden', FILTER_SANITIZE_SPECIAL_CHARS);
        $pdf_file_id = $this->request->getPost('pdf_file_hidden', FILTER_SANITIZE_SPECIAL_CHARS);
        $notice_yn = $this->request->getPost('notice_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $category = $this->request->getPost('category', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $contents = $this->request->getPost('contents');
        $url_link = $this->request->getPost('url_link', FILTER_SANITIZE_SPECIAL_CHARS);
        $youtube_link = $this->request->getPost('youtube_link', FILTER_SANITIZE_SPECIAL_CHARS);
        $reg_date = $this->request->getPost('reg_date', FILTER_SANITIZE_SPECIAL_CHARS);
        $hit_cnt = $this->request->getPost('hit_cnt', FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($title)) { $result = false; $message = '제목을 입력해주세요.'; }
        if (empty($contents)) { $result = false; $message = '내용을 입력해주세요.';}

        $reg_date = convertTextToDate($reg_date, 2, 3);

        $data = array();
        $data['board_idx'] = $board_idx;
        $data['board_id'] = $board_id;
        $data['category'] = $category;
        $data['title'] = $title;
        $data['contents'] = $contents;
        $data['main_image_id'] = $main_image_id;
        $data['url_link'] = $url_link;
        $data['pdf_file_id'] = $pdf_file_id;
        $data['youtube_link'] = $youtube_link;
        $data['notice_yn'] = $notice_yn;
        $data['hit_cnt'] = $hit_cnt;
        $data['reg_date'] = $reg_date;

        if ($result == true) {
            if ($board_idx == 0) {
                $model_result = $board_model->procBoardInsert($data);
                $board_idx = $model_result['insert_id'];
            } else {
                $model_result = $board_model->procBoardUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/board/'.$board_id.'/view/'.$board_idx;
        $proc_result['board_idx'] = $board_idx;

        return $this->response->setJSON($proc_result);
    }

    public function view($board_id, $board_idx)
    {
        $board_model = new BoardModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;
        $data['board_idx'] = $board_idx;

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];

        $board_config->category_arr = explode("||", $board_config->category);

        $model_result = $board_model->getBoardInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['board_config'] = $board_config;

        return aview('console/board/view', $proc_result);
    }

    public function edit($board_id, $board_idx)
    {
        $board_model = new BoardModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;
        $data['board_idx'] = $board_idx;

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];
        $board_config->category_arr = explode("||", $board_config->category);

        $model_result = $board_model->getBoardInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['board_config'] = $board_config;

        return aview('console/board/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = '정상처리 되었습니다.';

        $board_model = new BoardModel();

        $board_id = $this->request->getPost('board_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $board_idx = $this->request->getPost('board_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['board_id'] = $board_id;
        $data['board_idx'] = $board_idx;

        $model_result = $board_model->procBoardDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/board/'.$board_id.'/list';

        return $this->response->setJSON($proc_result);
    }

}