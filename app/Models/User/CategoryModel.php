<?php

namespace App\Models\User;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    public function getCategorySearchList(array $data)
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $upper_idx = $data['search_category'];
        $language = $data['search_language'];

        $db = $this->db;
        $builder = $db->table('product_category');
        $builder->where('del_yn', 'N');
        $builder->where('upper_idx', $upper_idx);
        if ($language != null) {
            $builder->where('language', $language);
        }
        $builder->orderBy('language', 'asc');
        $builder->orderBy('order_no', 'asc');
        $list = $builder->get()->getResult();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

    public function getCategoryInfo(array $data)
    {
        $product_category_idx = $data['product_category_idx'];

        $db = $this->db;
        $builder = $db->table('product_category');
        $builder->where('product_category_idx', $product_category_idx);
        $info = $builder->get()->getRow();

        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return $proc_result;
    }

}
