<?php


namespace app\admin\controller\system;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use Fairy\HttpCrontab;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use think\App;

/**
 * @ControllerAnnotation(title="定时任务管理")
 */
class Crontab extends AdminController
{
    private $baseUri;
    private $safeKey;

    protected $allowModifyFields = [
        'status',
        'sort'
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new \app\admin\model\DownloadConfig();
        $this->assign('getDownloadList', $this->model->select());
        $this->baseUri = env('easyadmin.crontab_base_uri') ?: 'http://127.0.0.1:2345';
        $this->safeKey = env('easyadmin.crontab_safe_key') ?: null;

        $this->assign('typeOptions', [0 => '请求url', 1 => '执行sql', 2 => '执行shell']);
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $response = $this->httpRequest(HttpCrontab::INDEX_PATH . '?' . $this->request->query());
            if(isset($response['data']['list'])){
              foreach($response['data']['list'] as $key => $value){
                $result = $this->model->where('id',$value['download_id'])->find();
                $response['data']['list'][$key]['download_name'] = $result['name'];
              }
            }
            
            $data = [
                'code' => 0,
                'msg' => $response['msg'],
                'count' => $response['ok'] ? $response['data']['count'] : 0,
                'data' => $response['ok'] ? $response['data']['list'] : [],
            ];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [
                'title|标题' => 'require',
                'type|类型' => 'require',
                'frequency|频率' => 'require',
                // 'shell|脚本' => 'require',
            ];
            $this->validate($post, $rule);
            $response = $this->httpRequest(HttpCrontab::ADD_PATH, 'POST', $post);
            $response['ok'] ? $this->success('保存成功') : $this->error($response['msg']);
        }

        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            $response = $this->httpRequest(HttpCrontab::EDIT_PATH . '?' . $this->request->query(), 'POST', $post);
            $response['ok'] ? $this->success('更新成功') : $this->error($response['msg']);
        }

        $response = $this->httpRequest(HttpCrontab::READ_PATH . '?id=' . $id);
        $this->assign('row', $response['data']);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="属性修改")
     */
    public function modify()
    {
        $post = $this->request->post();
        $rule = [
            'id|ID' => 'require',
            'field|字段' => 'require',
            'value|值' => 'require',
        ];
        $this->validate($post, $rule);
        if (!in_array($post['field'], $this->allowModifyFields)) {
            $this->error('该字段不允许修改：' . $post['field']);
        }
        $response = $this->httpRequest(HttpCrontab::MODIFY_PATH, 'POST', $post);
        $response['ok'] ? $this->success('修改成功') : $this->error($response['msg']);
    }

    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        $response = $this->httpRequest(HttpCrontab::DELETE_PATH, 'POST', ['id' => is_array($id) ? join(',', $id) : $id]);
        $response['ok'] ? $this->success('删除成功') : $this->error($response['msg']);
    }
    /**
     * @NodeAnotation(title="启动")
     */
    public function start($id)
    {
        $response = $this->httpRequest(HttpCrontab::START_PATH, 'POST', ['id' => $id]);
        $response['ok'] ? $this->success('执行成功') : $this->error($response['msg']);
    }
    /**
     * @NodeAnotation(title="重启")
     */
    public function reload($id)
    {
        $response = $this->httpRequest(HttpCrontab::RELOAD_PATH, 'POST', ['id' => $id]);
        $response['ok'] ? $this->success('重启成功') : $this->error($response['msg']);
    }

    /**
     * @NodeAnotation(title="日志")
     */
    public function flow()
    {
        $id = $this->request->get('id');
        if ($this->request->isAjax()) {
            $response = $this->httpRequest(HttpCrontab::FLOW_PATH . '?' . $this->request->query());
            $data = [
                'code' => 0,
                'msg' => $response['msg'],
                'count' => $response['ok'] ? $response['data']['count'] : 0,
                'data' => $response['ok'] ? $response['data']['list'] : [],
            ];
            return json($data);
        }

        return $this->fetch('', ['sid' => $id]);
    }

    /**
     * @NodeAnotation(title="心跳")
     */
    public function ping()
    {
        $response = $this->httpRequest(HttpCrontab::PING_PATH);
        return json(['code' => $response['ok'] ? 1 : 0]);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $form
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpRequest($url, $method = 'GET', array $form = [])
    {
        try {
            $client = new Client([
                'base_uri' => $this->baseUri,
                'headers' => [
                    'key' => $this->safeKey
                ]
            ]);
            $response = $client->request($method, $url, ['form_params' => $form]);
            $data = [
                'ok' => true,
                'data' => json_decode($response->getBody()->getContents(), true)['data'],
                'msg' => 'success',
            ];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $msg = json_decode($e->getResponse()->getBody()->getContents(), true)['msg'];
            } else {
                $msg = $e->getMessage();
            }
            $data = [
                'ok' => false,
                'data' => [],
                'msg' => $msg
            ];
        }

        return $data;
    }
}
