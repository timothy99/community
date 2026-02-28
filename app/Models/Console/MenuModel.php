<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\Console\FileModel;

class MenuModel extends Model
{
    public function getMenuList()
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $db = $this->db;
        $builder = $db->table('menu');
        $builder->where('upper_idx', 0);
        $builder->where('del_yn', 'N');
        $builder->orderBy('order_no', 'asc');
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $menu_idx = $val->menu_idx;
            $builder->where('del_yn', 'N');
            $builder->where('upper_idx', $menu_idx);
            $builder->orderBy('order_no', 'asc');
            $list2 = $builder->get()->getResult();
            $list[$no]->list = $list2;
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    public function getMenuInfo($data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $menu_idx = $data['menu_idx'];

        $db = $this->db;
        $builder = $db->table('menu');
        $builder->where('del_yn', 'N');
        $builder->where('menu_idx', $menu_idx);
        $info = $builder->get()->getRow();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procMenuInsert($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '메뉴가 등록되었습니다.';
        $insert_id = 0;

        $upper_idx = $data["upper_idx"];
        $idx1 = $data["idx1"];
        $idx2 = $data["idx2"];
        $menu_position = $data["menu_position"];
        $menu_name = $data["menu_name"];
        $url_link = $data["url_link"];
        $order_no = $data["order_no"];

        $menu_position = 1;
        $new_menu_position = 1;

        $db = $this->db;
        $db->transStart();

        $input_data = array();
        $input_data['menu_idx'] = $upper_idx;
        $model_result = $this->getMenuInfo($input_data);
        $info = $model_result['info'];
        if ($info != null) {
            $idx1 = $info->idx1;
            $idx2 = $info->idx2;
            $menu_position = $info->menu_position;
            $new_menu_position = $menu_position + 1;
        }


        $builder = $db->table('menu');
        $builder->set('upper_idx', $upper_idx);
        $builder->set('idx1', $idx1);
        $builder->set('idx2', $idx2);
        $builder->set('menu_position', $new_menu_position);
        $builder->set('menu_name', $menu_name);
        $builder->set('url_link', $url_link);
        $builder->set('order_no', $order_no);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        $builder = $db->table('menu');
        $builder->set('idx'.$new_menu_position, $insert_id);
        $builder->where('menu_idx', $insert_id);
        $builder->update();

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

    public function procMenuUpdate($data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '메뉴가 수정되었습니다.';

        $menu_idx = $data['menu_idx'];
        $menu_name = $data['menu_name'];
        $order_no = $data['order_no'];
        $url_link = $data['url_link'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('menu');
        $builder->set('menu_name', $menu_name);
        $builder->set('order_no', $order_no);
        $builder->set('url_link', $url_link);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('menu_idx', $menu_idx);
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

    public function procMenuDelete($data)
    {
        $member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '메뉴가 삭제되었습니다.';

        $menu_idx = $data['menu_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('menu');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $builder->where('menu_idx', $menu_idx);
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
