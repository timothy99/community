<?php

namespace App\Models\Console;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    public function getCategoryList()
    {
        $result = true;
        $message = '목록 불러오기가 완료되었습니다.';

        $db = $this->db;
        $builder = $db->table('product_category');
        $builder->where('upper_idx', 0);
        $builder->where('del_yn', 'N');
        $builder->orderBy('language', 'asc');
        $builder->orderBy('order_no', 'asc');
        $list = $builder->get()->getResult();
        foreach ($list as $no => $val) {
            $product_category_idx = $val->product_category_idx;
            $builder->where('del_yn', 'N');
            $builder->where('upper_idx', $product_category_idx);
            $builder->orderBy('order_no', 'asc');
            $list2 = $builder->get()->getResult();
            $list[$no]->list = $list2;
            foreach ($list2 as $no2 => $val2) {
                $product_category_idx = $val2->product_category_idx;
                $builder->where('del_yn', 'N');
                $builder->where('upper_idx', $product_category_idx);
                $builder->orderBy('order_no', 'asc');
                $list3 = $builder->get()->getResult();
                $list[$no]->list[$no2]->list = $list3;
            }
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;

        return $proc_result;
    }

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

    public function procCategoryInsert(array $data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '카테고리가 등록되었습니다.';
        $insert_id = 0;

        $upper_idx = $data['upper_idx'];
        $idx1 = $data['idx1'];
        $idx2 = $data['idx2'];
        $idx3 = $data['idx3'];
        $category_position = $data['category_position'];
        $category_name = $data['category_name'];
        $language = $data['language'];
        $order_no = $data['order_no'];

        $category_position = 1;
        $new_category_position = 1;

        $db = $this->db;
        $db->transStart();

        $input_data = array();
        $input_data['product_category_idx'] = $upper_idx;
        $model_result = $this->getCategoryInfo($input_data);
        $upper_category_info = $model_result['info'];
        if ($upper_category_info) {
            $idx1 = $upper_category_info->idx1;
            $idx2 = $upper_category_info->idx2;
            $idx3 = $upper_category_info->idx3;
            $category_position = $upper_category_info->category_position;
            $new_category_position = $category_position + 1;
        }

        $builder = $db->table('product_category');
        $builder->set('upper_idx', $upper_idx);
        $builder->set('language', $language);
        $builder->set('idx1', $idx1);
        $builder->set('idx2', $idx2);
        $builder->set('idx3', $idx3);
        $builder->set('order_no', $order_no);
        $builder->set('category_position', $new_category_position);
        $builder->set('category_name', $category_name);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $user_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();
        $insert_id = $db->insertID();

        $builder = $db->table('product_category');
        $builder->set('idx'.$new_category_position, $insert_id);
        $builder->where('product_category_idx', $insert_id);
        $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['insert_id'] = $insert_id;

        return $proc_result;
    }

    public function procCategoryUpdate(array $data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '카테고리가 수정되었습니다.';
        $insert_id = 0;

        $product_category_idx = $data['product_category_idx'];
        $category_name = $data['category_name'];
        $language = $data['language'];
        $order_no = $data['order_no'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('product_category');
        $builder->set('language', $language);
        $builder->set('order_no', $order_no);
        $builder->set('category_name', $category_name);
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('product_category_idx', $product_category_idx);
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['insert_id'] = $insert_id;

        return $proc_result;
    }

    public function procCategoryDelete(array $data)
    {
        $user_id = getUserSessionInfo('member_id');
        $today = date('YmdHis');

        $result = true;
        $message = '카테고리가 삭제되었습니다.';

        $product_category_idx = $data['product_category_idx'];

        $db = $this->db;
        $db->transStart();

        $builder = $db->table('product_category');
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('product_category_idx', $product_category_idx);
        $result = $builder->update();

        // 하위 카테고리도 모두 삭제 처리
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->where('upper_idx', $product_category_idx);
        $result = $builder->update();

        // 2차 하위 카테고리도 모두 삭제 처리
        $builder->set('del_yn', 'Y');
        $builder->set('upd_id', $user_id);
        $builder->set('upd_date', $today);
        $builder->whereIn('upper_idx', function($subquery) use ($product_category_idx) {
            return $subquery->select('product_category_idx')
                ->from('product_category')
                ->where('upper_idx', $product_category_idx)
                ->where('del_yn', 'N');
        });
        $result = $builder->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            $result = false;
            $message = '입력에 오류가 발생했습니다.';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }
}
