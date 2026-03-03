<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ConfigModel;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/home/main');
    }

    public function main()
    {
        $config_model = new ConfigModel();

        $model_result = $config_model->getConfigInfo();
        $config_info = $model_result['info'];

        $proc_result = array();
        $proc_result["html_meta"] = create_meta($config_info->title, $config_info->description);

        return uview('/user/home/main', $proc_result);
    }

}
