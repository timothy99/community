<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\CommentModel;

class Comment extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl');
    }

    public function insert()
    {
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상처리';

        $board_idx = $this->request->getPost('board_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment = sanitizeHtml($this->request->getPost('comment'));
        $file_idxs = $this->request->getPost('file_idxs', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($file_idxs == '' || $file_idxs == null) {
            $file_arr = array();
        } else {
            $file_arr = array_values(array_filter(explode('||', $file_idxs)));
        }

        $data = array();
        $data['board_idx'] = $board_idx;
        $data['comment'] = $comment;
        $data['file_arr'] = $file_arr;

        $model_result = $comment_model->procCommentInsert($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['board_idx'] = $board_idx;
        $proc_result['return_url'] = getUserSessionInfo('previous_url');

        return $this->response->setJSON($proc_result);
    }

    public function delete()
    {
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $board_comment_idx = $this->request->getPost('board_comment_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['board_comment_idx'] = $board_comment_idx;
        $model_result = $comment_model->procCommentDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = getUserSessionInfo('previous_url');

        return $this->response->setJSON($proc_result);
    }

    public function edit()
    {
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $board_comment_idx = $this->request->getUri()->getSegment(4);

        $model_result = $comment_model->getCommentInfo($board_comment_idx);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $comment_edit_html = view('/console/comment/edit', $model_result);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['board_comment_idx'] = $board_comment_idx;
        $proc_result['comment'] = $model_result['info']->comment ?? '';
        $proc_result['file_list'] = $model_result['info']->file_list ?? array();
        $proc_result['return_html'] = $comment_edit_html;

        return $this->response->setJSON($proc_result);
    }

    public function update()
    {
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상처리';

        $board_comment_idx = $this->request->getPost('board_comment_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment = sanitizeHtml($this->request->getPost('comment'));
        $file_idxs = $this->request->getPost('file_idxs', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($file_idxs == '' || $file_idxs == null) {
            $file_arr = array();
        } else {
            $file_arr = array_values(array_filter(explode('||', $file_idxs)));
        }

        $data = array();
        $data['board_comment_idx'] = $board_comment_idx;
        $data['comment'] = $comment;
        $data['file_arr'] = $file_arr;

        $model_result = $comment_model->procCommentUpdate($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['board_comment_idx'] = $board_comment_idx;
        $proc_result['return_url'] = getUserSessionInfo('previous_url');

        return $this->response->setJSON($proc_result);
    }

}
