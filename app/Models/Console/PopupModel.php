<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\Console\FileModel;

class PopupModel extends Model
{
    public function getPopupList($data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];

        $db = $this->db;
        $builder = $db->table('mng_popup');
        $builder->where('del_yn', 'N');
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        $builder->orderBy('popup_idx', 'desc');
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

    public function getPopupInfo($data)
    {
        $file_model = new FileModel();

        $result = true;
        $message = '정보 불러오기가 완료되었습니다.';

        $popup_idx = $data['popup_idx'];

        $db = $this->db;
        $builder = $db->table('mng_popup');
        $builder->where('del_yn', 'N');
        $builder->where('popup_idx', $popup_idx);
        $info = $builder->get()->getRow();

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->start_date_txt = convertTextToDate($info->start_date, 1, 2);
        $info->end_date_txt = convertTextToDate($info->end_date, 1, 2);

        if ($info->popup_file != null && $info->popup_file != '') {
            $info->popup_file_info = $file_model->getFileInfo($info->popup_file);
            $info->popup_file_info->file_size_kb = number_format($info->popup_file_info->file_size / 1024, 2);
            $info->popup_file_info->image_width_txt = number_format($info->popup_file_info->image_width);
            $info->popup_file_info->image_height_txt = number_format($info->popup_file_info->image_height);
        } else {
            $info->popup_file_info = null;
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procPopupInsert($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '팝업이 등록되었습니다.';
        $insert_id = 0;

        $title = $data['title'];
        $popup_file = $data['popup_file'];
        $url_link = $data['url_link'];
        $position_left = $data['position_left'];
        $position_top = $data['position_top'];
        $popup_width = $data['popup_width'];
        $popup_height = $data['popup_height'];
        $disabled_hours = $data['disabled_hours'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $display_yn = $data['display_yn'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('mng_popup');
        $builder->set('title', $title);
        $builder->set('popup_file', $popup_file);
        $builder->set('url_link', $url_link);
        $builder->set('position_left', $position_left);
        $builder->set('position_top', $position_top);
        $builder->set('popup_width', $popup_width);
        $builder->set('popup_height', $popup_height);
        $builder->set('disabled_hours', $disabled_hours);
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

    public function procPopupUpdate($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '수정이 완료되었습니다.';

        $popup_idx = $data['popup_idx'];
        $title = $data['title'];
        $popup_file = $data['popup_file'];
        $url_link = $data['url_link'];
        $position_left = $data['position_left'];
        $position_top = $data['position_top'];
        $popup_width = $data['popup_width'];
        $popup_height = $data['popup_height'];
        $disabled_hours = $data['disabled_hours'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $display_yn = $data['display_yn'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('mng_popup');
        $builder->set('title', $title);
        $builder->set('popup_file', $popup_file);
        $builder->set('url_link', $url_link);
        $builder->set('position_left', $position_left);
        $builder->set('position_top', $position_top);
        $builder->set('popup_width', $popup_width);
        $builder->set('popup_height', $popup_height);
        $builder->set('disabled_hours', $disabled_hours);
        $builder->set('start_date', $start_date);
        $builder->set('end_date', $end_date);
        $builder->set('display_yn', $display_yn);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('popup_idx', $popup_idx);
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

    public function procPopupDelete($data)
    {
        $member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '삭제가 완료되었습니다.';

        $popup_idx = $data['popup_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('mng_popup');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $builder->where('popup_idx', $popup_idx);
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

}
