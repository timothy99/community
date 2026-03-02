<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\Console\FileModel;

class File extends BaseController
{
    public function index()
    {
        return redirect()->to('/');
    }

    // 일반적인 파일 업로드 (이미지인 경우 압축하고 리사이징도 한다.)
    public function uploadGeneral()
    {
        ini_set('memory_limit', '256M'); // 이미지의 압축을 풀었을때 등 최대 가능 용량(파일의 용량과는 다름)

        $file_model = new FileModel();

        $data = array();
        $data['input_file_id'] = (string)$this->request->getPost('file_id'); // 폼 마다 다른 업로드하는 파일 아이디
        $data['user_file'] = $this->request->getFile($data['input_file_id']); // 올린 파일 정보 갖고 오기
        $data['quality'] = 80; // 이미지 저장시 퀄리티. jpeg기준 80이상 권장. 60이상을 입력해야한다.
        $data['resize_width'] = 2000; // 이미지일 경우 지정한 해상도보다 높은 경우 줄인다. 반드시 0 이상을 입력해야한다.
        $data['resize_height'] = 0; // 이미지 해상도를 0으로 지정한 경우 리사이징을 하지 않는다. width도 마찬가지
        $data['limit_size'] = 10; // 업로드 제한 사이즈 메가바이트 단위로 입력. 반드시 0 이상을 입력해야한다.
        $data['allowed_type'] = 'both';

        $proc_result = $file_model->uploadGeneralFile($data); // 파일을 올린다.

        return $this->response->setJSON($proc_result);
    }

    // 일반적인 파일 업로드에 게시판 설정을 읽어와서 파일의 갯수나 저장의 제한을 체크.
    public function uploadBoard()
    {
        ini_set('memory_limit', '256M');

        $file_model = new FileModel();

        $data = array();
        $data['input_file_id'] = (string)$this->request->getPost('file_id'); // 폼 마다 다른 업로드하는 파일 아이디
        $data['user_file'] = $this->request->getFile($data['input_file_id']); // 올린 파일 정보 갖고 오기
        $data['quality'] = 80; // 이미지 저장시 퀄리티. jpeg기준 80이상 권장. 60이상을 입력해야한다.
        $data['resize_width'] = 2000; // 이미지일 경우 지정한 해상도보다 높은 경우 줄인다. 반드시 0 이상을 입력해야한다.
        $data['resize_height'] = 0; // 이미지 해상도를 0으로 지정한 경우 리사이징을 하지 않는다. width도 마찬가지
        $data['limit_size'] = 10; // 업로드 제한 사이즈 메가바이트 단위로 입력. 반드시 0 이상을 입력해야한다.
        $data['board_id'] = $this->request->getPost('board_id'); // 게시판 아이디
        $data['file_list'] = $this->request->getPost('file_list'); // 기존에 업로드 되어 있던 파일 아이디들
        $data['allowed_type'] = 'both';  // 업로드를 허용할 타입을 결정.

        $proc_result = $file_model->uploadBoardFile($data); // 파일을 올린다.

        return $this->response->setJSON($proc_result);
    }

    // 원본파일 그대로 (이미지 압축이나 리사이징도 하지 않는다.)
    public function uploadOriginal()
    {
        ini_set('memory_limit', '256M'); // 이미지의 압축을 풀었을때 등 최대 가능 용량(파일의 용량과는 다름)

        $file_model = new FileModel();

        $data = array();
        $data['input_file_id'] = (string)$this->request->getPost('file_id'); // 폼 마다 다른 업로드하는 파일 아이디
        $data['user_file'] = $this->request->getFile($data['input_file_id']); // 올린 파일 정보 갖고 오기
        $data['limit_size'] = 10; // 업로드 제한 사이즈 메가바이트 단위로 입력. 반드시 0 이상을 입력해야한다.
        $data['allowed_type'] = 'both';

        $proc_result = $file_model->uploadOriginalFile($data); // 파일을 올린다.

        return $this->response->setJSON($proc_result);
    }

