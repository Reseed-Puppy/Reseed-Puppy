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

use EasyAdmin\upload\FileBase;

/**
 * 本地上传
 * Class Local
 * @package EasyAdmin\upload\driver
 */
class Local extends FileBase
{

    /**
     * 实现上传方法
     * @return array
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
            $completeFileUrl = request()->domain() . '/' . str_replace(DIRECTORY_SEPARATOR, '/',  $this->completeFilePath);
            $this->dbSave($completeFileUrl);
            return [
                'save' => true,
                'msg'  => '上传成功',
                'url'  => $completeFileUrl,
            ];
        }
    }

}