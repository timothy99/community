<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ContentsModel;

class Contents extends BaseController
{
    public function index()
    {
        return redirect()->to("/home/main");
    }

    public function view()
    {
        $contents_model = new ContentsModel();

        $result = true;
        $message = "정상처리";

        $contents_id = $this->request->getUri()->getSegment(2);

        $data = array();
        $data["contents_id"] = $contents_id;
        $model_result = $contents_model->getContentsInfo($data);
        $result = $model_result["result"];
        $message = $model_result["message"];
        $info = $model_result["info"];

        $proc_result = array();
        $proc_result["result"] = $result;
        $proc_result["message"] = $message;
        $proc_result["html_meta"] = create_meta($info->meta_title);
        $proc_result["contents"] = $info->contents;

        $view_file = "/user/contents/view";

        return uview($view_file, $proc_result);
    }

}