<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\Console\FileModel;

class SlideModel extends Model
{
    public function getSlideList($data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];

        $db = $this->db;
        $builder = $db->table('slide');
        $builder->where('del_yn', 'N');
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        $builder->orderBy('slide_idx', 'desc');
        $builder->limit($rows, getOffset($page, $rows));
        $cnt = $builder->countAllResults(false);
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $list[$no]->list_no = $cnt-$no-(($page-1)*$rows);
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 1);
            $list[$no]->start_date_txt = convertTextToDate($val->start_date, 1, 2);
            $list[$no]->end_date_txt = convertTextToDate($val->end_date, 1, 2);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;

        return $proc_result;
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
