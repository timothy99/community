<?php

namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\User\FileModel;

class BoardModel extends Model
{
    public function getBoardConfig($board_id)
    {
        $result = true;
        $message = '게시판 설정을 가져왔습니다.';

        $db = $this->db;
        $builder = $db->table('board_config');
        $builder->where('del_yn', 'N');
        $builder->where('board_id', $board_id);
        $config = $builder->get()->getRow();

        if (!$config) {
            // 기본 설정 반환
            $config = new \stdClass();
            $config->board_id = $board_id;
            $config->category_yn = 'N';
            $config->reg_date_yn = 'N';
            $config->hit_yn = 'Y';
            $config->hit_edit_yn = 'N';
            $config->heart_yn = 'Y';
            $config->pdf_yn = 'N';
            $config->youtube_yn = 'N';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['config'] = $config;

        return $proc_result;
    }

    public function getBoardList($data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $board_id = $data['board_id'];
        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];
        $category = $data['category'] ?? '';
        $notice_yn = $data['notice_yn'] ?? 'N';

        $db = $this->db;
        $builder = $db->table('board');
        $builder->where('del_yn', 'N');
        if ($board_id) {
            $builder->where('board_id', $board_id);
        }
        if ($category) {
            $builder->where('category', $category);
        }
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        $builder->where('notice_yn', $notice_yn);
        $builder->orderBy('board_idx_desc', 'asc');
        $builder->limit($rows, getOffset($page, $rows));
        $cnt = $builder->countAllResults(false);
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $list[$no]->list_no = $cnt-$no-(($page-1)*$rows);
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 1);
            $list[$no]->reg_date_txt = $val->reg_date ? convertTextToDate($val->reg_date, 1, 2) : '';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;

