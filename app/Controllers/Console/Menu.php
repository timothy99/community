<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\MenuModel;

class Menu extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/menu/list');
    }

    public function list()
    {
        $menu_model = new MenuModel();

        $model_result = $menu_model->getMenuList();
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return aview('/console/menu/list', $proc_result);
    }

    public function write($upper_idx)
    {
        $result = true;
        $message = '정상';

        $info = (object)array();
        $info->menu_idx = 0;
        $info->upper_idx = $upper_idx;
        $info->idx1 = 0;
        $info->idx2 = 0;
        $info->menu_position = 0;
        $info->menu_name = '';
        $info->url_link = '';
        $info->order_no = '';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/menu/edit', $proc_result);
    }

    public function update()
    {
        $menu_model = new MenuModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $menu_idx = $this->request->getPost("menu_idx", FILTER_SANITIZE_SPECIAL_CHARS);
        $upper_idx = $this->request->getPost("upper_idx", FILTER_SANITIZE_SPECIAL_CHARS);
        $idx1 = $this->request->getPost("idx1", FILTER_SANITIZE_SPECIAL_CHARS);
        $idx2 = $this->request->getPost("idx2", FILTER_SANITIZE_SPECIAL_CHARS);
        $menu_position = $this->request->getPost("menu_position", FILTER_SANITIZE_SPECIAL_CHARS);
        $menu_name = $this->request->getPost("menu_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $url_link = $this->request->getPost("url_link", FILTER_SANITIZE_SPECIAL_CHARS);
        $order_no = $this->request->getPost("order_no", FILTER_SANITIZE_SPECIAL_CHARS);

        if ($menu_name == null) { $result = false; $message = '메뉴명을 입력해주세요.'; }
        if ($order_no == null) { $result = false; $message = '정렬순서를 입력해주세요.'; }
        if ($url_link == null) { $result = false; $message = '링크를 입력해주세요.';}

        $data = array();
        $data['menu_idx'] = $menu_idx;
        $data['upper_idx'] = $upper_idx;
        $data['idx1'] = $idx1;
        $data['idx2'] = $idx2;
        $data['menu_position'] = $menu_position;
        $data['menu_name'] = $menu_name;
        $data['url_link'] = $url_link;
        $data['order_no'] = $order_no;

        if ($result == true) {
            if ($menu_idx == 0) {
                $model_result = $menu_model->procMenuInsert($data);
                $menu_idx = $model_result['insert_id'];
            } else {
                $model_result = $menu_model->procMenuUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/menu/list';
        $proc_result['menu_idx'] = $menu_idx;

        return json_encode($proc_result);
    }

    public function edit()
    {
        $menu_model = new MenuModel();

        $result = true;
        $message = '정상';

        $menu_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['menu_idx'] = $menu_idx;

        $model_result = $menu_model->getMenuInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/menu/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = '정상처리 되었습니다.';

        $menu_model = new MenuModel();

        $menu_idx = $this->request->getPost('menu_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['menu_idx'] = $menu_idx;

        $model_result = $menu_model->procMenuDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/menu/list';

        return json_encode($proc_result);
    }

}