<?php

namespace App\Models\User;

use CodeIgniter\Model;

class SnsModel extends Model
{
    /**
     * SNS 타입 + SNS ID로 연결 정보 조회
     */
    public function getSnsInfo(array $data): array
    {
        $sns_type = $data['sns_type'];
        $sns_id   = $data['sns_id'];

        $db      = $this->db;
        $builder = $db->table('member_sns');
        $builder->where('sns_type', $sns_type);
        $builder->where('sns_id', $sns_id);
        $info = $builder->get()->getFirstRow();

        return ['info' => $info];
    }

    /**
     * SNS ID로 연결된 회원 정보 조회
     */
    public function getMemberBySns(array $data): array
    {
        $sns_type = $data['sns_type'];
        $sns_id   = $data['sns_id'];

        $db      = $this->db;
        $builder = $db->table('member_sns ms');
        $builder->select('m.*');
        $builder->join('member m', 'm.member_idx = ms.member_idx');
        $builder->where('ms.sns_type', $sns_type);
        $builder->where('ms.sns_id', $sns_id);
        $builder->where('m.del_yn', 'N');
        $info = $builder->get()->getFirstRow();

        return ['info' => $info];
    }

    /**
     * 회원의 SNS 연결 목록 조회 (sns_type을 key로 한 map 반환)
     */
    public function getMemberSnsList(array $data): array
    {
        $member_idx = $data['member_idx'];

        $db      = $this->db;
        $builder = $db->table('member_sns');
        $builder->where('member_idx', $member_idx);
        $list = $builder->get()->getResultArray();

        $sns_map = [];
        foreach ($list as $item) {
            $sns_map[$item['sns_type']] = $item;
        }

        return [
            'list'    => $list,
            'sns_map' => $sns_map,
        ];
    }

    /**
     * SNS 연결 등록
     */
    public function procSnsInsert(array $data): array
    {
        $result  = true;
        $message = 'SNS 연결이 완료되었습니다.';

        $member_idx = (int)$data['member_idx'];
        $sns_type   = $data['sns_type'];
        $sns_id     = $data['sns_id'];
        $today      = date('YmdHis');

        $db      = $this->db;
        $builder = $db->table('member_sns');
        $builder->set('member_idx', $member_idx);
        $builder->set('sns_type', $sns_type);
        $builder->set('sns_id', $sns_id);
        $builder->set('ins_date', $today);

        try {
            $builder->insert();
        } catch (\Exception $e) {
            log_message('error', '[SnsModel] procSnsInsert error: ' . $e->getMessage());
            $result  = false;
            $message = 'SNS 연결 저장에 실패했습니다.';
        }

        return [
            'result'  => $result,
            'message' => $message,
        ];
    }

    /**
     * SNS 연결 해제
     */
    public function procSnsDelete(array $data): array
    {
        $result  = true;
        $message = 'SNS 연결이 해제되었습니다.';

        $member_idx = (int)$data['member_idx'];
        $sns_type   = $data['sns_type'];

        $db      = $this->db;
        $builder = $db->table('member_sns');
        $builder->where('member_idx', $member_idx);
        $builder->where('sns_type', $sns_type);

        $cnt = $builder->countAllResults(false);

        if ($cnt === 0) {
            $result  = false;
            $message = '연결된 SNS 정보가 없습니다.';
        } else {
            $builder->delete();
        }

        return [
            'result'  => $result,
            'message' => $message,
        ];
    }
}
