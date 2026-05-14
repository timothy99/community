<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ProductModel;
use App\Models\User\CategoryModel;

class Product extends BaseController
{
    public function index()
    {
        return redirect()->to('/product/list');
    }

    public function list()
    {
        $product_model = new ProductModel();
        $category_model = new CategoryModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'title';
        $search_language = getUserSessionInfo('language');
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

        $data["search_language"] = $search_language;
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
        $proc_result['html_meta'] = create_meta('홈 > 제품 목록');

        return uview('/user/product/list', $proc_result);
    }

    public function view(int $product_idx)
    {
        $product_model = new ProductModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['product_idx'] = $product_idx;

        $model_result = $product_model->getProductInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $model_result = $product_model->procHitCntUpdate($data);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['html_meta'] = create_meta('홈 > 제품 상세 > '.$info->title);

        return uview('/user/product/view', $proc_result);
    }

}
