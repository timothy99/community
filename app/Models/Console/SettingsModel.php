<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\Console\FileModel;

class SettingsModel extends Model
{
    public function getBoardList($data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];

        $db = $this->db;
        $builder = $db->table('board_config');
        $builder->where('del_yn', 'N');
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        $builder->orderBy('board_config_idx', 'desc');
        $builder->limit($rows, getOffset($page, $rows));
        $cnt = $builder->countAllResults(false);
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $list[$no]->list_no = $cnt-$no-(($page-1)*$rows);
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 1);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;

        return $proc_result;
    }

    public function getBoardNumber()
    {
        $cnt = 1;
        while ($cnt == 1) {
            $random_board_number = getRandomString(6, 4);
            $random_board_id = "board".$random_board_number;

            $db = $this->db;
            $builder = $db->table("board_config");
            $builder->select("count(*) as cnt");
            $builder->where("del_yn", "N");
            $builder->where("board_id", $random_board_id);
            $info = $builder->get()->getRow();
            $cnt = $info->cnt;
            if ($cnt == 0) {
                break;
            }
        }

        return $random_board_number;
    }

    public function procBoardInsert($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '게시판 설정이 등록되었습니다.';
        $insert_id = 0;

        $board_id = $data['board_id'];
        $title = $data['title'];
        $type = $data['type'];
        $base_rows = $data['base_rows'];
        $category_yn = $data['category_yn'];
        $category = $data['category'];
        $user_write = $data['user_write'];
        $comment_write = $data['comment_write'];
        $hit_yn = $data['hit_yn'];
        $heart_yn = $data['heart_yn'];
        $reg_date_yn = $data['reg_date_yn'];
        $hit_edit_yn = $data['hit_edit_yn'];
        $file_cnt = $data['file_cnt'];
        $file_upload_size_limit = $data['file_upload_size_limit'];
        $file_upload_size_total = $data['file_upload_size_total'];
        $write_point = $data['write_point'];
        $comment_point = $data['comment_point'];
        $form_style_yn = $data['form_style_yn'];
        $form_style = $data['form_style'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('board_config');
        $builder->set('board_id', $board_id);
        $builder->set('title', $title);
        $builder->set('type', $type);
        $builder->set('base_rows', $base_rows);
        $builder->set('category_yn', $category_yn);
        $builder->set('category', $category);
        $builder->set('user_write', $user_write);
        $builder->set('comment_write', $comment_write);
        $builder->set('hit_yn', $hit_yn);
        $builder->set('heart_yn', $heart_yn);
        $builder->set('reg_date_yn', $reg_date_yn);
        $builder->set('hit_edit_yn', $hit_edit_yn);
        $builder->set('file_cnt', $file_cnt);
        $builder->set('file_upload_size_limit', $file_upload_size_limit);
        $builder->set('file_upload_size_total', $file_upload_size_total);
        $builder->set('write_point', $write_point);
        $builder->set('comment_point', $comment_point);
        $builder->set('form_style_yn', $form_style_yn);
        $builder->set('form_style', $form_style);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

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

    public function procBoardUpdate($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '게시판 설정이 수정되었습니다.';

        $board_config_idx = $data['board_config_idx'];
        $board_id = $data['board_id'];
        $title = $data['title'];
        $type = $data['type'];
        $base_rows = $data['base_rows'];
        $category_yn = $data['category_yn'];
        $category = $data['category'];
        $user_write = $data['user_write'];
        $comment_write = $data['comment_write'];
        $hit_yn = $data['hit_yn'];
        $heart_yn = $data['heart_yn'];
        $reg_date_yn = $data['reg_date_yn'];
        $hit_edit_yn = $data['hit_edit_yn'];
        $file_cnt = $data['file_cnt'];
        $file_upload_size_limit = $data['file_upload_size_limit'];
        $file_upload_size_total = $data['file_upload_size_total'];
        $write_point = $data['write_point'];
        $comment_point = $data['comment_point'];
        $form_style_yn = $data['form_style_yn'];
        $form_style = $data['form_style'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('board_config');
        $builder->set('board_id', $board_id);
        $builder->set('title', $title);
        $builder->set('type', $type);
        $builder->set('base_rows', $base_rows);
        $builder->set('category_yn', $category_yn);
        $builder->set('category', $category);
        $builder->set('user_write', $user_write);
        $builder->set('comment_write', $comment_write);
        $builder->set('hit_yn', $hit_yn);
        $builder->set('heart_yn', $heart_yn);
        $builder->set('reg_date_yn', $reg_date_yn);
        $builder->set('hit_edit_yn', $hit_edit_yn);
        $builder->set('file_cnt', $file_cnt);
        $builder->set('file_upload_size_limit', $file_upload_size_limit);
        $builder->set('file_upload_size_total', $file_upload_size_total);
        $builder->set('write_point', $write_point);
        $builder->set('comment_point', $comment_point);
        $builder->set('form_style_yn', $form_style_yn);
        $builder->set('form_style', $form_style);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('board_config_idx', $board_config_idx);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '수정에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

    public function getBoardInfo($data)
    {
        $result = true;
        $message = '게시판 정보를 불러왔습니다.';

        $board_id = $data['board_id'];

        $db = $this->db;
        $builder = $db->table('board_config');
        $builder->where('del_yn', 'N');
        $builder->where('board_id', $board_id);
        $info = $builder->get()->getRow();

        if ($info == null) {
            $result = false;
            $message = '게시판 정보를 찾을 수 없습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procBoardDelete($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '게시판 설정이 삭제되었습니다.';

        $board_config_idx = $data['board_config_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('board_config');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('board_config_idx', $board_config_idx);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '삭제에 오류가 발생했습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }











    public function getSlideInfo($data)
    {
        $file_model = new FileModel();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $slide_idx = $data['slide_idx'];

        $db = $this->db;
        $builder = $db->table('slide');
        $builder->where('del_yn', 'N');
        $builder->where('slide_idx', $slide_idx);
        $info = $builder->get()->getRow();

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->start_date_txt = convertTextToDate($info->start_date, 1, 2);
        $info->end_date_txt = convertTextToDate($info->end_date, 1, 2);
        $info->contents = nl2br_only($info->contents);

        $info->slide_file_info = $file_model->getFileInfo($info->slide_file);
        $info->slide_file_info->file_size_kb = number_format($info->slide_file_info->file_size / 1024, 2);
        $info->slide_file_info->image_width_txt = number_format($info->slide_file_info->image_width);
        $info->slide_file_info->image_height_txt = number_format($info->slide_file_info->image_height);

        $badge_color = $info->display_yn === 'Y' ? 'bg-success' : 'bg-secondary';
        $info->display_yn_badge = $badge_color;

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procSlideInsert($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '슬라이드가 등록되었습니다.';
        $insert_id = 0;

        $title = $data['title'];
        $sub_title = $data['sub_title'];
        $contents = $data['contents'];
        $slide_file = $data['slide_file'];
        $order_no = $data['order_no'];
        $url_link = $data['url_link'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $display_yn = $data['display_yn'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('slide');
        $builder->set('title', $title);
        $builder->set('sub_title', $sub_title);
        $builder->set('contents', $contents);
        $builder->set('slide_file', $slide_file);
        $builder->set('order_no', $order_no);
        $builder->set('url_link', $url_link);
        $builder->set('start_date', $start_date);
        $builder->set('end_date', $end_date);
        $builder->set('display_yn', $display_yn);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

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

    public function procSlideUpdate($data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $slide_idx = $data['slide_idx'];
        $title = $data['title'];
        $sub_title = $data['sub_title'];
        $contents = $data['contents'];
        $slide_file = $data['slide_file'];
        $order_no = $data['order_no'];
        $url_link = $data['url_link'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $display_yn = $data['display_yn'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('slide');
        $builder->set('title', $title);
        $builder->set('sub_title', $sub_title);
        $builder->set('contents', $contents);
        $builder->set('slide_file', $slide_file);
        $builder->set('order_no', $order_no);
        $builder->set('url_link', $url_link);
        $builder->set('start_date', $start_date);
        $builder->set('end_date', $end_date);
        $builder->set('display_yn', $display_yn);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('slide_idx', $slide_idx);
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

    public function procSlideDelete($data)
    {
        $member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '삭제가 잘 되었습니다';

        $slide_idx = $data['slide_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('slide');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $builder->where('slide_idx', $slide_idx);
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

}
