<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\User\FileModel;

class IpModel extends Model
{
    public function getIpList(array $data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];

        $db = $this->db;
        $builder = $db->table('ip');
        $builder->where('del_yn', 'N');
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        $builder->orderBy('ip_idx', 'desc');
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

    public function getIpInfo(array $data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $ip_idx = $data['ip_idx'];

        $db = $this->db;
        $builder = $db->table('ip');
        $builder->where('del_yn', 'N');
        $builder->where('ip_idx', $ip_idx);
        $info = $builder->get()->getRow();

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procIpInsert(array $data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = 'IP가 등록되었습니다.';
        $insert_id = 0;

        $ip = $data['ip'];
        $environment_mode = $data['environment_mode'];
        $memo = $data['memo'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('ip');
        $builder->set('ip', $ip);
        $builder->set('environment_mode', $environment_mode);
        $builder->set('memo', $memo);
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

    public function procIpUpdate(array $data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $ip_idx = $data['ip_idx'];
        $ip = $data['ip'];
        $environment_mode = $data['environment_mode'];
        $memo = $data['memo'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('ip');
        $builder->set('ip', $ip);
        $builder->set('environment_mode', $environment_mode);
        $builder->set('memo', $memo);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('ip_idx', $ip_idx);
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

    public function procIpDelete(array $data)
    {
        $member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '삭제가 잘 되었습니다';

        $ip_idx = $data['ip_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('ip');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $builder->where('ip_idx', $ip_idx);
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
