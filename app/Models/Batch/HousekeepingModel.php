<?php

namespace App\Models\Batch;

use CodeIgniter\Model;

class HousekeepingModel extends Model
{
    /**
     * 파일명과 디렉토리로 DB의 파일 정보 한 건 조회
     * @param string $fileName   file_name_uploaded (랜덤 저장 파일명)
     * @param string $directory  file_directory (Ym 형식 예: 202604)
     */
    public function getFileByName(string $fileName, string $directory): ?object
    {
        $builder = $this->db->table('file');
        $builder->where('file_name_uploaded', $fileName);
        $builder->where('file_directory', $directory);

        return $builder->get()->getRow();
    }

    /**
     * 아래 7개 테이블에서 file_id 참조 여부 확인
     *  - board.main_image_id
     *  - board_file.file_id
     *  - config.company_logo
     *  - popup.popup_file
     *  - product.main_image_id
     *  - product_image.file_id
     *  - slide.slide_file
     *
     * @param string $file_id  mng_file.file_id
     */
    public function isFileIdUsedInTables(string $file_id): bool
    {
        $db = $this->db;

        // board.main_image_id (활성 게시물만)
        $builder = $db->table('board');
        $builder->where('main_image_id', $file_id);
        $builder->where('del_yn', 'N');
        $cnt = $builder->countAllResults();
        if ($cnt > 0) {
            return true;
        }

        // board_file.file_id (board_file 테이블은 del_yn 없음 - 전체 확인)
        $builder = $db->table('board_file');
        $builder->where('file_id', $file_id);
        $cnt = $builder->countAllResults();
        if ($cnt > 0) {
            return true;
        }

        // config.company_logo
        $builder = $db->table('config');
        $builder->where('company_logo', $file_id);
        $cnt = $builder->countAllResults();
        if ($cnt > 0) {
            return true;
        }

        // popup.popup_file (활성 팝업만)
        $builder = $db->table('popup');
        $builder->where('popup_file', $file_id);
        $builder->where('del_yn', 'N');
        $cnt = $builder->countAllResults();
        if ($cnt > 0) {
            return true;
        }

        // product.main_image_id (활성 제품만)
        $builder = $db->table('product');
        $builder->where('main_image_id', $file_id);
        $builder->where('del_yn', 'N');
        $cnt = $builder->countAllResults();
        if ($cnt > 0) {
            return true;
        }

        // product_image.file_id (활성 제품 이미지만)
        $builder = $db->table('product_image');
        $builder->where('file_id', $file_id);
        $builder->where('del_yn', 'N');
        $cnt = $builder->countAllResults();
        if ($cnt > 0) {
            return true;
        }

        // slide.slide_file (활성 슬라이드만)
        $builder = $db->table('slide');
        $builder->where('slide_file', $file_id);
        $builder->where('del_yn', 'N');
        $cnt = $builder->countAllResults();
        if ($cnt > 0) {
            return true;
        }

        return false;
    }

    /**
     * board.contents 에서 file_id LIKE 검색 (활성 게시물만)
     *
     * @param string $file_id  mng_file.file_id
     */
    public function isFileIdInBoardContents(string $file_id): bool
    {
        $db = $this->db;
        $builder = $db->table('board');
        $builder->where('del_yn', 'N');
        $builder->like('contents', $file_id);
        $cnt = $builder->countAllResults();
        $result = $cnt > 0 ? true : false;

        return $result;
    }

    /**
     * file_id 로 mng_file 레코드 삭제
     *
     * @param string $file_id  mng_file.file_id
     */
    public function deleteFileRecord(string $file_id): void
    {
        $db = $this->db;
        $builder = $db->table('file');
        $builder->where('file_id', $file_id);
        $builder->set('del_yn', 'Y');
        $builder->update();
    }

}
