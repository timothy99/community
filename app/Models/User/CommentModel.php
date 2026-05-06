<?php

namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\User\MemberModel;
use PHPUnit\Event\TestSuite\Loaded;

class CommentModel extends Model
{
    public function getCommentList($data)
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
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    public function procCommentInsert($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $board_idx = $data['board_idx'];
        $comment = $data['comment'];
        $secret_yn = $data['secret_yn'];

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
    public function procCommentDelete($data)
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

    public function getCommentInfo($board_comment_idx)
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

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    public function procCommentUpdate($data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $board_comment_idx = $data['board_comment_idx'];
        $comment = $data['comment'];
        $secret_yn = $data['secret_yn'];

        $db = $this->db;
        $builder = $db->table('board_comment');
        $builder->set('comment', $comment);
        $builder->set('secret_yn', $secret_yn);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('board_comment_idx', $board_comment_idx);
        $result = $builder->update();

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
