<?php


namespace App\Models\User;

use CodeIgniter\Model;

class BoardAuthorityModel extends Model
{
    // 게시판에 대한 통합권한관리 정보 획득
    public function getAuthorityInfo($data)
    {
        $auth_group = getUserSessionInfo("auth_group");
        $member_id = getUserSessionInfo("member_id");
        $board_id = $data["board_id"];
        $board_idx = isset($data["board_idx"]) ? $data["board_idx"] : 0;

        $input_data = array();
        $input_data["board_id"] = $board_id;
        $input_data["board_idx"] = $board_idx;
        $input_data["auth_group"] = $auth_group;
        $input_data["member_id"] = $member_id;

        $admin_authority = $this->getAdminAuthorityInfo($input_data); // 게시판 관리자 권한

        // 관리자이면 다른 권한 모두 부여
        if ($admin_authority == "Y") {
            $list_authority = "Y"; // 게시물 목록 접근 권한
            $view_authority = "Y"; // 게시물 보기 권한
            $write_authority = "Y"; // 게시물 쓰기 권한
            $edit_authority = "Y"; // 게시물 수정 권한
            $delete_authority = "Y"; // 게시물 삭제 권한
        } else {
            $list_authority = $this->getListAuthorityInfo($input_data); // 게시물 목록 접근 권한
            $view_authority = $this->getViewAuthorityInfo($input_data); // 게시물 보기 권한
            $write_authority = $this->getWriteAuthorityInfo($input_data); // 게시물 쓰기 권한
            $edit_authority = $this->getEditAuthorityInfo($input_data); // 게시물 수정 권한
            $delete_authority = $this->getDeleteAuthorityInfo($input_data); // 게시물 삭제 권한
        }

        $authority_info = (object)array();
        $authority_info->admin_authority = $admin_authority; // 게시판 관리자 권한
        $authority_info->list_authority = $list_authority; // 게시물 목록 접근 권한
        $authority_info->view_authority = $view_authority; // 게시물 보기 권한
        $authority_info->write_authority = $write_authority; // 게시물 쓰기 권한
        $authority_info->edit_authority = $edit_authority; // 게시물 수정 권한
        $authority_info->delete_authority = $delete_authority; // 게시물 삭제 권한

        return $authority_info;
    }
    // 게시판 관리자인지 확인하기
    public function getAdminAuthorityInfo($data)
    {
        $admin_authority = "N";

        $board_id = $data["board_id"];
        $member_id = getUserSessionInfo("member_id");

        $db = $this->db;;
        $builder = $db->table("member");
        $builder->where("member_id", $member_id);
        $builder->where("del_yn", "N");
        $builder->whereIn("auth_group", ["관리자", "최고관리자"]);
        $member_info = $builder->get()->getRow();

        // 게시판 관리자인 경우 권한 부여
        $builder = $db->table("board_admin");
        $builder->where("board_id", $board_id);
        $builder->where("del_yn", "N");
        $builder->where("member_id", $member_id);
        $admin_info = $builder->get()->getRow();

        if ($admin_info || $member_info) {
            $admin_authority = "Y";
        }

        return $admin_authority;
    }

    // 게시물 목록 접근 정보
    public function getListAuthorityInfo($data)
    {
        $list_authority = "N";

        $board_id = $data["board_id"];
        $auth_group = getUserSessionInfo("auth_group");

        $db = $this->db;
        $builder = $db->table("board_authority");
        $builder->where("board_id", $board_id);
        $builder->where("authority_role", "list");
        $builder->where("del_yn", "N");
        $builder->whereIn("auth_group", ["전체", "로그인", $auth_group]);
        $authority_list = $builder->get()->getResult();

        foreach ($authority_list as $no => $val) {
            // 전체 공개
            if ($val->auth_group == "전체") {
                $list_authority = "Y";
                break;
            }

            // 로그인 해야함
            if ($val->auth_group == "로그인" && $auth_group != "guest") {
                $list_authority = "Y";
                break;
            }

            // 특정 권한그룹
            if ($val->auth_group == $auth_group) {
                $list_authority = "Y";
                break;
            }
        }

        return $list_authority;
    }

    // 게시물 보기 권한 정보
    public function getViewAuthorityInfo($data)
    {
        $view_authority = "N";

        $board_id = $data["board_id"];
        $auth_group = getUserSessionInfo("auth_group");

        $db = $this->db;
        $builder = $db->table("board_authority");
        $builder->where("board_id", $board_id);
        $builder->where("authority_role", "view");
        $builder->where("del_yn", "N");
        $builder->whereIn("auth_group", ["전체", "로그인", $auth_group]);
        $authority_list = $builder->get()->getResult();

        foreach ($authority_list as $no => $val) {
            // 전체 공개
            if ($val->auth_group == "전체") {
                $view_authority = "Y";
                break;
            }

            // 로그인 해야함
            if ($val->auth_group == "로그인" && $auth_group != "guest") {
                $view_authority = "Y";
                break;
            }

            // 특정 권한그룹
            if ($val->auth_group == $auth_group) {
                $view_authority = "Y";
                break;
            }
        }

        return $view_authority;
    }

    // 게시물 쓰기 권한 정보 갖고 오기
    public function getWriteAuthorityInfo($data)
    {
        $write_authority = "N";

        $board_id = $data["board_id"];
        $auth_group = getUserSessionInfo("auth_group");

        $db = $this->db;
        $builder = $db->table("board_authority");
        $builder->where("board_id", $board_id);
        $builder->where("authority_role", "write");
        $builder->where("del_yn", "N");
        $builder->whereIn("auth_group", ["전체", "로그인", $auth_group]);
        $authority_list = $builder->get()->getResult();

        foreach ($authority_list as $no => $val) {
            // 로그인 해야함
            if ($val->auth_group == "로그인" && $auth_group != "guest") {
                $write_authority = "Y";
                break;
            }

            // 특정 권한그룹
            if ($val->auth_group == $auth_group) {
                $write_authority = "Y";
                break;
            }
        }

        return $write_authority;
    }

    // 게시물 수정 권한 정보 갖고 오기
    public function getEditAuthorityInfo($data)
    {
        $edit_authority = "N";

        $board_id = $data["board_id"];
        $board_idx = $data["board_idx"];
        $member_id = $data["member_id"];

        $db = $this->db;
        $builder = $db->table("board");
        $builder->where("board_id", $board_id);
        $builder->where("board_idx", $board_idx);
        $builder->where("ins_id", $member_id);
        $builder->where("del_yn", "N");
        $info = $builder->get()->getFirstRow();

        if ($info && $info->ins_id == $member_id) {
            $edit_authority = "Y";
        }

        return $edit_authority;
    }

    // 게시물 삭제 권한 정보 갖고 오기
    public function getDeleteAuthorityInfo($data)
    {
        $delete_authority = "N";

        $board_id = $data["board_id"];
        $board_idx = $data["board_idx"];
        $member_id = $data["member_id"];

        $db = $this->db;
        $builder = $db->table("board");
        $builder->where("board_id", $board_id);
        $builder->where("board_idx", $board_idx);
        $builder->where("ins_id", $member_id);
        $builder->where("del_yn", "N");
        $info = $builder->get()->getFirstRow();

        if ($info && $info->ins_id == $member_id) {
            $delete_authority = "Y";
        }

        return $delete_authority;
    }

}
