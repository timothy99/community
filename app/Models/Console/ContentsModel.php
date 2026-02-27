<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\Common\DownloadModel;

class ContentsModel extends Model
{
    public function getContentsList($data)
    {
        $result = true;
        $message = "목록 불러오기가 완료되었습니다.";

        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];

        $db = $this->db;
        $builder = $db->table("contents");
        $builder->where("del_yn", "N");
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        $builder->orderBy("contents_idx", "desc");
        $builder->limit($rows, getOffset($page, $rows));
        $cnt = $builder->countAllResults(false);
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $list[$no]->list_no = $cnt-$no-(($page-1)*$rows);
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 1);
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["list"] = $list;
        $proc_result["cnt"] = $cnt;

        return $proc_result;
    }

    public function getContentsInfo($contents_idx)
    {
        $result = true;
        $message = "목록 불러오기가 완료되었습니다.";

        $db = $this->db;
        $builder = $db->table("contents");
        $builder->where("del_yn", "N");
        $builder->where("contents_idx", $contents_idx);
        $info = $builder->get()->getRow();

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->contents = nl2br_only($info->contents);

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["info"] = $info;

        return $proc_result;
    }

    public function procContentsInsert($data)
    {
        $user_id = getUserSessionInfo("member_id");
        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";
        $insert_id = 0;

        $title = $data["title"];
        $contents = $data["contents"];
        $meta_title = $data["meta_title"];
        $contents_id = $data["contents_id"];

        $db = $this->db;
        $db->transStart();
        $builder = $db->table("contents");
        $builder->set("title", $title);
        $builder->set("contents", $contents);
        $builder->set("meta_title", $meta_title);
        $builder->set("contents_id", $contents_id);
        $builder->set("del_yn", "N");
        $builder->set("ins_id", $user_id);
        $builder->set("ins_date", $today);
        $builder->set("upd_id", $user_id);
        $builder->set("upd_date", $today);
        $result = $builder->insert();

        if ($db->transStatus() === false) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            $db->transRollback();
        } else {
            $db->transComplete();
            $insert_id = $db->insertID();
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;
        $model_result["insert_id"] = $insert_id;

        return $model_result;
    }

    public function procContentsUpdate($data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo("member_id");
        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";

        $contents_idx = $data["contents_idx"];
        $title = $data["title"];
        $contents = $data["contents"];
        $meta_title = $data["meta_title"];
        $contents_id = $data["contents_id"];

        $db = $this->db;
        $db->transStart();
        $builder = $db->table("contents");
        $builder->set("title", $title);
        $builder->set("contents", $contents);
        $builder->set("meta_title", $meta_title);
        $builder->set("contents_id", $contents_id);
        $builder->set("upd_id", $user_id);
        $builder->set("upd_date", $today);
        $builder->where("contents_idx", $contents_idx);
        $result = $builder->update();

        if ($db->transStatus() === false) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            $db->transRollback();
        } else {
            $db->transComplete();
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;

        return $model_result;
    }

    public function procContentsDelete($data)
    {
        $member_id = getUserSessionInfo("member_id");
        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";

        $contents_idx = $data["contents_idx"];

        $db = $this->db;
        $db->transStart();
        $builder = $db->table("contents");
        $builder->set("del_yn", "Y");
        $builder->set("upd_id", $member_id);
        $builder->set("upd_date", $today);
        $builder->where("contents_idx", $contents_idx);
        $result = $builder->update();

        if ($db->transStatus() === false) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
            $db->transRollback();
        } else {
            $db->transComplete();
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;

        return $model_result;
    }

}
