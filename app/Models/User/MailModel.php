<?php

namespace App\Models\User;

use CodeIgniter\Model;
use Throwable;

class MailModel extends Model
{
    public function getMailConfig()
    {
        $result = true;
        $message = '메일설정 불러오기가 정상적으로 이루어졌습니다.';

        $db = $this->db;
        $builder = $db->table('config');
        $info = $builder->get()->getRow();

        $mail_config = array();
        $mail_config['smtp_host'] = $info->smtp_host;
        $mail_config['smtp_user'] = $info->smtp_user;
        $mail_config['smtp_mail'] = $info->smtp_mail;
        $mail_config['smtp_pass'] = $info->smtp_pass;
        $mail_config['smtp_port'] = $info->smtp_port;
        $mail_config['smtp_name'] = $info->smtp_name;

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;
        $proc_result['mail_config'] = $mail_config;

        return $proc_result;
    }

    public function procMailSend($data, $config)
    {
        $email = \Config\Services::email(); // 이메일 서비스 로드

        $result = true;
        $message = '메일발송이 정상적으로 이루어졌습니다.';

        $receive_email = $data['receive_email'];
        $title = $data['title'];
        $contents = $data['contents'];

        $smtp_host = $config['smtp_host']; // 호스트
        $smtp_user = $config['smtp_user']; // 사용자 정보
        $smtp_pass = $config['smtp_pass']; // 암호
        $smtp_port = $config['smtp_port']; // 포트
        $smtp_name = $config['smtp_name']; // 보내는 사람 이름
        $smtp_mail = $config['smtp_mail']; // 메일주소

        $config['protocol'] = 'smtp';
        $config['SMTPHost'] = $smtp_host; 
        $config['SMTPUser'] = $smtp_user;
        $config['SMTPPass'] = $smtp_pass;
        $config['SMTPPort'] = (int)$smtp_port;
        $config['mailType'] = 'html';

        try {
            $email->initialize($config);
            $email->setFrom($smtp_mail, $smtp_name);
            $email->setTo($receive_email);
            $email->setSubject($title);
            $email->setMessage($contents);
            $result = $email->send();
        } catch (Throwable $t) {
            $result = false;
            $message = '메일발송에 오류가 발생했습니다.';
            logMessage($t->getMessage());
        }

        $proc_result = array();
        $proc_result['result'] = $result;
        $proc_result['message'] = $message;

        return $proc_result;
    }

}
