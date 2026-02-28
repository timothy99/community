<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\SettingsModel;

class Settings extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/slide/list');
    }

    public function boardList()
    {
        $settings_model = new SettingsModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'title';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $settings_model->getBoardList($data);
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

        return aview('/console/settings/board/list', $proc_result);
    }

    public function boardWrite()
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $random_board_number = $settings_model->getBoardNumber();

        $info = (object)array();
        $info->board_config_idx = 0;
        $info->board_id = 'board'.$random_board_number;
        $info->type = 'board';
        $info->category = '';
        $info->category_yn = 'N';
        $info->user_write = 'N';
        $info->comment_write = 'N';
        $info->title = '게시판'.$random_board_number;
        $info->base_rows = 10;
        $info->reg_date_yn = 'N';
        $info->file_yn = 'N';
        $info->file_cnt = '10';
        $info->file_upload_size_limit = '10';
        $info->file_upload_size_total = '100';
        $info->write_point = 5;
        $info->comment_point = 1;
        $info->form_style = '';
        $info->form_style_yn = 'N';
        $info->hit_edit_yn = 'N';
        $info->hit_yn = 'N';
        $info->heart_yn = 'N';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/settings/board/edit', $proc_result);
    }

    public function boardUpdate()
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $board_config_idx = $this->request->getPost('board_config_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $board_id = $this->request->getPost('board_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $type = $this->request->getPost('type', FILTER_SANITIZE_SPECIAL_CHARS);
        $base_rows = $this->request->getPost('base_rows', FILTER_SANITIZE_SPECIAL_CHARS);
        $category_yn = $this->request->getPost('category_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $category = $this->request->getPost('category', FILTER_SANITIZE_SPECIAL_CHARS);
        $user_write = $this->request->getPost('user_write', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment_write = $this->request->getPost('comment_write', FILTER_SANITIZE_SPECIAL_CHARS);
        $hit_yn = $this->request->getPost('hit_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $heart_yn = $this->request->getPost('heart_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $reg_date_yn = $this->request->getPost('reg_date_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $hit_edit_yn = $this->request->getPost('hit_edit_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $file_cnt = $this->request->getPost('file_cnt', FILTER_SANITIZE_SPECIAL_CHARS);
        $file_upload_size_limit = $this->request->getPost('file_upload_size_limit', FILTER_SANITIZE_SPECIAL_CHARS);
        $file_upload_size_total = $this->request->getPost('file_upload_size_total', FILTER_SANITIZE_SPECIAL_CHARS);
        $write_point = $this->request->getPost('write_point', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment_point = $this->request->getPost('comment_point', FILTER_SANITIZE_SPECIAL_CHARS);
        $form_style_yn = $this->request->getPost('form_style_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $form_style = sanitizeHtml($this->request->getPost('summer_code'));

        // 유효성 검사
        if ($board_id == null || $board_id == '') { $result = false; $message = '게시판 아이디를 입력해주세요.'; }
        if ($result && ($title == null || $title == '')) { $result = false; $message = '게시판 제목을 입력해주세요.'; }
        if ($result && ($base_rows == null || $base_rows == '' || $base_rows < 1)) { $result = false; $message = '화면에 보여줄 줄 수를 입력해주세요.'; }
        if ($result && ($file_cnt == null || $file_cnt == '' || $file_cnt < 0)) { $result = false; $message = '최대 첨부파일 수를 입력해주세요.'; }
        if ($result && ($file_upload_size_limit == null || $file_upload_size_limit == '' || $file_upload_size_limit < 1)) { $result = false; $message = '첨부파일 1개당 업로드 용량 제한을 입력해주세요.'; }
        if ($result && ($file_upload_size_total == null || $file_upload_size_total == '' || $file_upload_size_total < 1)) { $result = false; $message = '첨부파일 전체 업로드 용량 제한을 입력해주세요.'; }
        if ($result && ($write_point == null || $write_point == '' || $write_point < 0)) { $result = false; $message = '글쓰기 포인트를 입력해주세요.'; }
        if ($result && ($category_yn == null || $category_yn == '')) { $result = false; $message = '카테고리 사용여부를 선택해주세요.'; }
        if ($result && ($user_write == null || $user_write == '')) { $result = false; $message = '사용자 글쓰기 가능 여부를 선택해주세요.'; }
        if ($result && ($comment_write == null || $comment_write == '')) { $result = false; $message = '사용자 댓글쓰기 가능 여부를 선택해주세요.'; }
        if ($result && ($reg_date_yn == null || $reg_date_yn == '')) { $result = false; $message = '입력일 수정 기능 사용 여부를 선택해주세요.'; }
        if ($result && ($hit_edit_yn == null || $hit_edit_yn == '')) { $result = false; $message = '조회수 수정 기능 사용 여부를 선택해주세요.'; }
        if ($result && ($hit_yn == null || $hit_yn == '')) { $result = false; $message = '조회수 노출 기능 사용 여부를 선택해주세요.'; }
        if ($result && ($heart_yn == null || $heart_yn == '')) { $result = false; $message = '공감 기능 사용 여부를 선택해주세요.'; }
        if ($result && ($reg_date_yn == null || $reg_date_yn == '')) { $result = false; $message = '입력일 수정 기능 사용 여부를 선택해주세요.'; }
        if ($result && ($hit_edit_yn == null || $hit_edit_yn == '')) { $result = false; $message = '조회수 수정 기능 사용 여부를 선택해주세요.'; }
        if ($result && ($form_style_yn == null || $form_style_yn == '')) { $result = false; $message = '폼 스타일 사용 여부를 선택해주세요.'; }
        if ($result && !preg_match('/^[a-z0-9]+$/', $board_id)) { $result = false; $message = '게시판 아이디는 영문 소문자와 숫자만 사용 가능합니다.'; }

        $data = array();
        $data['board_config_idx'] = $board_config_idx;
        $data['board_id'] = $board_id;
        $data['title'] = $title;
        $data['type'] = $type;
        $data['base_rows'] = $base_rows;
        $data['category_yn'] = $category_yn;
        $data['category'] = $category;
        $data['user_write'] = $user_write;
        $data['comment_write'] = $comment_write;
        $data['hit_yn'] = $hit_yn;
        $data['heart_yn'] = $heart_yn;
        $data['reg_date_yn'] = $reg_date_yn;
        $data['hit_edit_yn'] = $hit_edit_yn;
        $data['file_cnt'] = $file_cnt;
        $data['file_upload_size_limit'] = $file_upload_size_limit;
        $data['file_upload_size_total'] = $file_upload_size_total;
        $data['write_point'] = $write_point;
        $data['comment_point'] = $comment_point;
        $data['form_style_yn'] = $form_style_yn;
        $data['form_style'] = $form_style;

        if ($result == true) {
            if ($board_config_idx == 0) {
                $model_result = $settings_model->procBoardInsert($data);
                $board_config_idx = $model_result['insert_id'];
            } else {
                $model_result = $settings_model->procBoardUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/settings/board/list';
        $proc_result['board_config_idx'] = $board_config_idx;

        return json_encode($proc_result);
    }

    public function boardEdit($board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;

        $model_result = $settings_model->getBoardInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/settings/board/edit', $proc_result);
    }

    public function boardDelete()
    {
        $result = true;
        $message = '정상처리 되었습니다.';

        $settings_model = new SettingsModel();

        $board_config_idx = $this->request->getPost('board_config_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['board_config_idx'] = $board_config_idx;

        $model_result = $settings_model->procBoardDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/settings/board/list';

        return json_encode($proc_result);
    }

    public function boardView($board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;

        $model_result = $settings_model->getBoardInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/settings/board/view', $proc_result);
    }

}
