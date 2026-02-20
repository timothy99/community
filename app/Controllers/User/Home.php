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
        log_message('error', 'Home::main() called');
        logMessage("ddd");
        return view('user/home/main');
    }

}
