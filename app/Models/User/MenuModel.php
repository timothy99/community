<?php

namespace App\Models\User;

use CodeIgniter\Model;

class MenuModel extends Model
{
    public function getMenuList()
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        // upper_idx 기준으로 상위 데이터를 찾아본다.
        $db = $this->db;
        $builder = $db->table('menu');
        $builder->where('del_yn', 'N');
        $builder->where('upper_idx', 0);
        $builder->orderBy('order_no', 'asc');
        $list = $builder->get()->getResult();

        foreach ($list as $no => $val) {
            $menu_idx = $val->menu_idx;

            $builder = $db->table('menu');
            $builder->where('del_yn', 'N');
            $builder->where('upper_idx', $menu_idx);
            $builder->orderBy('order_no', 'asc');
            $list2 = $builder->get()->getResult();

            $list[$no]->sub_list = $list2;
            $has_sub = count($list2);
            $list[$no]->has_sub = $has_sub > 0 ? 'has_sub' : '';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

}
