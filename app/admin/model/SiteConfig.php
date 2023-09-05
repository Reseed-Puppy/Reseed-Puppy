<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class SiteConfig extends TimeModel
{

    protected $name = "site_config";

    protected $deleteTime = "delete_time";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }


}