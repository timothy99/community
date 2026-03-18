<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\CommentModel;

class Comment extends BaseController
{
    public function index()
    {
        return redirect()->to('/');
    }

    public function insert()
    {
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상처리';

        $board_idx = $this->request->getPost('board_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment = $this->request->getPost('comment', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['board_idx'] = $board_idx;
        $data['comment'] = $comment;

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

    public function edit($board_comment_idx)
    {
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $model_result = $comment_model->getCommentInfo($board_comment_idx);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $language = service('request')->getCookie('language') ?? 'kor';
        $comment_edit_html = view('/user/'.$language.'/comment/edit', $model_result);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['board_comment_idx'] = $board_comment_idx;
        $proc_result['return_html'] = $comment_edit_html;

        return $this->response->setJSON($proc_result);
    }

    public function update()
    {
        $comment_model = new CommentModel();

        $result = true;
        $message = '정상처리';

        $board_comment_idx = $this->request->getPost('board_comment_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment = $this->request->getPost('comment', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['board_comment_idx'] = $board_comment_idx;
        $data['comment'] = $comment;

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
