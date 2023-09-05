<?php

namespace app\admin\controller\download;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="download_config")
 */
class Config extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\DownloadConfig();
        
        $this->assign('getTypeList', $this->model->getTypeList());

        $this->assign('getSkiphashList', $this->model->getSkiphashList());

        $this->assign('getIsactionList', $this->model->getIsactionList());

    }

    
}