<?php

namespace App\Models\User;

use CodeIgniter\Model;

class MainModel extends Model
{
    public function getSlideList()
    {
        $result = true;
        $message = '슬라이드 불러오기가 정상적으로 이루어졌습니다.';

        $today = date('YmdHis');

        $db = $this->db;
        $builder = $db->table('slide');
        $builder->where('del_yn', 'N');
        $builder->where('display_yn', 'Y');
        $builder->where('start_date <=', $today);
        $builder->where('end_date >=', $today);
        $list = $builder->get()->getResult();

        foreach ($list as $no => $val) {
            $val->active_class = $no == 0 ? 'active' : '';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    // 팝업 목록 갖고 오기
    public function getPopupList($is_mobile)
    {
        $result = true;
        $message = '팝업 불러오기가 정상적으로 이루어졌습니다.';

        $today = date('YmdHis');

        $db = $this->db;
        $builder = $db->table('popup');
        $builder->where('del_yn', 'N');
        $builder->where('display_yn', 'Y');
        $builder->where('start_date <=', $today);
        $builder->where('end_date >=', $today);
        $list = $builder->get()->getResult();

        $layer_closed = getUserSessionInfo("layer_closed");
        foreach ($layer_closed as $no1 => $val1) {
            foreach ($list as $no2 => $val2) {
                if ($val1->popup_idx == $val2->popup_idx && $val1->expire_date > date("YmdHis")) {
                    unset($list[$no2]);
                }
            }
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    // 최근 게시물 갖고 오기
    public function getRecentContentsList($data)
    {
        $result = true;
        $message = '최근 게시물 불러오기가 정상적으로 이루어졌습니다.';

        $board_id = $data['board_id'];
        $limit = $data['limit'];

        $db = $this->db;
        $builder = $db->table('board');
        $builder->where('del_yn', 'N');
        $builder->where('board_id', $board_id);
        $builder->orderBy('board_idx_desc', 'asc');
        $builder->limit($limit);
        $list = $builder->get()->getResult();

        foreach ($list as $no => $val) {
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 16);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }
}
