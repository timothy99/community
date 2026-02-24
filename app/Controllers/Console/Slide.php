<?php

namespace App\Controllers\Console;

use App\Controllers\BaseController;

class Slide extends BaseController
{
    public function index()
    {
        return redirect()->to('/csl/slide/list');
    }

    public function list()
    {
        return aview('/console/slide/list');
    }

}