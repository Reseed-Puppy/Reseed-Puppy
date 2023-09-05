<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class DownloadConfig extends TimeModel
{

    protected $name = "download_config";

    protected $deleteTime = "delete_time";

    
    
    public function getTypeList()
    {
        return ['1'=>'qb','2'=>'tr',];
    }

    public function getSkiphashList()
    {
        return ['1'=>'否','2'=>'是',];
    }

    public function getIsactionList()
    {
        return ['1'=>'否','2'=>'是',];
    }


}