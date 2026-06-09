<?php

namespace App\Controllers\Batch;

use CodeIgniter\Controller;
use CodeIgniter\CLI\CLI;
use App\Models\Batch\HousekeepingModel;

/**
 * 하우스키핑 배치 컨트롤러
 *
 * 크론탭 실행 예시 (매월 1일 새벽 2시):
 *   0 2 1 * * /usr/bin/php /var/www/html/public/index.php batch/housekeeping >> /var/log/housekeeping.log 2>&1
 *
 * 수동 실행:
 *   php public/index.php batch/housekeeping
 */
class Batch extends Controller
{
    /**
     * CLI 출력과 로그 파일 기록을 동시에 처리
     */
    private function log(string $message, string $color = ''): void
    {
        CLI::write($message, $color ?: 'white');
        log_message('error', '[housekeeping] ' . $message);
    }

    public function housekeeping(): void
    {
        if (!is_cli()) {
            exit('Forbidden');
        }

        $housekeeping_model = new HousekeepingModel();

        // ──────────────────────────────────────────────
        // 타겟 월 계산: 현재 달 기준 2개월 전 (지지난달)
        // 예) 현재 2026-06 → 타겟 2026-04 (202604)
        // ──────────────────────────────────────────────
        $target_date = new \DateTime('first day of 2 months ago');
        $target_directory = $target_date->format('Ym'); // e.g. 202604
        $year_month = $target_date->format('Ym');
        $date_from = $year_month . '01000000';
        $last_day = (int) $target_date->format('t'); // 해당 월의 마지막 날 (28~31)
        $date_to = $year_month . sprintf('%02d', $last_day) . '235959';

        $upload_dir = UPLOADPATH . $target_directory . '/';
        $trash_dir = UPLOADPATH . 'trash/' . $target_directory . '/';

        // trash 디렉토리가 없으면 미리 생성
        if (!is_dir($trash_dir)) {
            mkdir($trash_dir, 0755, true);
        }

        $this->log('');
        $this->log('╔══════════════════════════════════════════╗', 'green');
        $this->log('║         하우스키핑 배치 시작             ║', 'green');
        $this->log('╚══════════════════════════════════════════╝', 'green');
        $this->log('[시작] ' . date('Y-m-d H:i:s'));
        $this->log('대상 디렉토리 : ' . $upload_dir);
        $this->log('날짜 범위     : ' . $date_from . ' ~ ' . $date_to);
        $this->log('');

        if (!is_dir($upload_dir)) {
            $this->log('[경고] 디렉토리가 존재하지 않습니다: ' . $upload_dir, 'yellow');
            $this->log('[종료] ' . date('Y-m-d H:i:s'));
            return;
        }

        $scan_files = scandir($upload_dir);
        $deleted_count = 0;
        $skipped_count = 0;
        $total_count = 0;

        foreach ($scan_files as $filename) {
            // .(현재) ..(상위 디렉토리) 및 index.html 제외 (CI4 기본 생성 파일)
            if (in_array($filename, ['.', '..', 'index.html'])) {
                continue;
            }

            $full_path = $upload_dir . $filename;

            // 일반 파일이 아니면 스킵 (서브 디렉토리 등)
            if (is_file($full_path) == false) {
                continue;
            }

            $total_count++;

            // ──────────────────────────────────────────────
            // [케이스 1] mng_file 테이블에 file_name_uploaded가
            //            없는 경우 → DB 정보 없는 고아 파일
            //            물리적 파일만 삭제
            // ──────────────────────────────────────────────
            $file_info = $housekeeping_model->getFileByName($filename, $target_directory);

            if ($file_info === null) {
                if (@rename($full_path, $trash_dir . $filename)) {
                    $deleted_count++;
                    $this->log('  [이동-DB없음]    ' . $filename, 'red');
                } else {
                    $this->log('  [이동실패-권한?] ' . $filename, 'yellow');
                }
                continue;
            }

            $file_id = $file_info->file_id;

            // ──────────────────────────────────────────────
            // [케이스 2] 7개 참조 테이블에서 file_id 사용 여부 확인
            //   - board.main_image_id
            //   - board_file.file_id
            //   - config.company_logo
            //   - popup.popup_file
            //   - product.main_image_id
            //   - product_image.file_id
            //   - slide.slide_file
            // ──────────────────────────────────────────────
            $used_result = $housekeeping_model->isFileIdUsedInTables($file_id);
            if ($used_result === true) {
                $skipped_count++;
                $this->log('  [유지-테이블참조] ' . $filename, 'cyan');
                continue;
            }

            // ──────────────────────────────────────────────
            // [케이스 3] board.contents에 file_id LIKE 검색
            //            에디터 본문 삽입 이미지 등 보호
            // ──────────────────────────────────────────────
            $board_contents_result = $housekeeping_model->isFileIdInBoardContents($file_id);
            if ($board_contents_result === true) {
                $skipped_count++;
                $this->log('  [유지-본문참조]   ' . $filename, 'cyan');
                continue;
            }

            // ──────────────────────────────────────────────
            // 어디에서도 사용되지 않음
            // → 물리적 파일 삭제 + mng_file DB 레코드 삭제
            // ──────────────────────────────────────────────
            if (@rename($full_path, $trash_dir . $filename)) {
                $housekeeping_model->deleteFileRecord($file_id); // mng_file.del_yn = 'Y'
                $deleted_count++;
                $this->log('  [이동-미사용]    ' . $filename, 'red');
            } else {
                $this->log('  [이동실패-권한?] ' . $filename, 'yellow');
            }
        }

        $this->log('');
        $this->log('──────────────────────────────────────────────');
        $this->log('전체 파일 수   : ' . $total_count . '개');
        $this->log('이동된 파일 수 : ' . $deleted_count . '개 → ' . $trash_dir, 'red');
        $this->log('유지된 파일 수 : ' . $skipped_count . '개', 'cyan');
        $this->log('──────────────────────────────────────────────');
        $this->log('[완료] ' . date('Y-m-d H:i:s'));
        $this->log('');
    }
}
