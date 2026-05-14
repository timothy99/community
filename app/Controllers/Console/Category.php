<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\CategoryModel;
use App\Models\Console\LanguageModel;

class Category extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/category/list');
    }

    public function list()
    {
        $category_model = new CategoryModel();

        $model_result = $category_model->getCategoryList();
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return aview('/console/category/list', $proc_result);
    }

    public function write(int $upper_idx)
    {
        $language_model = new LanguageModel();
        $category_model = new CategoryModel();

        $result = true;
        $message = '정상';

        $language = 'kor';

        $upper_menu_info = new \stdClass();

        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

        if ($upper_idx > 0) {
            $data = array();
            $data['product_category_idx'] = $upper_idx;
            $model_result = $category_model->getCategoryInfo($data);
            $upper_menu_info = $model_result['info'];

            $language = $upper_menu_info->language;
        }

        $info = new \stdClass();
        $info->product_category_idx = 0;
        $info->upper_idx = $upper_idx;
        $info->idx1 = 0;
        $info->idx2 = 0;
        $info->idx3 = 0;
        $info->category_position = 0;
        $info->category_name = '';
        $info->order_no = 0;
        $info->language = $language;

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['language_list'] = $language_list;
        $proc_result['upper_menu_info'] = $upper_menu_info;

        return aview('console/category/edit', $proc_result);
    }

    public function update()
    {
        $category_model = new CategoryModel();

        $result = true;
        $message = '카테고리가 저장되었습니다.';

        $product_category_idx = $this->request->getPost('product_category_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $upper_idx = $this->request->getPost('upper_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $idx1 = $this->request->getPost('idx1', FILTER_SANITIZE_SPECIAL_CHARS);
        $idx2 = $this->request->getPost('idx2', FILTER_SANITIZE_SPECIAL_CHARS);
        $idx3 = $this->request->getPost('idx3', FILTER_SANITIZE_SPECIAL_CHARS);
        $category_position = $this->request->getPost('category_position', FILTER_SANITIZE_SPECIAL_CHARS);
        $category_name = $this->request->getPost('category_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $language = $this->request->getPost('language', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'kor';
        $order_no = $this->request->getPost('order_no', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['product_category_idx'] = $product_category_idx;
        $data['upper_idx'] = $upper_idx;
        $data['idx1'] = $idx1;
        $data['idx2'] = $idx2;
        $data['idx3'] = $idx3;
        $data['category_position'] = $category_position;
        $data['category_name'] = $category_name;
        $data['language'] = $language;
        $data['order_no'] = $order_no;

        if (empty($category_name)) {
            $result = false;
            $message = '카테고리명을 입력해주세요.';
        }

        if ($result == true) {
            if ($product_category_idx == 0) {
                // 등록
                $model_result = $category_model->procCategoryInsert($data);
                $product_category_idx = $model_result['insert_id'];
            } else {
                // 수정
                $model_result = $category_model->procCategoryUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['product_category_idx'] = $product_category_idx;
        $proc_result['return_url'] = '/csl/category/list';

        return $this->response->setJSON($proc_result);
    }

    public function edit(int $product_category_idx)
    {
        $category_model = new CategoryModel();
        $language_model = new LanguageModel();

        $result = true;
        $message = '정상';

        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

        $data = array();
        $data['product_category_idx'] = $product_category_idx;

        $model_result = $category_model->getCategoryInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['language_list'] = $language_list;

        return aview('console/category/edit', $proc_result);
    }

    public function delete()
    {
        $category_model = new CategoryModel();

        $result = true;
        $message = '카테고리가 삭제되었습니다.';

        $product_category_idx = $this->request->getPost('product_category_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['product_category_idx'] = $product_category_idx;

        if ($result == true) {
            $model_result = $category_model->procCategoryDelete($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/category/list';

        return $this->response->setJSON($proc_result);
    }

}
