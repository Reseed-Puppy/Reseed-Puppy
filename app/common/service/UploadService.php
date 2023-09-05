<?php

namespace app\common\service;

use EasyAdmin\upload\Uploadfile;
use think\File;
use think\Validate;

class UploadService
{
    protected $uploadConfig;
    protected $uploadType;
    protected $file;

    public function __construct(File $file, $uploadType = 'local')
    {
        $this->file = $file;
        $this->uploadConfig = sysconfig('upload');
        $this->uploadType = $uploadType ?: $this->uploadConfig['upload_type'];
    }

    public function upload()
    {
        $this->check();
        return Uploadfile::instance()
            ->setUploadType($this->uploadType)
            ->setUploadConfig($this->uploadConfig)
            ->setFile($this->file)
            ->save();
    }

    protected function check()
    {
        $data = [
            'upload_type' => $this->uploadType,
            'file' => $this->file,
        ];
        list($mainType, $subtype) = explode('/', $this->file->getOriginalMime());
        switch (strtolower($mainType)) {
            case 'image':
                $uploadAllowSize = $this->uploadConfig['upload_allow_image_size'];
                break;
            case 'audio':
                $uploadAllowSize = $this->uploadConfig['upload_allow_audio_size'];
                break;
            case 'video':
                $uploadAllowSize = $this->uploadConfig['upload_allow_video_size'];
                break;
            default:
                $uploadAllowSize = $this->uploadConfig['upload_allow_size'];
        }
        $rule = [
            'upload_type|存储方式' => "in:{$this->uploadConfig['upload_allow_type']}",
            'file|文件' => ["file", "fileExt:{$this->uploadConfig['upload_allow_ext']}", "fileSize:{$uploadAllowSize}"],
        ];
        $validate = new Validate();
        $validate->rule($rule)->failException(true)->check($data);
    }
}