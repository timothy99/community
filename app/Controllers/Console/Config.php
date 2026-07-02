<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;
use App\Models\Console\ConfigModel;
use App\Models\Console\IpModel;
use App\Models\Console\LanguageModel;
use App\Models\Console\SettingsModel;

class Config extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/config/environment/list');
    }

    public function environment()
    {
        return redirect()->to('/csl/config/environment/list');
    }

    public function environmentList()
    {
        $config_model = new ConfigModel();
        $language_model = new LanguageModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $model_result = $language_model->getLanguageList();
        $list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['list'] = $list;

        return aview('console/config/environment_list', $proc_result);
    }

    public function environmentEdit(string $languageCode)
    {
        $config_model = new ConfigModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $model_result = $config_model->getEnvironmentLanguageInfo($languageCode);
        if ($model_result['result'] === false) {
            return redirect()->to('/csl/config/environment/list');
        }
        $language_info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['language_info'] = $language_info;

        return aview('console/config/environment_edit', $proc_result);
    }

    public function security()
    {
        return $this->renderConfigPage('console/config/security');
    }

    public function email()
    {
        return $this->renderConfigPage('console/config/email');
    }

    public function environmentUpdate()
    {
        $company_logo = $this->request->getPost('company_logo_hidden', FILTER_SANITIZE_SPECIAL_CHARS);
        $program_ver = $this->request->getPost('program_ver', FILTER_SANITIZE_SPECIAL_CHARS);

        $result = true;
        $message = '정상처리 되었습니다.';

        $data = array();
        $data['company_logo'] = $company_logo;
        $data['program_ver'] = $program_ver;

        return $this->updateEnvironmentCommon($data, $result, $message, '/csl/config/environment/list');
    }

    public function environmentLanguageUpdate()
    {
        $language_code = $this->request->getPost('language_code', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = $this->request->getPost('description', FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = $this->request->getPost('phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $fax = $this->request->getPost('fax', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->getPost('email', FILTER_SANITIZE_SPECIAL_CHARS);
        $work_hour = $this->request->getPost('work_hour', FILTER_SANITIZE_SPECIAL_CHARS);
        $post_code = $this->request->getPost('post_code', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr1 = $this->request->getPost('addr1', FILTER_SANITIZE_SPECIAL_CHARS);
        $addr2 = $this->request->getPost('addr2', FILTER_SANITIZE_SPECIAL_CHARS);
        $biz_no = $this->request->getPost('biz_no', FILTER_SANITIZE_SPECIAL_CHARS);

        $result = true;
        $message = '정상처리 되었습니다.';

        if ($language_code == null || $language_code == '') { $result = false; $message = '언어 코드가 누락되었습니다.'; }
        if ($title == null) { $result = false; $message = '회사명을 입력해주세요.'; }
        if ($phone == null) { $result = false; $message = '전화번호를 입력해주세요.'; }
        if ($email == null) { $result = false; $message = '이메일을 입력해주세요.'; }
        if ($work_hour == null) { $result = false; $message = '업무시간을 입력해주세요.'; }
        if ($post_code == null) { $result = false; $message = '우편번호를 입력해주세요.'; }
        if ($biz_no == null) { $result = false; $message = '사업자등록번호를 입력해주세요.'; }

        $data = array();
        $data['title'] = $title;
        $data['description'] = $description;
        $data['phone'] = $phone;
        $data['fax'] = $fax;
        $data['email'] = $email;
        $data['work_hour'] = $work_hour;
        $data['post_code'] = $post_code;
        $data['addr1'] = $addr1;
        $data['addr2'] = $addr2;
        $data['biz_no'] = $biz_no;

        return $this->updateEnvironmentLanguage($language_code, $data, $result, $message, '/csl/config/environment/edit/' . $language_code);
    }

    public function securityUpdate()
    {
        $construction_yn = $this->request->getPost('construction_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $login_required_yn = $this->request->getPost('login_required_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $admin_two_factor_yn = $this->request->getPost('admin_two_factor_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $admin_ip_check_yn = $this->request->getPost('admin_ip_check_yn', FILTER_SANITIZE_SPECIAL_CHARS);

        $result = true;
        $message = '정상처리 되었습니다.';

        if ($construction_yn == null) { $result = false; $message = '공사중 여부를 입력해주세요.'; }
        if ($login_required_yn == null) { $result = false; $message = '로그인 필수 여부를 입력해주세요.'; }
        if ($admin_two_factor_yn == null) { $result = false; $message = '관리자 접속시 이메일 인증 여부를 입력해주세요.'; }
        if ($admin_ip_check_yn == null) { $result = false; $message = '관리자 접속시 IP확인 여부를 입력해주세요.'; }

        if ($admin_two_factor_yn === 'Y') {
            $config_model = new ConfigModel();
            $config_info = $config_model->getConfigInfo()['info'];
            if (empty($config_info->smtp_pass)) {
                $result = false;
                $message = '관리자 접속시 이메일 인증 기능 활성화를 위해 이메일 설정의 SMTP정보를 먼저 입력해주세요.';
            }
        }

        $data = array();
        $data['construction_yn'] = $construction_yn;
        $data['login_required_yn'] = $login_required_yn;
        $data['admin_two_factor_yn'] = $admin_two_factor_yn;
        $data['admin_ip_check_yn'] = $admin_ip_check_yn;

        return $this->updateConfig($data, $result, $message, '/csl/config/security');
    }

    public function emailUpdate()
    {
        $smtp_yn = $this->request->getPost('smtp_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $manager_email = $this->request->getPost('manager_email', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_mail = $this->request->getPost('smtp_mail', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_user = $this->request->getPost('smtp_user', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_pass = $this->request->getPost('smtp_pass', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_port = $this->request->getPost('smtp_port', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_name = $this->request->getPost('smtp_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $smtp_host = $this->request->getPost('smtp_host', FILTER_SANITIZE_SPECIAL_CHARS);

        $result = true;
        $message = '정상처리 되었습니다.';

        if ($smtp_yn == null) { $result = false; $message = '메일발송기능 사용여부를 입력해주세요.'; }
        if ($manager_email == null) { $result = false; $message = '담당자 이메일을 입력해주세요.'; }

        $data = array();
        $data['smtp_yn'] = $smtp_yn;
        $data['manager_email'] = $manager_email;
        $data['smtp_mail'] = $smtp_mail;
        $data['smtp_user'] = $smtp_user;
        $data['smtp_pass'] = $smtp_pass;
        $data['smtp_port'] = $smtp_port;
        $data['smtp_name'] = $smtp_name;
        $data['smtp_host'] = $smtp_host;

        return $this->updateConfig($data, $result, $message, '/csl/config/email');
    }

    public function configIpList()
    {
        $ip_model = new IpModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'ip';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $ip_model->getIpList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        $search_arr = array();
        $search_arr['search_condition'] = $search_condition;
        $search_arr['search_text'] = $search_text;
        $search_arr['search_page'] = $search_page;
        $search_arr['search_rows'] = $search_rows;
        $search_arr['cnt'] = $cnt;
        $paging_info = getPagingInfo($search_arr);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;
        $proc_result['paging_info'] = $paging_info;
        $proc_result['data'] = $data;

        return aview('/console/ip/list', $proc_result);
    }

    public function configIpWrite()
    {
        $result = true;
        $message = '정상';

        $info = new \stdClass();
        $info->ip_idx = 0;
        $info->ip = '';
        $info->memo = '';
        $info->environment_mode = 'production';

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/ip/edit', $proc_result);
    }

    public function configIpUpdate()
    {
        $ip_model = new IpModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $ip_idx = $this->request->getPost('ip_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $ip = $this->request->getPost('ip', FILTER_SANITIZE_SPECIAL_CHARS);
        $environment_mode = $this->request->getPost('environment_mode', FILTER_SANITIZE_SPECIAL_CHARS);
        $memo = $this->request->getPost('memo', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['ip_idx'] = $ip_idx;
        $data['ip'] = $ip;
        $data['environment_mode'] = $environment_mode;
        $data['memo'] = $memo;

        if ($result == true) {
            if ($ip_idx == 0) {
                $model_result = $ip_model->procIpInsert($data);
                $ip_idx = $model_result['insert_id'];
            } else {
                $model_result = $ip_model->procIpUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/config/ip/view/'.$ip_idx;
        $proc_result['ip_idx'] = $ip_idx;

        return $this->response->setJSON($proc_result);
    }

    public function configIpView(int $ip_idx)
    {
        $ip_model = new IpModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['ip_idx'] = $ip_idx;

        $model_result = $ip_model->getIpInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/ip/view', $proc_result);
    }

    public function configIpEdit(int $ip_idx)
    {
        $ip_model = new IpModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['ip_idx'] = $ip_idx;

        $model_result = $ip_model->getIpInfo($data);
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview('console/ip/edit', $proc_result);
    }

    public function configIpDelete()
    {
        $result = true;
        $message = '정상처리 되었습니다.';

        $ip_model = new IpModel();

        $ip_idx = $this->request->getPost('ip_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['ip_idx'] = $ip_idx;

        $model_result = $ip_model->procIpDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/config/ip/list';

        return $this->response->setJSON($proc_result);
    }

    public function configBoardList()
    {
        $settings_model = new SettingsModel();

        $search_page = $this->request->getGet('search_page') ?? 1;
        $search_rows = $this->request->getGet('search_rows') ?? 10;
        $search_text = $this->request->getGet('search_text', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
        $search_condition = $this->request->getGet('search_condition', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'title';

        $data = array();
        $data['search_page'] = $search_page;
        $data['search_rows'] = $search_rows;
        $data['search_text'] = $search_text;
        $data['search_condition'] = $search_condition;

        $model_result = $settings_model->getBoardList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];
        $cnt = $model_result['cnt'];

        $search_arr = array();
        $search_arr['search_condition'] = $search_condition;
        $search_arr['search_text'] = $search_text;
        $search_arr['search_page'] = $search_page;
        $search_arr['search_rows'] = $search_rows;
        $search_arr['cnt'] = $cnt;
        $paging_info = getPagingInfo($search_arr);

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['cnt'] = $cnt;
        $proc_result['paging_info'] = $paging_info;
        $proc_result['data'] = $data;

        return aview('/console/settings/board/list', $proc_result);
    }

    public function configBoardWrite()
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $random_board_number = $settings_model->getBoardNumber();

        $info = new \stdClass();
        $info->board_config_idx = 0;
        $info->board_id = 'board'.$random_board_number;
        $info->type = 'board';
        $info->category = '';
        $info->category_yn = 'N';
        $info->user_write = 'N';
        $info->comment_write = 'N';
        $info->secret_comment_yn = 'N';
        $info->title = '게시판'.$random_board_number;
        $info->meta_title = '';
        $info->base_rows = 10;
        $info->reg_date_yn = 'N';
        $info->file_yn = 'N';
        $info->file_cnt = '10';
        $info->file_upload_size_limit = '10';
        $info->file_upload_size_total = '100';
        $info->write_point = 5;
        $info->comment_point = 1;
        $info->form_style = '';
        $info->form_style_yn = 'N';
        $info->hit_edit_yn = 'N';
        $info->hit_yn = 'N';
        $info->heart_yn = 'N';
        $info->pdf_yn = 'N';
        $info->youtube_yn = 'N';
        $info->url_link_yn = 'N';
        $info->main_image_yn = 'N';
        $info->new_days = 3;

        $authority_list = array();

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['authority_list'] = $authority_list;

        return aview('console/settings/board/edit', $proc_result);
    }

    public function configBoardUpdate()
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상처리 되었습니다.';

        $board_config_idx = $this->request->getPost('board_config_idx', FILTER_SANITIZE_SPECIAL_CHARS);
        $board_id = $this->request->getPost('board_id', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = $this->request->getPost('title', FILTER_SANITIZE_SPECIAL_CHARS);
        $meta_title = $this->request->getPost('meta_title', FILTER_SANITIZE_SPECIAL_CHARS);
        $type = $this->request->getPost('type', FILTER_SANITIZE_SPECIAL_CHARS);
        $base_rows = $this->request->getPost('base_rows', FILTER_SANITIZE_SPECIAL_CHARS);
        $category_yn = $this->request->getPost('category_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $category = $this->request->getPost('category', FILTER_SANITIZE_SPECIAL_CHARS);
        $user_write = $this->request->getPost('user_write', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment_write = $this->request->getPost('comment_write', FILTER_SANITIZE_SPECIAL_CHARS);
        $secret_comment_yn = $this->request->getPost('secret_comment_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $hit_yn = $this->request->getPost('hit_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $heart_yn = $this->request->getPost('heart_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $pdf_yn = $this->request->getPost('pdf_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $youtube_yn = $this->request->getPost('youtube_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $url_link_yn = $this->request->getPost('url_link_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $reg_date_yn = $this->request->getPost('reg_date_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $hit_edit_yn = $this->request->getPost('hit_edit_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $file_cnt = $this->request->getPost('file_cnt', FILTER_SANITIZE_SPECIAL_CHARS);
        $file_upload_size_limit = $this->request->getPost('file_upload_size_limit', FILTER_SANITIZE_SPECIAL_CHARS);
        $file_upload_size_total = $this->request->getPost('file_upload_size_total', FILTER_SANITIZE_SPECIAL_CHARS);
        $write_point = $this->request->getPost('write_point', FILTER_SANITIZE_SPECIAL_CHARS);
        $comment_point = $this->request->getPost('comment_point', FILTER_SANITIZE_SPECIAL_CHARS);
        $form_style_yn = $this->request->getPost('form_style_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $form_style = sanitizeHtml($this->request->getPost('summer_code'));
        $main_image_yn = $this->request->getPost('main_image_yn', FILTER_SANITIZE_SPECIAL_CHARS);
        $new_days = $this->request->getPost('new_days', FILTER_SANITIZE_SPECIAL_CHARS);

        $list_authority = $this->request->getPost('list_authority');
        $view_authority = $this->request->getPost('view_authority');
        $write_authority = $this->request->getPost('write_authority');

        $authority_arr = array();
        $authority_arr['list'] = $list_authority;
        $authority_arr['view'] = $view_authority;
        $authority_arr['write'] = $write_authority;

        if ($board_id == null || $board_id == '') { $result = false; $message = '게시판 아이디를 입력해주세요.'; }
        if ($title == null || $title == '') { $result = false; $message = '게시판 제목을 입력해주세요.'; }
        if ($base_rows == null || $base_rows == '' || $base_rows < 1) { $result = false; $message = '화면에 보여줄 줄 수를 입력해주세요.'; }
        if ($file_cnt == null || $file_cnt == '' || $file_cnt < 0) { $result = false; $message = '최대 첨부파일 수를 입력해주세요.'; }
        if ($file_upload_size_limit == null || $file_upload_size_limit == '' || $file_upload_size_limit < 1) { $result = false; $message = '첨부파일 1개당 업로드 용량 제한을 입력해주세요.'; }
        if ($file_upload_size_total == null || $file_upload_size_total == '' || $file_upload_size_total < 1) { $result = false; $message = '첨부파일 전체 업로드 용량 제한을 입력해주세요.'; }
        if ($write_point == null || $write_point == '' || $write_point < 0) { $result = false; $message = '글쓰기 포인트를 입력해주세요.'; }
        if ($category_yn == null || $category_yn == '') { $result = false; $message = '카테고리 사용여부를 선택해주세요.'; }
        if ($user_write == null || $user_write == '') { $result = false; $message = '사용자 글쓰기 가능 여부를 선택해주세요.'; }
        if ($comment_write == null || $comment_write == '') { $result = false; $message = '사용자 댓글쓰기 가능 여부를 선택해주세요.'; }
        if ($secret_comment_yn == null || $secret_comment_yn == '') { $result = false; $message = '비밀 댓글 기능 사용 여부를 선택해주세요.'; }
        if ($reg_date_yn == null || $reg_date_yn == '') { $result = false; $message = '입력일 수정 기능 사용 여부를 선택해주세요.'; }
        if ($hit_edit_yn == null || $hit_edit_yn == '') { $result = false; $message = '조회수 수정 기능 사용 여부를 선택해주세요.'; }
        if ($hit_yn == null || $hit_yn == '') { $result = false; $message = '조회수 노출 기능 사용 여부를 선택해주세요.'; }
        if ($heart_yn == null || $heart_yn == '') { $result = false; $message = '공감 기능 사용 여부를 선택해주세요.'; }
        if ($pdf_yn == null || $pdf_yn == '') { $result = false; $message = 'PDF 보기 기능 사용 여부를 선택해주세요.'; }
        if ($youtube_yn == null || $youtube_yn == '') { $result = false; $message = '유튜브 기능 사용 여부를 선택해주세요.'; }
        if ($form_style_yn == null || $form_style_yn == '') { $result = false; $message = '폼 스타일 사용 여부를 선택해주세요.'; }
        if ($result && !preg_match('/^[a-z0-9]+$/', $board_id)) { $result = false; $message = '게시판 아이디는 영문 소문자와 숫자만 사용 가능합니다.'; }
        if ($list_authority == null || $list_authority == '') { $result = false; $message = '목록 권한을 선택해주세요.'; }
        if ($view_authority == null || $view_authority == '') { $result = false; $message = '상세 권한을 선택해주세요.'; }
        if ($write_authority == null || $write_authority == '') { $result = false; $message = '쓰기 권한을 선택해주세요.'; }
        if ($url_link_yn == null || $url_link_yn == '') { $result = false; $message = '링크 기능 사용 여부를 선택해주세요.'; }
        if ($main_image_yn == null || $main_image_yn == '') { $result = false; $message = '대표 이미지 사용 여부를 선택해주세요.'; }
        if ($new_days == null || $new_days == '') { $result = false; $message = 'new 표시 기간을 입력해주세요.'; }

        $meta_title = str_replace('&#62;', '>', $meta_title);
        $meta_title = $meta_title.' ';

        $data = array();
        $data['board_config_idx'] = $board_config_idx;
        $data['board_id'] = $board_id;
        $data['title'] = $title;
        $data['meta_title'] = $meta_title;
        $data['type'] = $type;
        $data['base_rows'] = $base_rows;
        $data['category_yn'] = $category_yn;
        $data['category'] = $category;
        $data['user_write'] = $user_write;
        $data['comment_write'] = $comment_write;
        $data['secret_comment_yn'] = $secret_comment_yn;
        $data['hit_yn'] = $hit_yn;
        $data['heart_yn'] = $heart_yn;
        $data['pdf_yn'] = $pdf_yn;
        $data['youtube_yn'] = $youtube_yn;
        $data['reg_date_yn'] = $reg_date_yn;
        $data['hit_edit_yn'] = $hit_edit_yn;
        $data['file_cnt'] = $file_cnt;
        $data['file_upload_size_limit'] = $file_upload_size_limit;
        $data['file_upload_size_total'] = $file_upload_size_total;
        $data['write_point'] = $write_point;
        $data['comment_point'] = $comment_point;
        $data['form_style_yn'] = $form_style_yn;
        $data['form_style'] = $form_style;
        $data['authority_arr'] = $authority_arr;
        $data['url_link_yn'] = $url_link_yn;
        $data['main_image_yn'] = $main_image_yn;
        $data['new_days'] = $new_days;

        if ($result == true) {
            if ($board_config_idx == 0) {
                $model_result = $settings_model->procBoardInsert($data);
                $board_config_idx = $model_result['insert_id'];
            } else {
                $model_result = $settings_model->procBoardUpdate($data);
            }

            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/config/board/list';
        $proc_result['board_config_idx'] = $board_config_idx;

        return $this->response->setJSON($proc_result);
    }

    public function configBoardEdit(string $board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;

        $model_result = $settings_model->getBoardInfo($data);
        $info = $model_result['info'];

        if ($result == false || $model_result['info'] === null) {
            redirect_alert($message, '/csl/config/board/list');
            exit;
        }

        $model_result = $settings_model->getBoardAdminList($data);
        $admin_list = $model_result['list'];

        $model_result = $settings_model->getBoardAuthorityList($data);
        $authority_list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['admin_list'] = $admin_list;
        $proc_result['authority_list'] = $authority_list;

        return aview('console/settings/board/edit', $proc_result);
    }

    public function configBoardDelete()
    {
        $result = true;
        $message = '정상처리 되었습니다.';

        $settings_model = new SettingsModel();

        $board_config_idx = $this->request->getPost('board_config_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        $data = array();
        $data['board_config_idx'] = $board_config_idx;

        $model_result = $settings_model->procBoardDelete($data);
        $result = $model_result['result'];
        $message = $model_result['message'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = '/csl/config/board/list';

        return $this->response->setJSON($proc_result);
    }

    public function configBoardView(string $board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;

        $model_result = $settings_model->getBoardInfo($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        if ($result == false || $model_result['info'] === null) {
            redirect_alert($message, '/csl/config/board/list');
            exit;
        }

        $model_result = $settings_model->getBoardAdminList($data);
        $admin_list = $model_result['list'];

        $model_result = $settings_model->getBoardAuthorityList($data);
        $authority_list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;
        $proc_result['admin_list'] = $admin_list;
        $proc_result['authority_list'] = $authority_list;

        return aview('console/settings/board/view', $proc_result);
    }

    public function configBoardAdmin(string $board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $data = array();
        $data['board_id'] = $board_id;

        $model_result = $settings_model->getBoardAdminList($data);
        $result = $model_result['result'];
        $message = $model_result['message'];
        $list = $model_result['list'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['list'] = $list;
        $proc_result['board_id'] = $board_id;

        return aview('console/settings/admin/list', $proc_result);
    }

    public function configBoardAdminSearch(string $board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $search_text = $this->request->getPost('search_text', FILTER_SANITIZE_SPECIAL_CHARS);
        $strlen = mb_strlen($search_text);
        if ($strlen < 2) {
            $result = false;
            $message = '검색어는 최소 2자 이상 입력해주세요.';
        }

        if ($result == true) {
            $data = array();
            $data['search_text'] = $search_text;

            $model_result = $settings_model->getMemberList($data);
            $return_html = view('console/settings/admin/search', $model_result);
        } else {
            $return_html = null;
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_html'] = $return_html;

        return $this->response->setJSON($proc_result);
    }

    public function configBoardAdminInsert(string $board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $board_id = $this->request->getPost('board_id', FILTER_SANITIZE_SPECIAL_CHARS) ?? $board_id;
        $member_id = $this->request->getPost('member_id', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($board_id == null || $board_id == '') {
            $result = false;
            $message = '게시판 아이디가 누락되었습니다.';
        }
        if ($member_id == null || $member_id == '') {
            $result = false;
            $message = '회원 아이디가 누락되었습니다.';
        }

        if ($result == true) {
            $data = array();
            $data['board_id'] = $board_id;
            $data['member_id'] = $member_id;

            $model_result = $settings_model->procBoardAdminInsert($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $this->response->setJSON($proc_result);
    }

    public function configBoardAdminDelete(string $board_id)
    {
        $settings_model = new SettingsModel();

        $result = true;
        $message = '정상';

        $board_admin_idx = $this->request->getPost('board_admin_idx', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($board_admin_idx == null || $board_admin_idx == '') {
            $result = false;
            $message = '관리자 아이디가 누락되었습니다.';
        }

        if ($result == true) {
            $data = array();
            $data['board_admin_idx'] = $board_admin_idx;

            $model_result = $settings_model->procBoardAdminDelete($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $this->response->setJSON($proc_result);
    }

    private function renderConfigPage(string $viewPath)
    {
        $config_model = new ConfigModel();

        $result = true;
        $message = '정상';

        $model_result = $config_model->getConfigInfo();
        $result = $model_result['result'];
        $message = $model_result['message'];
        $info = $model_result['info'];

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['info'] = $info;

        return aview($viewPath, $proc_result);
    }

    private function updateConfig(array $data, bool $result, string $message, string $returnUrl)
    {
        $config_model = new ConfigModel();

        if ($result === true) {
            $model_result = $config_model->procConfigUpdate($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = $returnUrl;

        return $this->response->setJSON($proc_result);
    }

    private function updateEnvironmentCommon(array $data, bool $result, string $message, string $returnUrl)
    {
        $config_model = new ConfigModel();

        if ($result === true) {
            $model_result = $config_model->procEnvironmentCommonUpdate($data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = $returnUrl;

        return $this->response->setJSON($proc_result);
    }

    private function updateEnvironmentLanguage(string $languageCode, array $data, bool $result, string $message, string $returnUrl)
    {
        $config_model = new ConfigModel();

        if ($result === true) {
            $model_result = $config_model->procEnvironmentLanguageUpdate($languageCode, $data);
            $result = $model_result['result'];
            $message = $model_result['message'];
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['return_url'] = $returnUrl;

        return $this->response->setJSON($proc_result);
    }

}
