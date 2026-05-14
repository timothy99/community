<?php

namespace App\Models\User;

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

    // 조회수 증가
    public function procHitCntUpdate(array $data)
    {
        $result = true;
        $message = '조회수 증가가 완료되었습니다.';

        $product_idx = $data['product_idx'];

        $db = $this->db;
        $builder = $db->table('product');
        $builder->set('hit_cnt', 'hit_cnt+1', false);
        $builder->where('product_idx', $product_idx);
        $builder->update();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

}
