<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ConfigModel;
use App\Models\User\MainModel;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/home/main');
    }

    public function main()
    {
        $config_model = new ConfigModel();
        $main_model = new MainModel();

        $model_result = $config_model->getConfigInfo();
        $config_info = $model_result['info'];

        $model_result = $main_model->getSlideList();
        $slide_list = $model_result['list'];

        $agent = $this->request->getUserAgent();
        if ($agent->isMobile()) {
            $is_mobile = true;
        } else {
            $is_mobile = false;
        }

        $model_result = $main_model->getPopupList($is_mobile);
        $popup_list = $model_result['list'];

        $data = array();
        $data["board_id"] = "faq";
        $data["limit"] = 5;
        $model_result = $main_model->getRecentContentsList($data);
        $faq_list = $model_result['list'];

        $data["board_id"] = "notice";
        $model_result = $main_model->getRecentContentsList($data);
        $notice_list = $model_result['list'];

        $data["board_id"] = "gallery";
        $data["limit"] = 3;
        $model_result = $main_model->getRecentContentsList($data);
        $gallery_list = $model_result['list'];

        $proc_result = array();
        $proc_result['slide_list'] = $slide_list;
        $proc_result['popup_list'] = $popup_list;
        $proc_result['faq_list'] = $faq_list;
        $proc_result['notice_list'] = $notice_list;
        $proc_result['gallery_list'] = $gallery_list;
        $proc_result['config_info'] = $config_info;
        $proc_result["html_meta"] = create_meta($config_info->title, $config_info->description);

        return uview('/user/home/main', $proc_result);
    }

    // 정해진 시간동안 팝업이 차단되게 하는 컨트롤러
    public function popupBlock()
    {
        $popup_idx = $this->request->getPost("popup_idx", FILTER_SANITIZE_NUMBER_INT);
        $disabled_hours = $this->request->getPost("disabled_hours", FILTER_SANITIZE_NUMBER_INT);
        $expire_date = date("YmdHis", strtotime("+".$disabled_hours." hours"));

        $result = true;
        $message = "정상";

        $data = (object)array();
        $data->popup_idx = $popup_idx;
        $data->disabled_hours = $disabled_hours;
        $data->expire_date = $expire_date;

        $layer_closed = getUserSessionInfo("layer_closed");
        array_push($layer_closed, $data);
        setUserSessionInfo("layer_closed", $layer_closed);

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["popup_idx"] = $popup_idx;

        return $this->response->setJSON($proc_result);
    }

}
