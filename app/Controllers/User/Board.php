<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\BoardModel;
use App\Models\User\BoardAuthorityModel;
use App\Models\User\CommentModel;

class Board extends BaseController
{
    public function index()
    {
        return redirect()->to('/usr/board/list');
    }

    public function list($board_id)
    {
        $board_model = new BoardModel();
        $authority_model = new BoardAuthorityModel();

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

        $authority = $authority_model->getAuthorityInfo($data);
        if ($authority->list_authority == "Y" ) {
            // do nothing
        } else {
            redirect_alert("게시판 목록 권한이 없습니다.", getUserSessionInfo("previous_url"));
            exit;
        }
        $data["authority"] = $authority;

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];
        $board_config->category_arr = explode("||", $board_config->category);
        $data['board_config'] = $board_config;

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
        $search_arr['active_class'] = 'active';
        $search_arr['paging_file'] = '/user/'.getUserSessionInfo('language').'/paging/general';
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
        $proc_result['authority'] = $authority;
        $proc_result['html_meta'] = create_meta($board_config->meta_title.'> '.$board_config->title.' > 목록');

        return uview('/user/board/'.$board_config->type.'/list', $proc_result);
    }

    public function write($board_id)
    {
        $board_model = new BoardModel();
        $authority_model = new BoardAuthorityModel();

        $result = true;
        $message = '정상';

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];

        $data = array();
        $data["board_id"] = $board_id;

        $authority = $authority_model->getAuthorityInfo($data);
        if ($authority->write_authority == "Y" ) {
            // do nothing
        } else {
            redirect_alert("게시판 글쓰기 권한이 없습니다.", getUserSessionInfo("previous_url"));
            exit;
        }

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
        $info->file_list = array();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['board_config'] = $board_config;
        $proc_result['authority'] = $authority;
        $proc_result['html_meta'] = create_meta($board_config->meta_title.'> '.$board_config->title.' > 작성');

        return uview('/user/board/'.$board_config->type.'/edit', $proc_result);
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
        $reg_date = $this->request->getPost('reg_date', FILTER_SANITIZE_SPECIAL_CHARS) ?? date('Y-m-d H:i:s');
        $hit_cnt = $this->request->getPost('hit_cnt', FILTER_SANITIZE_SPECIAL_CHARS) ?? 0;
        $file_idxs = $this->request->getPost('file_idxs', FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($title)) { $result = false; $message = '제목을 입력해주세요.'; }
        if (empty($contents)) { $result = false; $message = '내용을 입력해주세요.';}

        $reg_date = convertTextToDate($reg_date, 2, 3);
        if ($file_idxs == '') {
            $file_arr = array();
        } else {
            $file_arr = explode("||", $file_idxs);
        }

        $contents = str_replace('<!--[if !supportEmptyParas]-->&nbsp;<!--[endif]-->', '', $contents);
        // HWP JSON 데이터 제거
        $start = strpos($contents, '<!--[data-hwpjson]');
        if ($start !== false) {
            $end = strpos($contents, '-->', $start);
            if ($end !== false) {
                $contents = substr($contents, 0, $start) . substr($contents, $end + 3);
            }
        }

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
        $data['file_arr'] = $file_arr;

        $db = \Config\Database::connect();
        $db->transStart();

        if ($result == true) {
            if ($board_idx == 0) {
                $model_result = $board_model->procBoardInsert($data, $db);
                $board_idx = $model_result['insert_id'];
            } else {
                $model_result = $board_model->procBoardUpdate($data, $db);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '처리 중 오류가 발생했습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/board/'.$board_id.'/view/'.$board_idx;
        $proc_result['board_idx'] = $board_idx;

        return $this->response->setJSON($proc_result);
    }

    public function view($board_id, $board_idx)
    {
        $board_model = new BoardModel();
        $authority_model = new BoardAuthorityModel();
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;
        $data['board_idx'] = $board_idx;

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];
        $board_config->category_arr = explode("||", $board_config->category);
        $data['board_config'] = $board_config;

        // 게시판 권한관련
        $authority = $authority_model->getAuthorityInfo($data);
        if ($authority->view_authority == "Y" ) {
            // do nothing
        } else {
            redirect_alert("게시판 상세 권한이 없습니다.", getUserSessionInfo("previous_url"));
            exit;
        }

        $model_result = $board_model->getBoardInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        // 조회수 증가
        if ($result == true) {
            $board_model->procBoardHitUpdate($data);
        }

        // 댓글목록
        $model_result = $comment_model->getCommentList($data);
        $comment_list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['comment_list'] = $comment_list;
        $proc_result['board_config'] = $board_config;
        $proc_result['authority'] = $authority;
        $proc_result['html_meta'] = create_meta($board_config->meta_title.'> '.$board_config->title.' > 보기 > '.$info->title);

        return uview('/user/board/'.$board_config->type.'/view', $proc_result);
    }

    public function edit($board_id, $board_idx)
    {
        $board_model = new BoardModel();
        $authority_model = new BoardAuthorityModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;
        $data['board_idx'] = $board_idx;

        // 게시판 설정 가져오기
        $config_result = $board_model->getBoardConfig($board_id);
        $board_config = $config_result['config'];
        $board_config->category_arr = explode("||", $board_config->category);
        $data['board_config'] = $board_config;

        // 게시판 권한관련
        $authority = $authority_model->getAuthorityInfo($data);
        if ($authority->edit_authority == "Y") {
            // do nothing
        } else {
            redirect_alert("게시판 수정 권한이 없습니다.", getUserSessionInfo("previous_url"));
            exit;
        }

        $model_result = $board_model->getBoardInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['board_config'] = $board_config;
        $proc_result['authority'] = $authority;
        $proc_result['html_meta'] = create_meta($board_config->meta_title.'> '.$board_config->title.' > 수정 > '.$info->title);

        return uview('/user/board/'.$board_config->type.'/edit', $proc_result);
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
        $proc_result['return_url'] = '/board/'.$board_id.'/list';

        return $this->response->setJSON($proc_result);
    }

}