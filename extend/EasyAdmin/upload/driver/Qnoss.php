<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

namespace EasyAdmin\upload\driver;

use EasyAdmin\upload\driver\qnoss\Oss;
use EasyAdmin\upload\FileBase;

/**
 * 七牛云上传
 * Class Qnoss
 * @package EasyAdmin\upload\driver
 */
class Qnoss extends FileBase
{

    /**
     * 实现上传方法
     * @return array|void
     */
    public function save()
    {
        $hashFile = $this->sha1File();
        if ($hashFile) {
            return [
                'save' => true,
                'msg'  => '上传成功',
                'url'  => $hashFile['url'],
            ];
        } else {
            $this->localSave();
            $upload = Oss::instance($this->uploadConfig)
                ->save($this->completeFilePath, $this->completeFilePath);
            if ($upload['save'] == true) {
                $this->dbSave($upload['url']);
            }
            $this->rmLocalSave();
            return $upload;
        }
    }
}