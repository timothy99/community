<?php

namespace App\Models\User;

use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

class SpreadsheetModel extends Model
{
    public function procExcelWrite($content_list, $filename, $header_list)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet(); // 현재 활성화된 Sheet 가져오기

        // 헤더 작업
        $column_array = array();
        foreach($header_list as $no => $val) {
            if ($no > 25) {
                $no = $no-26;
                $column_position = "A".chr(65+$no);
            } else {
                $column_position = chr(65+$no);
            }
            $column_array[] = $column_position;
            $sheet->setCellValue($column_position."1", $val);
        }

        // 값 입력
        foreach($content_list as $no1 => $val1) {
            $row_position = $no1+2;
            foreach($val1 as $no2 => $val2) {
                $column_position = $column_array[$no2];

                // 제일 첫 자리가 0으로 시작하고 1자리 이상인경우 텍스트로 변경하여 저장. 한자리의 0이면 숫자 0이므로 제외
                if (substr($val2, 0, 1) == "0" && strlen($val2) > 1) {
                    $sheet->setCellValueExplicit($column_position.$row_position, $val2, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValue($column_position.$row_position, $val2);
                }
            }
        }

        $write = new XlsxWriter($spreadsheet);
        ob_end_clean();
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Cache-Control: max-age=0");
        $write->save("php://output");
        exit;
    }

    public function procExcelRead($file_info)
    {
        $result = false;
        $message = "엑셀파일을 올려주세요.";

        $file_ext = $file_info->file_ext;
        if ($file_ext == "xls") {
            $reader = new XlsReader();
        } elseif ($file_ext == "xlsx") {
            $reader = new XlsxReader();
        } else {
            $result = false;
            $message = "엑셀파일을 올려주세요.";
        }

        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file_info->file_path);
        $spreadsheet_data = $spreadsheet->getSheet(0)->toArray(null, true, true, true);

        $list = array();
        $header = (object)array();
        foreach ($spreadsheet_data as $no => $val) {
            $info = (object)array();
            foreach($val as $column_name => $val2) {
                $info->$column_name = $val2;
            }
            $list[] = $info;
        }

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["list"] = $list;

        return $proc_result;
    }

}