    // 이미지 파일만 올릴수 있음. (압축과 리사이징을 한다.)
    public function uploadImage()
    {
        ini_set('memory_limit', '256M'); // 이미지의 압축을 풀었을때 등 최대 가능 용량(파일의 용량과는 다름)

        $file_model = new FileModel();

        $data = array();
        $data['file_id'] = (string)$this->request->getPost('file_id'); // 폼 마다 다른 업로드하는 파일 아이디
        $data['user_file'] = $this->request->getFile($data['file_id']); // 올린 파일 정보 갖고 오기
        $data['quality'] = 80; // 이미지 저장시 퀄리티. jpeg기준 80이상 권장. 60이상을 입력해야한다.
        $data['resize_width'] = 2000; // 이미지일 경우 지정한 해상도보다 높은 경우 줄인다. 반드시 0 이상을 입력해야한다.
        $data['resize_height'] = 0; // 이미지 해상도를 0으로 지정한 경우 리사이징을 하지 않는다. width도 마찬가지
        $data['limit_size'] = 10; // 업로드 제한 사이즈 메가바이트 단위로 입력. 반드시 0 이상을 입력해야한다.
        $data['allowed_type'] = 'image';

        $proc_result = $file_model->uploadImageFile($data); // 파일을 올린다.

        return $this->response->setJSON($proc_result);
    }

    // 이미지 파일만 올릴수 있음. (드롭존용)
    public function uploadDropzone()
    {
        ini_set('memory_limit', '256M'); // 이미지의 압축을 풀었을때 등 최대 가능 용량(파일의 용량과는 다름)

        $file_model = new FileModel();

        $data = array();
        // $data['file_id'] = (string)$this->request->getPost('file_id'); // 폼 마다 다른 업로드하는 파일 아이디
        $data['user_file'] = $this->request->getFile('file'); // 올린 파일 정보 갖고 오기
        $data['quality'] = 80; // 이미지 저장시 퀄리티. jpeg기준 80이상 권장. 60이상을 입력해야한다.
        $data['resize_width'] = 2000; // 이미지일 경우 지정한 해상도보다 높은 경우 줄인다. 반드시 0 이상을 입력해야한다.
        $data['resize_height'] = 0; // 이미지 해상도를 0으로 지정한 경우 리사이징을 하지 않는다. width도 마찬가지
        $data['limit_size'] = 10; // 업로드 제한 사이즈 메가바이트 단위로 입력. 반드시 0 이상을 입력해야한다.
        $data['allowed_type'] = 'both';

        $proc_result = $file_model->uploadOriginalFile($data); // 파일을 올린다.

        return $this->response->setJSON($proc_result);
    }

    // 파일 보기 모드
    public function view($file_id)
    {
        $file_model = new FileModel();

        $file_info = new \stdClass();
        if ($file_id == null) { // 파일 아이디가 없다는건 파일 정보가 애초에 없다는 말이니
            $file_info->file_path = null; // 공백 no image 파일이 다운로드 되도록 처리한다.
        } else {
            $file_info = $file_model->getFileInfo($file_id); // 파일소유권 확인 및 파일 정보 확인
        }

        $raw_file = $file_model->getRawFile($this->response, $file_info->file_path); // 파일 다운로드

        return $raw_file;
    }

    // 파일 다운로드 모드. 이미지임에도 불구하고 다운로드가 필요한 경우가 있음.
    public function download()
    {
        $file_model = new FileModel();

        $file_id = $this->request->getUri()->getSegment(3);

        $file_info = $file_model->getFileInfo($file_id); // 파일소유권 확인 및 파일 정보 확인
        $file_download = $this->response->download($file_info->file_path, null)->setFileName($file_info->file_name_org); // 파일 다운로드

        return $file_download;
    }

}
