<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\ContentsModel;
use App\Models\Common\PagingModel;

class Contents extends BaseController
{
    public function index()
    {
        return redirect()->to('/console/contents/list');
    }

    public function list()
    {
        $contents_model = new ContentsModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'title';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $contents_model->getContentsList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        $search_arr = array();
        $search_arr['search_condition'] = $search_condition;
        $search_arr['search_text'] = $search_text;
        $search_arr['search_page'] = $search_page;
        $search_arr['search_rows'] = $search_rows;
        $search_arr['cnt'] = $cnt;
        $paging_info = getPagingInfo($search_arr);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;
        $proc_result['paging_info'] = $paging_info;
        $proc_result['data'] = $data;

        return aview('console/contents/list', $proc_result);
    }

    public function write()
    {
        $result = true;
        $message = '정상';

        $info = (object)array();
        $info->contents_idx = 0;
        $info->contents_id = '';
        $info->title = '';
        $info->meta_title = '';
        $info->contents = '';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/contents/edit', $proc_result);
    }

    public function update()
    {
        $contents_model = new ContentsModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $contents_idx = $this->request->getPost('contents_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $contents = (string)$this->request->getPost('contents');
        $meta_title = $this->request->getPost('meta_title');
        $contents_id = $this->request->getPost('contents_id', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($title == null) {
            $result = false;
            $message = '제목을 입력해주세요.';
        }

        $data = array();
        $data['contents_idx'] = $contents_idx;
        $data['title'] = $title;
        $data['contents'] = $contents;
        $data['meta_title'] = $meta_title;
        $data['contents_id'] = $contents_id;

        if ($result == true) {
            if ($contents_idx == 0) {
                $model_result = $contents_model->procContentsInsert($data);
            } else {
                $model_result = $contents_model->procContentsUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/contents/view/'.$contents_idx;

        return json_encode($proc_result);
    }

    public function view()
    {
        $contents_model = new ContentsModel();

        $contents_idx = $this->request->getUri()->getSegment(4);

        $result = true;
        $message = '정상';

        $model_result = $contents_model->getContentsInfo($contents_idx);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/contents/view', $proc_result);
    }

    public function edit()
    {
        $contents_model = new ContentsModel();

        $contents_idx = $this->request->getUri()->getSegment(4);

        $result = true;
        $message = '정상';

        $model_result = $contents_model->getContentsInfo($contents_idx);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/contents/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = '정상처리 되었습니다.';

        $contents_model = new ContentsModel();

        $contents_idx = $this->request->getPost('contents_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['contents_idx'] = $contents_idx;

        $model_result = $contents_model->procContentsDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/console/contents/list';

        return json_encode($proc_result);
    }

}
