<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\User\FileModel;
use App\Models\Console\CategoryModel;

class ProductModel extends Model
{
    public function getProductList(array $data)
    {
        $category_model = new CategoryModel();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $page = $data['search_page'];
        $rows = $data['search_rows'];
        $search_condition = $data['search_condition'];
        $search_text = $data['search_text'];
        $language = $data['search_language'];
        $product_category_idx1 = $data['product_category_idx1'];
        $product_category_idx2 = $data['product_category_idx2'];
        $product_category_idx3 = $data['product_category_idx3'];

        $db = $this->db;
        $builder = $db->table('product');
        $builder->where('del_yn', 'N');
        if ($search_text != null) {
            $builder->like($search_condition, $search_text);
        }
        if ($language != null) {
            $builder->where('language', $language);
        }
        if ($product_category_idx1 != 0) {
            $builder->where('product_category_idx1', $product_category_idx1);
        }
        if ($product_category_idx2 != 0) {
            $builder->where('product_category_idx2', $product_category_idx2);
        }
        if ($product_category_idx3 != 0) {
            $builder->where('product_category_idx3', $product_category_idx3);
        }
        $builder->orderBy('product_idx', 'desc');
        $builder->limit($rows, getOffset($page, $rows));
        $cnt = $builder->countAllResults(false);
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $list[$no]->list_no = $cnt-$no-(($page-1)*$rows);
            $list[$no]->ins_date_txt = convertTextToDate($val->ins_date, 1, 1);

            // 카테고리 정보 갖고 오기
            $data = array();
            $data['product_category_idx'] = $val->product_category_idx1;
            $model_result = $category_model->getCategoryInfo($data);
            $list[$no]->product_category_info1 = $model_result['info'];

            $data['product_category_idx'] = $val->product_category_idx2;
            $model_result = $category_model->getCategoryInfo($data);
            $list[$no]->product_category_info2 = $model_result['info'];

            $data['product_category_idx'] = $val->product_category_idx3;
            $model_result = $category_model->getCategoryInfo($data);
            $list[$no]->product_category_info3 = $model_result['info'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;

        return $proc_result;
    }

    public function getProductInfo(array $data)
    {
        $file_model = new FileModel();
        $category_model = new CategoryModel();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $product_idx = $data['product_idx'];

        $db = $this->db;
        $builder = $db->table('product');
        $builder->where('del_yn', 'N');
        $builder->where('product_idx', $product_idx);
        $info = $builder->get()->getRow();

        $info->ins_date_txt = convertTextToDate($info->ins_date, 1, 1);
        $info->reg_date_txt = convertTextToDate($info->reg_date, 1, 1);
        $info->contents = nl2br_only($info->contents);

        // 제품의 옵션 갖고 오기
        $model_result = $this->getProductOptionList($data);
        $option_list = $model_result['list'];
        $info->option_list = $option_list;

        // 제품의 이미지 정보 갖고 오기
        $model_result = $this->getProductImageList($data);
        $image_list = $model_result['list'];
        $info->image_list = $image_list;

        // 제품의 메인 이미지의 파일 정보 갖고 오기
        if ($info->main_image_id != null) {
            $info->main_image_file_info = $file_model->getFileInfo($info->main_image_id);
        }

        // 제품의 카테고리 3개 정보 갖고 오기
        $model_result = $category_model->getCategoryInfo(array('product_category_idx' => $info->product_category_idx1));
        $info->product_category_info1 = $model_result['info'];
        $model_result = $category_model->getCategoryInfo(array('product_category_idx' => $info->product_category_idx2));
        $info->product_category_info2 = $model_result['info'];
        $model_result = $category_model->getCategoryInfo(array('product_category_idx' => $info->product_category_idx3));
        $info->product_category_info3 = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

    // 제품의 옵션 갖고 오기
    public function getProductOptionList(array $data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $product_idx = $data['product_idx'];

        $db = $this->db;
        $builder = $db->table('product_option');
        $builder->where('del_yn', 'N');
        $builder->where('product_idx', $product_idx);
        $list = $builder->get()->getResult();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    // 제품의 이미지 정보 갖고 오기
    public function getProductImageList(array $data)
    {
        $file_model = new FileModel();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $product_idx = $data['product_idx'];

        $db = $this->db;
        $builder = $db->table('product_image');
        $builder->where('del_yn', 'N');
        $builder->where('product_idx', $product_idx);
        $list = $builder->get()->getResult();

        foreach ($list as $no => $val) {
            $list[$no]->file_info = $file_model->getFileInfo($val->file_id);
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    public function procProductInsert(array $data, object $db)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '제품이 등록되었습니다.';
        $insert_id = 0;

        $main_image_id = $data['main_image_id'];
        $language = $data['language'];
        $product_category_idx1 = $data['product_category_idx1'];
        $product_category_idx2 = $data['product_category_idx2'];
        $product_category_idx3 = $data['product_category_idx3'];
        $title = $data['title'];
        $contents = $data['contents'];
        $reg_date = $data['reg_date'];
        $hit_cnt = $data['hit_cnt'];
        $display_yn = $data['display_yn'];
        $file_arr = $data['file_arr'];
        $option_arr = $data['option_arr'];

        $builder = $db->table('product');
        $builder->set('main_image_id', $main_image_id);
        $builder->set('language', $language);
        $builder->set('product_category_idx1', $product_category_idx1);
        $builder->set('product_category_idx2', $product_category_idx2);
        $builder->set('product_category_idx3', $product_category_idx3);
        $builder->set('title', $title);
        $builder->set('contents', $contents);
        $builder->set('reg_date', $reg_date);
        $builder->set('hit_cnt', $hit_cnt);
        $builder->set('display_yn', $display_yn);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        $builder = $db->table('product_image');
        $builder->where('product_idx', $insert_id);
        $builder->delete();

        foreach ($file_arr as $file_id) {
            $builder = $db->table('product_image');
            $builder->set('product_idx', $insert_id);
            $builder->set('file_id', $file_id);
            $builder->insert();
        }

        $builder = $db->table('product_option');
        $builder->where('product_idx', $insert_id);
        $builder->delete();

        foreach ($option_arr as $option) {
            $builder = $db->table('product_option');
            $builder->set('product_idx', $insert_id);
            $builder->set('option_name', $option['option_name']);
            $builder->set('option_value', $option['option_value']);
            $builder->insert();
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;
        $model_result['insert_id'] = $insert_id;

        return $model_result;
    }

    public function procProductUpdate(array $data, object $db)
    {
        // 게시판 입력과 관련된 기본 정보
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $product_idx = $data['product_idx'];
        $main_image_id = $data['main_image_id'];
        $language = $data['language'];
        $product_category_idx1 = $data['product_category_idx1'];
        $product_category_idx2 = $data['product_category_idx2'];
        $product_category_idx3 = $data['product_category_idx3'];
        $title = $data['title'];
        $contents = $data['contents'];
        $reg_date = $data['reg_date'];
        $hit_cnt = $data['hit_cnt'];
        $display_yn = $data['display_yn'];
        $file_arr = $data['file_arr'];
        $option_arr = $data['option_arr'];

        $builder = $db->table('product');
        $builder->set('title', $title);
        $builder->set('main_image_id', $main_image_id);
        $builder->set('language', $language);
        $builder->set('product_category_idx1', $product_category_idx1);
        $builder->set('product_category_idx2', $product_category_idx2);
        $builder->set('product_category_idx3', $product_category_idx3);
        $builder->set('contents', $contents);
        $builder->set('reg_date', $reg_date);
        $builder->set('hit_cnt', $hit_cnt);
        $builder->set('display_yn', $display_yn);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('product_idx', $product_idx);
        $result = $builder->update();

        $builder = $db->table('product_image');
        $builder->where('product_idx', $product_idx);
        $builder->delete();

        foreach ($file_arr as $file_id) {
            $builder = $db->table('product_image');
            $builder->set('product_idx', $product_idx);
            $builder->set('file_id', $file_id);
            $builder->insert();
        }

        $builder = $db->table('product_option');
        $builder->where('product_idx', $product_idx);
        $builder->delete();

        foreach ($option_arr as $option) {
            $builder = $db->table('product_option');
            $builder->set('product_idx', $product_idx);
            $builder->set('option_name', $option['option_name']);
            $builder->set('option_value', $option['option_value']);
            $builder->insert();
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;

        return $model_result;
    }

    public function procProductDelete(array $data)
    {
        $member_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '삭제가 잘 되었습니다';

        $product_idx = $data['product_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('product');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $builder->where('product_idx', $product_idx);
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
