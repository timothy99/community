<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\ProductModel;
use App\Models\Console\CategoryModel;
use App\Models\Console\ConfigModel;
use App\Models\Console\LanguageModel;

class Product extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/product/list');
    }

    public function list()
    {
        $product_model = new ProductModel();
        $category_model = new CategoryModel();
        $config_model = new ConfigModel();
        $language_model = new LanguageModel();

        $model_result = $config_model->getConfigInfo();
        $config_info = $model_result['info'];

        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'product_name';
        $search_language = $this->request->getGet('search_language', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'kor';
        $product_category_idx1 = $this->request->getGet('product_category_idx1', FILTER_SANITIZE_SPECIAL_CHARS) ?? 0;
        $product_category_idx2 = $this->request->getGet('product_category_idx2', FILTER_SANITIZE_SPECIAL_CHARS) ?? 0;
        $product_category_idx3 = $this->request->getGet('product_category_idx3', FILTER_SANITIZE_SPECIAL_CHARS) ?? 0;

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;
        $data['product_category_idx1'] = $product_category_idx1;
        $data['product_category_idx2'] = $product_category_idx2;
        $data['product_category_idx3'] = $product_category_idx3;
        $data['search_language'] = $search_language;

        $model_result = $product_model->getProductList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        // 검색조건의 카테고리 생성을 위한 카테고리 배열 갖고 오기
        // 1차 카테고리는 항상 갖고 와야함
        // 다국어를 설정하였는지 먼저 확인
        $product_category_list1 = array();
        $product_category_list2 = array();
        $product_category_list3 = array();

        $language_yn = $config_info->language_yn;
        if ($language_yn === 'N') { // 다국어가 아니라면 한국어로 고정한다.
            $data["search_language"] = 'kor';
        } else {
            $data["search_language"] = $this->request->getGet('search_language', FILTER_SANITIZE_SPECIAL_CHARS);
        }
        $data["search_category"] = 0;
        $model_result = $category_model->getCategorySearchList($data);
        $product_category_list1 = $model_result["list"];

        if ($product_category_idx1 > 0) {
            $data["search_category"] = $product_category_idx1;
            $model_result = $category_model->getCategorySearchList($data);
            $product_category_list2 = $model_result["list"];
        }

        if ($product_category_idx2 > 0) {
            $data["search_category"] = $product_category_idx2;
            $model_result = $category_model->getCategorySearchList($data);
            $product_category_list3 = $model_result["list"];
        }

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
        $proc_result['product_category_list1'] = $product_category_list1;
        $proc_result['product_category_list2'] = $product_category_list2;
        $proc_result['product_category_list3'] = $product_category_list3;
        $proc_result['config_info'] = $config_info;
        $proc_result['language_list'] = $language_list;

        return aview('/console/product/list', $proc_result);
    }

    public function category()
    {
        $category_model = new CategoryModel();

        $result = true;
        $message = '정상';

        $upper_idx = $this->request->getPost('upper_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $language = $this->request->getPost('language', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['search_category'] = $upper_idx;
        $data['search_language'] = $language;
        $model_result = $category_model->getCategorySearchList($data);
        $view_html = view('/console/product/category', $model_result);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['html'] = $view_html;

        return $this->response->setJSON($proc_result);
    }

    public function write()
    {
        $language_model = new LanguageModel();
        $config_model = new ConfigModel();
        $category_model = new CategoryModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $config_info = $model_result['info'];
        $language_yn = $config_info->language_yn;
        if ($language_yn === 'N') { // 다국어가 아니라면 한국어로 고정한다.
            $language = 'kor';

            // 1차 카테고리는 항상 갖고 와야함
            $data = array();
            $data['search_category'] = 0;
            $data['search_language'] = $language;
            $model_result = $category_model->getCategorySearchList($data);
            $product_category_list1 = $model_result['list'];
        } else {
            $language = $this->request->getPost('language', FILTER_SANITIZE_SPECIAL_CHARS);
            $product_category_list1 = array();
        }

        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

        $info = new \stdClass();
        $info->product_idx = 0;
        $info->language = $language;
        $info->product_category_idx1 = 0;
        $info->product_category_idx2 = 0;
        $info->product_category_idx3 = 0;
        $info->title = '';
        $info->contents = '';
        $info->main_image_id = '';
        $info->display_yn = 'Y';
        $info->main_image_info = null;
        $info->file_list = array();
        $info->reg_date_txt = date('Y-m-d H:i:s');
        $info->hit_cnt = 0;

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['language_list'] = $language_list;
        $proc_result['product_category_list1'] = $product_category_list1;

        return aview('console/product/edit', $proc_result);
    }

    public function update()
    {
        $product_model = new ProductModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $product_idx = $this->request->getPost('product_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $main_image_hidden = $this->request->getPost('main_image_hidden', FILTER_SANITIZE_SPECIAL_CHARS);
        $language = $this->request->getPost('language', FILTER_SANITIZE_SPECIAL_CHARS);
        $product_category_idx1 = $this->request->getPost('product_category_idx1', FILTER_SANITIZE_SPECIAL_CHARS);
        $product_category_idx2 = $this->request->getPost('product_category_idx2', FILTER_SANITIZE_SPECIAL_CHARS);
        $product_category_idx3 = $this->request->getPost('product_category_idx3', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $contents = $this->request->getPost('contents');
        $reg_date = $this->request->getPost('reg_date', FILTER_SANITIZE_SPECIAL_CHARS);
        $hit_cnt = $this->request->getPost('hit_cnt', FILTER_SANITIZE_SPECIAL_CHARS);
        $display_yn = $this->request->getPost('display_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $file_idxs = $this->request->getPost('file_idxs', FILTER_SANITIZE_SPECIAL_CHARS);
        $option_name = $this->request->getPost('option_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $option_value = $this->request->getPost('option_value', FILTER_SANITIZE_SPECIAL_CHARS);

        $option_arr = array();
        foreach ($option_name as $no => $val) {
            if ($val != '') {
                $option_arr[] = array(
                    'option_name' => $val,
                    'option_value' => $option_value[$no]
                );
            }
        }

        $contents = remove_hwp_json($contents);

        $reg_date = convertTextToDate($reg_date, 2, 3);

        if ($file_idxs == '') {
            $file_arr = array();
        } else {
            $file_arr = explode('||', $file_idxs);
        }

        if ($title == '') { $result = false; $message = '제품명이 입력되지 않았습니다.'; }
        if ($contents == '') { $result = false; $message = '제품 설명이 입력되지 않았습니다.'; }

        $data = array();
        $data['product_idx'] = $product_idx;
        $data['main_image_id'] = $main_image_hidden;
        $data['language'] = $language;
        $data['product_category_idx1'] = $product_category_idx1;
        $data['product_category_idx2'] = $product_category_idx2;
        $data['product_category_idx3'] = $product_category_idx3;
        $data['title'] = $title;
        $data['contents'] = $contents;
        $data['reg_date'] = $reg_date;
        $data['hit_cnt'] = $hit_cnt;
        $data['display_yn'] = $display_yn;
        $data['file_arr'] = $file_arr;
        $data['option_arr'] = $option_arr;

        $db = \Config\Database::connect();
        $db->transStart();

        if ($result == true) {
            if ($product_idx == 0) {
                $model_result = $product_model->procProductInsert($data, $db);
                $product_idx = $model_result['insert_id'];
            } else {
                $model_result = $product_model->procProductUpdate($data, $db);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '처리 중 오류가 발생했습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/product/view/'.$product_idx;
        $proc_result['product_idx'] = $product_idx;

        return $this->response->setJSON($proc_result);
    }

    public function view()
    {
        $product_model = new ProductModel();

        $result = true;
        $message = '정상';

        $product_idx = $this->request->getUri()->getSegment(4);

        $data = array();
        $data['product_idx'] = $product_idx;

        $model_result = $product_model->getProductInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/product/view', $proc_result);
    }

    public function edit(int $product_idx)
    {
        $product_model = new ProductModel();
        $language_model = new LanguageModel();
        $config_model = new ConfigModel();
        $category_model = new CategoryModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $config_info = $model_result['info'];
        $language_yn = $config_info->language_yn;
        if ($language_yn === 'N') { // 다국어가 아니라면 한국어로 고정한다.
            $language = 'kor';
        } else {
            $language = $this->request->getPost('language', FILTER_SANITIZE_SPECIAL_CHARS);
        }

        // 언어 리스트는 무조건 갖고 와야함
        $model_result = $language_model->getLanguageUseList();
        $language_list = $model_result['list'];

        // 제품정보
        $data = array();
        $data['product_idx'] = $product_idx;
        $model_result = $product_model->getProductInfo($data);
        $info = $model_result['info'];

        $data['search_category'] = 0;
        $data['search_language'] = $language;
        $model_result = $category_model->getCategorySearchList($data);
        $product_category_list1 = $model_result['list'];

        $data['search_category'] = $info->product_category_idx1;
        $model_result = $category_model->getCategorySearchList($data);
        $product_category_list2 = $model_result['list'];

        $data['search_category'] = $info->product_category_idx2;
        $model_result = $category_model->getCategorySearchList($data);
        $product_category_list3 = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['language_list'] = $language_list;
        $proc_result['product_category_list1'] = $product_category_list1;
        $proc_result['product_category_list2'] = $product_category_list2;
        $proc_result['product_category_list3'] = $product_category_list3;

        return aview('console/product/edit', $proc_result);
    }

    public function delete()
    {
        $result = true;
        $message = "정상처리 되었습니다.";

        $product_model = new ProductModel();

        $product_idx = $this->request->getPost("product_idx", FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data["product_idx"] = $product_idx;

        $model_result = $product_model->procProductDelete($data);
        $result = $model_result["result"];
        $message = $model_result["message"];

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["return_url"] = "/csl/product/list";

        return $this->response->setJSON($proc_result);
    }

}