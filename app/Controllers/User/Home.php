<?php

namespace App\Controllers\Usr;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->to('/home/main');
    }

    public function main()
    {
        return uview('user/home/main');
    }

}
