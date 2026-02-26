<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/home/main');
    }

    public function main()
    {
        $proc_result = array();
        $proc_result["html_meta"] = create_meta("홈 > 메인");

        return uview('/user/home/main', $proc_result);
    }

}
