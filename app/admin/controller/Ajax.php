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

namespace app\admin\controller;

use app\admin\model\SystemUploadfile;
use app\common\controller\AdminController;
use app\common\service\MenuService;
use app\common\service\UploadService;
use think\db\Query;
use think\exception\ValidateException;
use think\facade\Cache;

class Ajax extends AdminController
{

    /**
     * 初始化后台接口地址
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function initAdmin()
    {
        $cacheData = Cache::get('initAdmin_' . session('admin.id'));
        if (!empty($cacheData)) {
            return json($cacheData);
        }
        $menuService = new MenuService(session('admin.id'));
        $data = [
            'logoInfo' => [
                'title' => sysconfig('site', 'logo_title'),
                'image' => sysconfig('site', 'logo_image'),
                'href' => __url('index/index'),
            ],
            'homeInfo' => $menuService->getHomeInfo(),
            'menuInfo' => $menuService->getMenuTree(),
        ];
        Cache::tag('initAdmin')->set('initAdmin_' . session('admin.id'), $data);
        return json($data);
    }

    /**
     * 清理缓存接口
     */
    public function clearCache()
    {
        Cache::clear();
        $this->success('清理缓存成功');
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        $this->checkPostRequest();
        $file = $this->request->file('file');
        empty($file) && $this->error('文件不能为空');
        $uploadService = new UploadService($file, $this->request->post('upload_type'));
        try {
            $upload = $uploadService->upload();
        } catch (ValidateException | \Exception $e) {
            $this->error($e->getMessage());
        }

        $upload['save'] == true ? $this->success($upload['msg'], ['url' => $upload['url']]) : $this->error($upload['msg']);
    }

    /**
     * 上传图片至编辑器
     * @return \think\response\Json
     */
    public function uploadEditor()
    {
        $this->checkPostRequest();
        $file = $this->request->file('upload');
        if (empty($file)) {
            return json([
                'uploaded' => 0,
                'error' => [
                    'message' => '文件不能为空',
                ],
            ]);
        }

        $uploadService = new UploadService($file, $this->request->post('upload_type'));
        try {
            $upload = $uploadService->upload();
        } catch (ValidateException | \Exception $e) {
            return json([
                'uploaded' => 0,
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ]);
        }

        $result = $upload['save'] == true ?
            [
                'uploaded' => 1,
                'error' => [
                    'message' => '上传成功',
                    'number' => 201,
                ],
                'fileName' => '',
                'url' => $upload['url'],
            ] : [
                'uploaded' => 0,
                'error' => [
                    'message' => $upload['msg'],
                ],
            ];
        return json($result);
    }

    /**
     * 获取上传文件列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUploadFiles()
    {
        $get = $this->request->get();
        $page = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 10;
        $title = isset($get['title']) && !empty($get['title']) ? $get['title'] : null;
        $ext = isset($get['ext']) && !empty($get['ext']) ? $get['ext'] : null;
        $this->model = new SystemUploadfile();
        $count = $this->model
            ->where(function (Query $query) use ($title, $ext) {
                !empty($title) && $query->where('original_name', 'like', "%{$title}%");
                !empty($ext) && $query->where('file_ext', 'in', str_replace('|', ',', $ext));
            })
            ->count();
        $list = $this->model
            ->where(function (Query $query) use ($title, $ext) {
                !empty($title) && $query->where('original_name', 'like', "%{$title}%");
                !empty($ext) && $query->where('file_ext', 'in', str_replace('|', ',', $ext));
            })
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
        $data = [
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $list,
        ];
        return json($data);
    }

}