<?php
namespace app\admin\controller;
use app\common\controller\AdminController;
use think\db\Query;
use think\exception\ValidateException;
use think\facade\Cache;
use think\facade\Http;

class Pyapi extends AdminController
{

    public function index()
    {
        $postData = $_POST;
        if($postData['type'] == 1){
            $url = 'http://127.0.0.1:5000/qbconnect';
        }else{
            $url = 'http://127.0.0.1:5000/trconnect';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);
        if ($httpCode === 200) {
            $responseData = json_decode($response);
            // 处理接口返回的数据
            return json($responseData);
        } else {
            // 处理请求错误
            echo '请求错误';
        }
    }

    /**
     * 清理缓存接口
     */
    public function clearCache()
    {
        Cache::clear();
        $this->success('清理缓存成功');
    }
}