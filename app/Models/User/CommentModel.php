<?php

namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\User\MemberModel;
use App\Models\User\FileModel;

class CommentModel extends Model
{
    private function getCommentFileList(int $board_comment_idx): array
    {
        $file_model = new FileModel();

        $db = $this->db;
        $builder = $db->table('board_comment_file');
        $builder->where('board_comment_idx', $board_comment_idx);
        $builder->orderBy('board_comment_file_idx', 'asc');
        $file_list = $builder->get()->getResult();

        foreach ($file_list as $key => $val) {
            $file_list[$key]->file_info = $file_model->getFileInfo($val->file_id);
            if ($file_list[$key]->file_info != null) {
                $file_list[$key]->file_info->file_size_kb = number_format($file_list[$key]->file_info->file_size / 1024, 2);
            }
        }

        return $file_list;
    }

    public function getCommentList(array $data)
    {
        $member_model = new MemberModel();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $board_idx = $data['board_idx'];

        $db = $this->db;
        $builder = $db->table('board_comment');
        $builder->where('del_yn', 'N');
        $builder->where('board_idx', $board_idx);
        $builder->orderBy('board_comment_idx', 'desc');
        $list = $builder->get()->getResult();

        foreach($list as $no => $val) {
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 16);
            $list[$no]->comment = str_replace('&#13;&#10;', '<br>', $val->comment);
            $data['member_id'] = $val->ins_id;
            $list[$no]->member_info = $member_model->getMemberInfo($data)['info'];
            $list[$no]->file_list = $this->getCommentFileList((int)$val->board_comment_idx);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    public function procCommentInsert(array $data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $board_idx = $data['board_idx'];
        $comment = $data['comment'];
        $secret_yn = $data['secret_yn'];
        $file_arr = $data['file_arr'] ?? array();

        $db = $this->db;
        $db->transStart();
        $builder = $db->table('board_comment');
        $builder->set('board_idx', $board_idx);
        $builder->set('comment', $comment);
        $builder->set('secret_yn', $secret_yn);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        if ($result) {
            foreach ($file_arr as $file_id) {
                $builder = $db->table('board_comment_file');
                $builder->set('board_comment_idx', $insert_id);
                $builder->set('file_id', $file_id);
                if (!$builder->insert()) {
                    $result = false;
                    $message = '댓글 파일 등록에 오류가 발생했습니다.';
                    break;
                }
            }
        }

        // board_comment 코멘트 숫자 구하기
        $builder = $db->table('board_comment');
        $builder->select('count(*) as cnt');
        $builder->where('board_idx', $board_idx);
        $builder->where('del_yn', 'N');
        $comment_cnt_info = $builder->get()->getRow();
        $comment_cnt = $comment_cnt_info->cnt;

        // board 테이블의 comment_count 업데이트
        $builder = $db->table('board');
        $builder->set('comment_cnt', $comment_cnt);
        $builder->where('board_idx', $board_idx);
        $result = $builder->update();

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

    // 댓글 삭제
    public function procCommentDelete(array $data)
    {
        // 게시판 입력과 관련된 기본 정보
        $member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $board_comment_idx = $data['board_comment_idx'];

        $db = $this->db;
        $db->transStart();
        $builder = $db->table('board_comment');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $builder->where('board_comment_idx', $board_comment_idx);
        $result = $builder->update();

        // 댓글 파일 삭제
        if ($result) {
            $builder = $db->table('board_comment_file');
            $builder->where('board_comment_idx', $board_comment_idx);
            $builder->delete();
        }

        // 삭제된 댓글의 board_idx 조회
        $builder = $db->table('board_comment');
        $builder->select('board_idx');
        $builder->where('board_comment_idx', $board_comment_idx);
        $comment_info = $builder->get()->getRow();
        $board_idx = $comment_info->board_idx;

        // board_comment 코멘트 숫자 구하기
        $builder = $db->table('board_comment');
        $builder->select('count(*) as cnt');
        $builder->where('board_idx', $board_idx);
        $builder->where('del_yn', 'N');
        $comment_cnt_info = $builder->get()->getRow();
        $comment_cnt = $comment_cnt_info->cnt;

        // board 테이블의 comment_count 업데이트
        $builder = $db->table('board');
        $builder->set('comment_cnt', $comment_cnt);
        $builder->where('board_idx', $board_idx);
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

    public function getCommentInfo(int $board_comment_idx)
    {
        $member_model = new MemberModel();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $db = $this->db;
        $builder = $db->table('board_comment');
        $builder->where('del_yn', 'N');
        $builder->where('board_comment_idx', $board_comment_idx);
        $info = $builder->get()->getRow();

        $data = array();
        $data['member_id'] = $info->ins_id;

        $info->member_info = $member_model->getMemberInfo($data)['info'];
        $info->file_list = $this->getCommentFileList((int)$board_comment_idx);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procCommentUpdate(array $data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $board_comment_idx = $data['board_comment_idx'];
        $comment = $data['comment'];
        $secret_yn = $data['secret_yn'];
        $file_arr = $data['file_arr'] ?? array();

        $db = $this->db;
        $db->transStart();
        $builder = $db->table('board_comment');
        $builder->set('comment', $comment);
        $builder->set('secret_yn', $secret_yn);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('board_comment_idx', $board_comment_idx);
        $result = $builder->update();

        // board_comment_file 삭제
        if ($result) {
            $builder = $db->table('board_comment_file');
            $builder->where('board_comment_idx', $board_comment_idx);
            $builder->delete();
        }

        // board_comment_file 삽입
        if ($result) {
            foreach ($file_arr as $file_id) {
                $builder = $db->table('board_comment_file');
                $builder->set('board_comment_idx', $board_comment_idx);
                $builder->set('file_id', $file_id);
                if (!$builder->insert()) {
                    $result = false;
                    $message = '댓글 파일 수정에 오류가 발생했습니다.';
                    break;
                }
            }
        }

        // 삭제된 댓글의 board_idx 조회
        $builder = $db->table('board_comment');
        $builder->select('board_idx');
        $builder->where('board_comment_idx', $board_comment_idx);
        $builder->where('del_yn', 'N');
        $comment_info = $builder->get()->getRow();
        $board_idx = $comment_info->board_idx;

        // board_comment 코멘트 숫자 구하기
        $builder = $db->table('board_comment');
        $builder->select('count(*) as cnt');
        $builder->where('board_idx', $board_idx);
        $builder->where('del_yn', 'N');
        $comment_cnt_info = $builder->get()->getRow();
        $comment_cnt = $comment_cnt_info->cnt;

        // board 테이블의 comment_count 업데이트
        $builder = $db->table('board');
        $builder->set('comment_cnt', $comment_cnt);
        $builder->where('board_idx', $board_idx);
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
