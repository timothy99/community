<?php

namespace App\Models\Console;

use CodeIgniter\Model;

class MemoModel extends Model
{
    public function getMemoList($data)
    {
        $result = true;
        $message = "목록 불러오기가 완료되었습니다.";

        $member_id = $data["member_id"];

        $db = $this->db;
        $builder = $db->table("member_memo");
        $builder->where("del_yn", "N");
        $builder->where("member_id", $member_id);
        $builder->orderBy("member_memo_idx", "asc");
        $cnt = $builder->countAllResults(false);
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $list[$no]->list_no = $no+1;
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 1);
            $list[$no]->upd_date_txt = convertTextToDate($val->upd_date, 1, 1);
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["list"] = $list;
        $proc_result["cnt"] = $cnt;

        return $proc_result;
    }

    public function getMemoInfo($member_memo_idx)
    {
        $result = true;
        $message = "목록 불러오기가 완료되었습니다.";

        $db = $this->db;
        $builder = $db->table("member_memo");
        $builder->where("del_yn", "N");
        $builder->where("member_memo_idx", $member_memo_idx);
        $info = $builder->get()->getRow();

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->upd_date_txt = convertTextToDate($info->upd_date, 1, 1);

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["info"] = $info;

        return $proc_result;
    }

    public function procMemoInsert($data)
    {
        $user_id = getUserSessionInfo("member_id");
        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";
        $insert_id = 0;

        $member_id = $data["member_id"];
        $memo = $data["memo"];

        $db = $this->db;
        $db->transStart();
        $builder = $db->table("member_memo");
        $builder->set("member_id", $member_id);
        $builder->set("memo", $memo);
        $builder->set("del_yn", "N");
        $builder->set("ins_id", $user_id);
        $builder->set("ins_date", $today);
        $builder->set("upd_id", $user_id);
        $builder->set("upd_date", $today);
        $result = $builder->insert();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
        } else {
            $insert_id = $db->insertID();
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;
        $model_result["insert_id"] = $insert_id;

        return $model_result;
    }

    public function procMemoUpdate($data)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo("member_id");
        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";

        $member_memo_idx = $data["member_memo_idx"];
        $member_id = $data["member_id"];
        $memo = $data["memo"];

        $db = $this->db;
        $db->transStart();
        $builder = $db->table("member_memo");
        $builder->set("member_id", $member_id);
        $builder->set("memo", $memo);
        $builder->set("upd_id", $user_id);
        $builder->set("upd_date", $today);
        $builder->where("member_memo_idx", $member_memo_idx);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;

        return $model_result;
    }

    public function procMemoDelete($data)
    {
        $member_id = getUserSessionInfo("member_id");
        $today = date("YmdHis");

        $result = true;
        $message = "입력이 잘 되었습니다";

        $member_memo_idx = $data["member_memo_idx"];

        $db = $this->db;
        $db->transStart();
        $builder = $db->table("member_memo");
        $builder->set("del_yn", "Y");
        $builder->set("upd_id", $member_id);
        $builder->set("upd_date", $today);
        $builder->where("member_memo_idx", $member_memo_idx);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = "입력에 오류가 발생했습니다.";
        }

        $model_result = array();
        $model_result["result"] = $result;
        $model_result["message"] = $message;

        return $model_result;
    }

}
