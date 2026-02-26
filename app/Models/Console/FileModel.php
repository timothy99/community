<?php

namespace App\Models\Console;

use CodeIgniter\Model;
use App\Models\Csl\Board\ConfigModel;

class FileModel extends Model
{
    // 일반적인 파일 업로드
    public function uploadGeneralFile($data)
    {
        $result = true;
        $message = '파일 업로드가 완료되었습니다.';

        $user_file = $data['user_file'];

        if ($user_file->getName() == null) {
            $result = false;
            $message = '업로드를 취소합니다.';
        }

        if ($result == true) {
            $is_valid = $user_file->isValid(); // 파일이 정상인지 확인
            if($is_valid == false) { // 올린 파일이 잘못된 경우
                $result = false;
                $message = '파일이 잘못되었습니다.';
            }
        }

        if ($result == true) {
            // 허용된 이미지 크기를 넘지 않는지 확인한다.
            $data['upload_size'] = $user_file->getSize();
            $model_result = $this->checkFileSize($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        // 이미지인지 확인
        if ($result == true) { // 파일이 정상인 경우
            $data['mime_type'] = $user_file->getMimeType(); // mimetype확인
            $data['file_ext'] = $user_file->getClientExtension(); // 파일 확장자
        }

        // 파일이 해당되는 mimetype에 있음 정상 결과를 반환한다.
        if ($result == true) {
            $model_result = $this->checkMimeType($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
            $data['category'] = $model_result['category'];
        }

        // 허용되는 파일 형식이라면 저장한다.
        if ($result == true) {
            // 이미지 파일이든 아니든 일단 파일은 업로드한다.
            $file_info = $this->saveFile($data); // 파일을 올린다.
            $data['file_name_org'] = $file_info['file_name_org'];
            $data['file_directory'] = $file_info['file_directory'];
            $data['file_name_uploaded'] = $file_info['file_name_uploaded'];
            $data['file_id'] = $file_info['file_id'];
            $file_path = $file_info['file_directory'].'/'.$file_info['file_name_uploaded'];
            $data['file_path'] = $file_path;
            $data['file_size'] = $file_info['file_size'];
            $data['file_size_kb'] = number_format($file_info['file_size'] / 1024, 2);
        }

        if ($result == true) {
            // 이미지인 경우 리사이징 처리
            if ($data['category'] == 'image') {
                $model_result = $this->resizeImageFile($data);
                $data['file_size'] = $model_result['file_size']; // 리사이징을 하고 나면 파일 용량이 변경되었을것이다.
                $data['file_size_kb'] = number_format($model_result['file_size'] / 1024, 2);
                $data['image_width'] = $model_result['image_width'];
                $data['image_width_txt'] = number_format($model_result['image_width']);
                $data['image_height'] = $model_result['image_height'];
                $data['image_height_txt'] = number_format($model_result['image_height']);
            } else { // 이미지가 아니라면 당연히 가로세로 해상도는 0이 된다.
                $data['image_width'] = 0;
                $data['image_width_txt'] = '0';
                $data['image_height'] = 0;
                $data['image_height_txt'] = '0';
            }
        }

        if ($result == true) {
            $model_result = $this->insertFileInfo($data); // DB에 파일 정보 저장
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $data;

        return $proc_result;
    }

    // 게시판의 파일 업로드
    public function uploadBoardFile($data)
    {
        $result = true;
        $message = '파일 업로드가 완료되었습니다.';

        $user_file = $data['user_file'];

        if ($user_file->getName() == null) {
            $result = false;
            $message = '업로드를 취소합니다.';
        }

        if ($result == true) {
            $is_valid = $user_file->isValid(); // 파일이 정상인지 확인
            if($is_valid == false) { // 올린 파일이 잘못된 경우
                $result = false;
                $message = '파일이 잘못되었습니다.';
            }
        }

        // 게시판 설정을 통해 해당 게시판의 정보대로 파일 첨부와 관련해 넘는게 없는지 확인
        if ($result == true) {
            $model_result = $this->getUploadInfo($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        // 이미지인지 확인
        if ($result == true) { // 파일이 정상인 경우
            $data['mime_type'] = $user_file->getMimeType(); // mimetype확인
            $data['file_ext'] = $user_file->getClientExtension(); // 파일 확장자
        }

        // 파일이 해당되는 mimetype에 있음 정상 결과를 반환한다.
        if ($result == true) {
            $model_result = $this->checkMimeType($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
            $data['category'] = $model_result['category'];
        }

        // 허용되는 파일 형식이라면 저장한다.
        if ($result == true) {
            // 이미지 파일이든 아니든 일단 파일은 업로드한다.
            $file_info = $this->saveFile($data); // 파일을 올린다.
            $data['file_name_org'] = $file_info['file_name_org'];
            $data['file_directory'] = $file_info['file_directory'];
            $data['file_name_uploaded'] = $file_info['file_name_uploaded'];
            $data['file_id'] = $file_info['file_id'];
            $file_path = $file_info['file_directory'].'/'.$file_info['file_name_uploaded'];
            $data['file_path'] = $file_path;
            $data['file_size'] = $file_info['file_size'];
            $data['file_size_kb'] = number_format($file_info['file_size'] / 1024, 2);
        }

        if ($result == true) {
            // 이미지인 경우 리사이징 처리
            if ($data['category'] == 'image') {
                $model_result = $this->resizeImageFile($data);
                $data['file_size'] = $model_result['file_size']; // 리사이징을 하고 나면 파일 용량이 변경되었을것이다.
                $data['file_size_kb'] = number_format($model_result['file_size'] / 1024, 2);
                $data['image_width'] = $model_result['image_width'];
                $data['image_width_txt'] = number_format($model_result['image_width']);
                $data['image_height'] = $model_result['image_height'];
                $data['image_height_txt'] = number_format($model_result['image_height']);
            } else { // 이미지가 아니라면 당연히 가로세로 해상도는 0이 된다.
                $data['image_width'] = 0;
                $data['image_width_txt'] = '0';
                $data['image_height'] = 0;
                $data['image_height_txt'] = '0';
            }
        }

        if ($result == true) {
            $model_result = $this->insertFileInfo($data); // DB에 파일 정보 저장
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $data;

        return $proc_result;
    }

    // 원본파일 그대로
    public function uploadOriginalFile($data)
    {
        $result = true;
        $message = '파일 업로드가 완료되었습니다.';

        $user_file = $data['user_file'];

        if ($user_file->getName() == null) {
            $result = false;
            $message = '업로드를 취소합니다.';
        }

        if ($result == true) {
            $is_valid = $user_file->isValid(); // 파일이 정상인지 확인
            if($is_valid == false) { // 올린 파일이 잘못된 경우
                $result = false;
                $message = '파일이 잘못되었습니다.';
            }
        }

        if ($result == true) {
            // 허용된 이미지 크기를 넘지 않는지 확인한다.
            $data['upload_size'] = $user_file->getSize();
            $model_result = $this->checkFileSize($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        // 파일이 해당되는 mimetype에 있음 정상 결과를 반환한다.
        if ($result == true) {
            $data['mime_type'] = $user_file->getMimeType(); // mimetype확인
            $data['file_ext'] = $user_file->getClientExtension(); // 파일 확장자
            $model_result = $this->checkMimeType($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
            $data['category'] = $model_result['category'];
        }

        // 허용되는 파일 형식이라면 저장한다.
        if ($result == true) {
            // 이미지 파일이든 아니든 일단 파일은 업로드한다.
            $file_info = $this->saveFile($data); // 파일을 올린다.
            $data['file_name_org'] = $file_info['file_name_org'];
            $data['file_directory'] = $file_info['file_directory'];
            $data['file_name_uploaded'] = $file_info['file_name_uploaded'];
            $data['file_id'] = $file_info['file_id'];
            $file_path = $file_info['file_directory'].'/'.$file_info['file_name_uploaded'];
            $data['file_path'] = $file_path;
            $data['file_size'] = $file_info['file_size'];
            $data['file_size_kb'] = number_format($file_info['file_size'] / 1024, 2);

            if ($data['category'] == 'image') {
                $image = \Config\Services::image();
                $image->withFile(UPLOADPATH.$file_path);
                $data['image_width'] = $image->getWidth();
                $data['image_width_txt'] = number_format($image->getWidth());
                $data['image_height'] = $image->getHeight();
                $data['image_height_txt'] = number_format($image->getHeight());
            } else {
                $data['image_width'] = 0;
                $data['image_width_txt'] = '0';
                $data['image_height'] = 0;
                $data['image_height_txt'] = '0';
            }
        }

        if ($result == true) {
            $model_result = $this->insertFileInfo($data); // DB에 파일 정보 저장
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $data;

        return $proc_result;
    }

    // 이미지 업로드
    public function uploadImageFile($data)
    {
        $result = true;
        $message = '파일 업로드가 완료되었습니다.';

        $user_file = $data['user_file'];

        if ($user_file->getName() == null) {
            $result = false;
            $message = '업로드를 취소합니다.';
        }

        if ($result == true) {
            $is_valid = $user_file->isValid(); // 파일이 정상인지 확인
            if($is_valid == false) { // 올린 파일이 잘못된 경우
                $result = false;
                $message = '파일이 잘못되었습니다.';
            }
        }

        if ($result == true) {
            // 허용된 이미지 크기를 넘지 않는지 확인한다.
            $data['upload_size'] = $user_file->getSize();
            $model_result = $this->checkFileSize($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        // 이미지인지 확인
        if ($result == true) { // 파일이 정상인 경우
            $data['mime_type'] = $user_file->getMimeType(); // mimetype확인
            $data['file_ext'] = $user_file->getClientExtension(); // 파일 확장자
        }

        // 파일이 해당되는 mimetype에 있음 정상 결과를 반환한다.
        if ($result == true) {
            $model_result = $this->checkMimeType($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
            $data['category'] = $model_result['category'];
        }

        // 허용되는 파일 형식이라면 저장한다.
        if ($result == true) {
            // 이미지 파일이든 아니든 일단 파일은 업로드한다.
            $file_info = $this->saveFile($data); // 파일을 올린다.
            $data['file_name_org'] = $file_info['file_name_org'];
            $data['file_directory'] = $file_info['file_directory'];
            $data['file_name_uploaded'] = $file_info['file_name_uploaded'];
            $data['file_id'] = $file_info['file_id'];
            $file_path = $file_info['file_directory'].'/'.$file_info['file_name_uploaded'];
            $data['file_path'] = $file_path;
            $data['file_size'] = $file_info['file_size'];
            $data['file_size_kb'] = number_format($file_info['file_size'] / 1024, 2);
        }

        if ($result == true) {
            // 이미지인 경우 리사이징 처리
            if ($data['category'] == 'image') {
                $model_result = $this->resizeImageFile($data);
                $data['file_size'] = $model_result['file_size']; // 리사이징을 하고 나면 파일 용량이 변경되었을것이다.
                $data['file_size_kb'] = number_format($model_result['file_size'] / 1024, 2);
                $data['image_width'] = $model_result['image_width'];
                $data['image_width_txt'] = number_format($model_result['image_width']);
                $data['image_height'] = $model_result['image_height'];
                $data['image_height_txt'] = number_format($model_result['image_height']);
            } else { // 이미지가 아니라면 당연히 가로세로 해상도는 0이 된다.
                $data['image_width'] = 0;
                $data['image_width_txt'] = '0';
                $data['image_height'] = 0;
                $data['image_height_txt'] = '0';
            }
        }

        if ($result == true) {
            $model_result = $this->insertFileInfo($data); // DB에 파일 정보 저장
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $data;

        return $proc_result;
    }

    // 파일 사이즈 체크해서 우리가 설정한 크기와 맞는지 확인
    public function checkFileSize($data)
    {
        $result = true;
        $message = '파일이 지정된 용량을 초과하지 않았습니다.';

        $upload_size = $data['upload_size'];
        $limit_size = $data['limit_size'];

        $limit_size_byte = $limit_size*1024*1024; // MB단위로 입력된 숫자를 바이트 단위로 변경
        // 입력받은 이미지 사이즈와 비교해서
        if ($upload_size > $limit_size_byte) { // 이미지 사이즈가 크면
            $check_file_size = false; // false 반환
        } else { // 이미지 사이즈가 규정보다 작으면
            $check_file_size = true; // true 반환
        }

        if($check_file_size == false) {
            $result = false;
            $message = '파일이 '.$limit_size.'MB 보다 큽니다';
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

    // 확장자 체크 해서 필터에 따라 분류가 맞는지 확인
    public function checkMimeType($data)
    {
        $result = false;
        $message = '업로드가 허용되지 않는 파일형식입니다.';

        $check_image_type = false;
        $check_file_type = false;
        $category = 'unknown';

        $allowed_type = $data['allowed_type'];
        $user_file_type = $data['mime_type'];

        $image_type = array();
        $file_type = array();

        // 이미지용 mime_type
        $image_type[] = 'image/png';
        $image_type[] = 'image/jpg';
        $image_type[] = 'image/jpeg';
        $image_type[] = 'image/gif';

        $file_type[] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; // 엑셀(xlsx)
        $file_type[] = 'application/vnd.ms-excel'; // 엑셀(xls)
        $file_type[] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation'; // 파워포인트(pptx)
        $file_type[] = 'application/vnd.ms-powerpoint'; // 파워포인트(ppt)
        $file_type[] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'; // 워드(docx)
        $file_type[] = 'application/msword'; // 워드(doc)
        $file_type[] = 'text/plain'; // 텍스트
        $file_type[] = 'application/pdf'; // PDF
        $file_type[] = 'application/zip'; // ZIP
        $file_type[] = 'application/x-hwp'; // 한글파일

        $check_image_type = in_array($user_file_type, $image_type);
        $check_file_type = in_array($user_file_type, $file_type);

        if ($allowed_type == 'image' && $check_image_type === true) {
            $category = 'image';
            $result = true;
        }

        if ($allowed_type == 'file' && $check_file_type === true) {
            $category = 'file';
            $result = true;
        }

        if ($allowed_type == 'both' && ($check_image_type === true || $check_file_type === true)) {
            if ($check_image_type === true) {
                $category = 'image';
            }
            if ($check_file_type === true) {
                $category = 'file';
            }
            $result = true;
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['category'] = $category;
        

        return $proc_result;
    }

    // 파일을 저장한다.
    public function saveFile($data)
    {
        $user_file = $data['user_file'];

        $upload_date_path = date('Y/m'); // 업로드 디렉토리는 연/월로 생성
        $random_name = $user_file->getRandomName(); // 랜덤네임 생성
        $user_file->store($upload_date_path, $random_name); // 저장
        $file_id = getRandomString(4, 32); // 보안을 위한 랜덤문자 생성
        $file_size = $user_file->getSize();

        $file_info = array();
        $file_info['file_name_org'] = $user_file->getClientName();
        $file_info['file_directory'] = $upload_date_path;
        $file_info['file_name_uploaded'] = $random_name;
        $file_info['file_id'] = $file_id;
        $file_info['file_path'] = $file_id;
        $file_info['file_size'] = $file_size;

        return $file_info;
    }

    // 이미지 파일 리사이즈
    public function resizeImageFile($data)
    {
        $file_path = $data['file_path'];
        $mime_type = $data['mime_type'];
        $width = $data['resize_width'];
        $height = $data['resize_height'];
        $quality = $data['quality'];

        $image_path = UPLOADPATH.$file_path;
        $image = \Config\Services::image();
        $image->withFile($image_path); // 어느 이미지 수정할지 결정
        if ($mime_type == 'image/png') {
            $image->convert(IMAGETYPE_PNG); // png일 경우 투명이미지가 있을수 있으니 따로 리사이징해 저장
        } else {
            $image->convert(IMAGETYPE_JPEG); // 그 외 이미지는 모두 jpg로 가공한다.
        }

        // 이미지의 가로세로 해상도를 구해서 0보다 크지만 지정된 이미지 크기보다 큰 경우 최대 해상도로 고정
        $image_width = $image->getWidth();
        if($image_width < $width) {
            $width = $image_width;
        }
        $image_height = $image->getHeight();
        if($image_height < $height) {
            $height = $image_height;
        }

        if($width == 0 && $height == 0) {
            // 이미지 처리를 하지 않는다
        } else {
            $master_dimension = 'auto';
            // 이미지 크기를 0으로 지정할 경우 비율에 맞추는 이미지로 설정
            if ($width == 0) {
                $master_dimension = 'height';
            } elseif ($height == 0) {
                $master_dimension = 'width';
            }
            $image->resize($width, $height, true, $master_dimension);
        }
        $image->save($image_path, $quality); // 이미지를 저장할때 퀄리티 조정은 반드시 한다. CI프레임워크의 기본값은 90
        $image_width = $image->getWidth();
        $image_height = $image->getHeight();

        // 파일에 대한 저장 용량 얻기
        $file = new \CodeIgniter\Files\File($image_path);
        $file_size = $file->getSize();

        $proc_result = array();
        $proc_result['file_size'] = $file_size;
        $proc_result['image_width'] = $image_width;
        $proc_result['image_height'] = $image_height;

        return $proc_result;
    }

    // 파일 정보 DB에 저장
    public function insertFileInfo($data)
    {
        $file_name_org = $data['file_name_org'];
        $file_directory = $data['file_directory'];
        $file_name_uploaded = $data['file_name_uploaded'];
        $file_size = $data['file_size'];
        $file_ext = $data['file_ext'];
        $image_width = $data['image_width'];
        $image_height = $data['image_height'];
        $mime_type = $data['mime_type'];
        $category = $data['category'];
        $file_id = $data['file_id'];

        $member_id = getUserSessionInfo('member_id') ?? 'guest'; // 세션의 정보중 아이디를 갖고 옵니다.

        $today = date('YmdHis');

        $result = true;
        $message = '입력이 잘 되었습니다';

        $db = $this->db;
        $db->transStart();
        $builder = $db->table('file');
        $builder->set('file_id', $file_id);
        $builder->set('file_name_org', $file_name_org);
        $builder->set('file_directory', $file_directory);
        $builder->set('file_name_uploaded', $file_name_uploaded);
        $builder->set('file_size', $file_size);
        $builder->set('file_ext', $file_ext);
        $builder->set('image_width', $image_width);
        $builder->set('image_height', $image_height);
        $builder->set('mime_type', $mime_type);
        $builder->set('category', $category);
        $builder->set('del_yn', 'N');
        $builder->set('ins_id', $member_id);
        $builder->set('ins_date', $today);
        $builder->set('upd_id', $member_id);
        $builder->set('upd_date', $today);
        $result = $builder->insert();

        if ($result === false) {
            $db->transRollback();
            $result = false;
            $message = '파일정보 입력에 실패하였습니다.';
        } else {
            $db->transComplete();
            $result = true;
            $message = '파일정보 입력이 완료되었습니다.';
        }

        $model_result = array();
        $model_result['result'] = $result;
        $model_result['message'] = $message;
        $model_result['file_id'] = $file_id;
        $model_result['file_name_org'] = $file_name_org;

        return $model_result;
    }

    public function getUploadInfo($data)
    {
        $config_model = new ConfigModel();

        $result = true;
        $message = '파일 수량 점검이 완료되었습니다.';

        $user_file = $data['user_file'];
        $file_list = $data['file_list'] ?? array();

        $upload_size = $user_file->getSize();

        $model_result = $config_model->getConfigInfo($data);
        $config = $model_result['info'];

        $file_upload_size_limit = $config->file_upload_size_limit;
        $file_upload_size_total = $config->file_upload_size_total;

        // 전체 업로드 제한 용량 체크
        if (count($file_list) == 0 || $file_upload_size_total == 0) {
            $result = true;
            $message = '';
            $total_file_size = 0;
        } else {
            $db = $this->db;
            $builder = $db->table('file');
            $builder->select('sum(file_size) as sum_file_size');
            $builder->where('del_yn', 'N');
            $builder->whereIn('file_id', $file_list);
            $info = $builder->get()->getRow();

            $sum_file_size = $info->sum_file_size;
            $total_file_size = $sum_file_size+$upload_size;
            $data['upload_size'] = $total_file_size;
            $data['limit_size'] = $file_upload_size_total;
            $model_result = $this->checkFileSize($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
            if($result == false) {
                $result = false;
                $message = '총 파일은 '.$file_upload_size_total.'MB 보다 더 올릴 수 없습니다';
            }
        }

        if ($result == true) {
            // 개별 파일의 크기 확인
            $data['limit_size'] = $file_upload_size_limit;
            $data['upload_size'] = $upload_size;
            $model_result = $this->checkFileSize($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
            if($result == false) {
                $result = false;
                $message = '파일이 '.$file_upload_size_limit.'MB 보다 큽니다';
            }
        }

        if ($result == true) {
            // 파일 갯수 체크
            $file_cnt = $config->file_cnt;
            $upload_cnt = count($file_list)+1;
            if ($upload_cnt > $file_cnt && $file_cnt > 0) {
                $result = false;
                $message = '파일은 '.$file_cnt.'개 보다 많이 올릴 수 없습니다.';
            }
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

    // base64로 인코딩된 이미지를 파일로 저장
    public function saveBase64ImageFile($data)
    {
        $security_model = new SecurityModel();

        $result = true;
        $message = '파일 업로드가 완료되었습니다.';

        $base64_image = str_replace('data:image/png;base64,', '', $data['base64_image']);
        $file_ext = 'png';
        $upload_date_path = date('Y/m'); // 업로드 디렉토리는 연/월로 생성
        $random_name = $security_model->getRandomString(5, 32).'.'.$file_ext; // 랜덤네임 생성

        // 디렉토리 생성
        $full_upload_path = UPLOADPATH.$upload_date_path;
        if (!is_dir($full_upload_path)) {
            mkdir($full_upload_path, 0777, true);
        }

        // base64 디코딩
        $image_data = base64_decode($base64_image);

        // 파일 저장
        $full_file_path = $full_upload_path.'/'.$random_name;
        file_put_contents($full_file_path, $image_data);

        $file_size = filesize($full_file_path);
        $file_id = $security_model->getRandomString(4, 32); // 보안을 위한 랜덤문자 생성

        // 이미지의 가로세로 해상도 구하기
        $image = \Config\Services::image();
        $image->withFile($full_file_path);
        $image_width = $image->getWidth();
        $image_height = $image->getHeight();

        $file_info = array();
        $file_info['file_name_org'] = 'signature_'.$random_name;
        $file_info['file_directory'] = $upload_date_path;
        $file_info['file_name_uploaded'] = $random_name;
        $file_info['file_size'] = $file_size;
        $file_info['file_ext'] = $file_ext;
        $file_info['image_width'] = $image_width;
        $file_info['image_height'] = $image_height;
        $file_info['mime_type'] = 'image/png';
        $file_info['category'] = 'image';
        $file_info['file_id'] = $file_id;
        $file_info['file_path'] = $full_file_path;
        $file_info['file_size'] = $file_size;

        $model_result = $this->insertFileInfo($file_info); // DB에 파일 정보 저장

        return $file_info;
    }

    public function getFileInfo($file_id)
    {
        $file_id = (string)$file_id;
        $db = $this->db;
        $builder = $db->table("file");
        $builder->where("file_id", $file_id);
        $builder->where("del_yn", "N");
        $db_info = $builder->get()->getFirstRow(); // 쿼리 실행

        if($db_info == null) {
            // do nothing
        } else {
            $db_info->file_path = UPLOADPATH.$db_info->file_directory."/".$db_info->file_name_uploaded;
            $db_info->file_size_kb = number_format($db_info->file_size / 1024, 2);
            $db_info->image_width_txt = number_format($db_info->image_width);
            $db_info->image_height_txt = number_format($db_info->image_height);
        }

        return $db_info;
    }

    public function getRawFile($response, $file_path)
    {
        try {
            $raw_file = $response->download($file_path, null); // 파일 다운로드
        } catch (\Throwable $t) {
            $raw_file = $response->download("resource/community/image/no_image.png", null); // 기본 이미지로 다운로드
        }

        return $raw_file;
    }

}