        return $proc_result;
    }

    public function getBoardInfo($data)
    {
        $file_model = new FileModel();

        $result = true;
        $message = '게시물 정보를 가져왔습니다.';

        $board_idx = $data['board_idx'];

        $db = $this->db;
        $builder = $db->table('board');
        $builder->where('del_yn', 'N');
        $builder->where('board_idx', $board_idx);
        $info = $builder->get()->getRow();

        if ($info == null) {
            redirect_alert('존재하지 않는 게시물입니다.', '/');
            exit;
        }

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->upd_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->reg_date_txt = convertTextToDate($info->reg_date, 1, 1);
        $info->contents = $info->contents;

        if ($info->main_image_id) {
            $info->main_image_info = $file_model->getFileInfo($info->main_image_id);
            $info->main_image_info->file_size_kb = number_format($info->main_image_info->file_size / 1024, 2);
            $info->main_image_info->image_width_txt = number_format($info->main_image_info->image_width);
            $info->main_image_info->image_height_txt = number_format($info->main_image_info->image_height);
        } else {
            $info->main_image_info = null;
        }

        if ($info->pdf_file_id) {
            $info->pdf_file_info = $file_model->getFileInfo($info->pdf_file_id);
            $info->pdf_file_info->file_size_kb = number_format($info->pdf_file_info->file_size / 1024, 2);
        } else {
            $info->pdf_file_info = null;
        }

        // 유튜브 주소에서 유튜브 아이디 추출
        if ($info->youtube_link) {
            // 유튜브 비디오 ID 추출
            $youtube_id = '';
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $info->youtube_link, $matches)) {
                $youtube_id = $matches[1];
            }
            $info->youtube_id = $youtube_id;
        } else {
            $info->youtube_id = null;
        }

        $builder = $db->table('board_file');
        $builder->where('board_idx', $board_idx);
        $file_list = $builder->get()->getResult();
        foreach ($file_list as $key => $val) {
            $file_list[$key]->file_info = $file_model->getFileInfo($val->file_id);
            $file_list[$key]->file_info->file_size_kb = number_format($file_list[$key]->file_info->file_size / 1024, 2);
        }
        $info->file_list = $file_list;

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procBoardInsert($data, $db)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '게시물이 등록되었습니다.';
        $insert_id = 0;

        $board_id = $data['board_id'];
        $category = $data['category'];
        $title = $data['title'];
        $contents = $data['contents'];
        $main_image_id = $data['main_image_id'];
        $url_link = $data['url_link'];
        $pdf_file_id = $data['pdf_file_id'];
        $youtube_link = $data['youtube_link'];
        $notice_yn = $data['notice_yn'];
        $hit_cnt = $data['hit_cnt'];
        $reg_date = $data['reg_date'];
        $file_arr = $data['file_arr'];

        $builder = $db->table('board');
        $builder->set('board_id', $board_id);
        $builder->set('category', $category);
        $builder->set('title', $title);
        $builder->set('contents', $contents);
        $builder->set('main_image_id', $main_image_id);
        $builder->set('url_link', $url_link);
        $builder->set('pdf_file_id', $pdf_file_id);
        $builder->set('youtube_link', $youtube_link);
        $builder->set('notice_yn', $notice_yn);
        $builder->set('hit_cnt', $hit_cnt);
        $builder->set('reg_date', $reg_date);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        // board_idx_desc 에 $insert_id 의 음수 업데이트
        $builder = $db->table('board');
        $builder->set('board_idx_desc', -$insert_id);
        $builder->where('board_idx', $insert_id);
        $builder->update();

        $builder = $db->table('board_file');
        $builder->where('board_idx', $insert_id);
        $builder->delete();

        foreach ($file_arr as $file_id) {
            $builder = $db->table('board_file');
            $builder->set('board_idx', $insert_id);
            $builder->set('file_id', $file_id);
            $builder->insert();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;
        $model_result['insert_id'] = $insert_id;

        return $model_result;
    }

    public function procBoardUpdate($data, $db)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $board_idx = $data['board_idx'];
        $board_id = $data['board_id'];
        $category = $data['category'];
        $title = $data['title'];
        $contents = $data['contents'];
        $main_image_id = $data['main_image_id'];
        $url_link = $data['url_link'];
        $pdf_file_id = $data['pdf_file_id'];
        $youtube_link = $data['youtube_link'];
        $notice_yn = $data['notice_yn'];
        $hit_cnt = $data['hit_cnt'];
        $reg_date = $data['reg_date'];
        $file_arr = $data['file_arr'];

        $builder = $db->table('board');
        $builder->set('board_id', $board_id);
        $builder->set('category', $category);
        $builder->set('title', $title);
        $builder->set('contents', $contents);
        $builder->set('main_image_id', $main_image_id);
        $builder->set('url_link', $url_link);
        $builder->set('pdf_file_id', $pdf_file_id);
        $builder->set('youtube_link', $youtube_link);
        $builder->set('notice_yn', $notice_yn);
        $builder->set('hit_cnt', $hit_cnt);
        $builder->set('reg_date', $reg_date);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('board_idx', $board_idx);
        $result = $builder->update();

        // board_idx 기준으로 삭제
        $builder = $db->table('board_file');
        $builder->where('board_idx', $board_idx);
        $builder->delete();

        foreach ($file_arr as $file_id) {
            $builder = $db->table('board_file');
            $builder->set('board_idx', $board_idx);
            $builder->set('file_id', $file_id);
            $builder->insert();
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

    public function procBoardDelete($data)
    {
        $member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '삭제가 잘 되었습니다';

        $board_id = $data['board_id'];
        $board_idx = $data['board_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('board');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $builder->where('board_idx', $board_idx);
        $builder->where('board_id', $board_id);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

    // 조회수 증가
    public function procBoardHitUpdate($data)
    {
        $board_idx = $data["board_idx"];
        $board_id = $data["board_id"];

        $result = true;
        $message = "조회수 증가가 완료되었습니다.";

        $db = $this->db;
        $builder = $db->table("board");
        $builder->set("hit_cnt", "hit_cnt+1", false);
        $builder->where("board_idx", $board_idx);
        $builder->where("board_id", $board_id);
        $result = $builder->update();

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;

        return $model_result;
    }

}
