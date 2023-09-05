<?php


namespace Fairy;

use Fairy\exception\HttpException;
use Workerman\Connection\TcpConnection;
use Workerman\Crontab\Crontab;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
use Workerman\Timer;
use Workerman\Worker;

/**
 * 注意：定时器开始、暂停、重起 都是在下一分钟开始执行
 * Class CrontabService
 * @package Fairy
 */
class HttpCrontab
{
    /**
     * worker 实例
     * @var Worker
     */
    private $worker;

    /**
     * 进程名
     * @var string
     */
    private $workerName = "Workerman Http Crontab";

    /**
     * 数据库配置
     * @var array
     */
    private $dbConfig = [
        'hostname' => '127.0.0.1',
        'hostport' => '3306',
        'username' => 'root',
        'password' => 'root',
        'database' => 'test',
        'charset' => 'utf8mb4',
        'prefix' => '',
    ];


    /**
     * 定时任务表
     * @var string
     */
    private $taskTable = 'crontab_task';

    /**
     * 定时任务日志表
     * @var string
     */
    private $taskLogTable = 'crontab_task_log';

    /**
     * 定时任务锁表
     * @var string
     */
    private $taskLockTable = 'crontab_task_lock';

    /**
     * 数据库句柄
     * @var Db
     */
    private $db;

    /**
     * 任务进程池
     * @var Crontab[] array
     */
    private $crontabPool = [];

    /**
     * 调试模式
     * @var bool
     */
    private $debug = false;

    /**
     * 错误信息
     * @var
     */
    private $errorMsg = [];

    /**
     * 安全秘钥
     * @var string
     */
    private $safeKey;

    /**
     * 路由对象
     * @var Route
     */
    private $route;

    /**
     * 最低PHP版本
     * @var string
     */
    const LESS_PHP_VERSION = '7.0.0';

    //请求接口地址
    const INDEX_PATH = '/crontab/index';
    const ADD_PATH = '/crontab/add';
    const EDIT_PATH = '/crontab/edit';
    const READ_PATH = '/crontab/read';
    const MODIFY_PATH = '/crontab/modify';
    const START_PATH = '/crontab/start';
    const RELOAD_PATH = '/crontab/reload';
    const DELETE_PATH = '/crontab/delete';
    const FLOW_PATH = '/crontab/flow';
    const POOL_PATH = '/crontab/pool';
    const PING_PATH = '/crontab/ping';

    /**
     * @param string $socketName 不填写表示不监听任何端口,格式为 <协议>://<监听地址> 协议支持 tcp、udp、unix、http、websocket、text
     * @param array $contextOption socket 上下文选项 http://php.net/manual/zh/context.socket.php
     */
    public function __construct($socketName = '', array $contextOption = [])
    {
        $this->checkEnv();
        $this->initRoute();
        $this->initWorker($socketName, $contextOption);
    }

    /**
     * 检测运行环境
     */
    private function checkEnv()
    {
        $errorMsg = [];
        Tool::isFunctionDisabled('exec') && $errorMsg[] = 'exec函数被禁用';
        Tool::versionCompare(self::LESS_PHP_VERSION, '<') && $errorMsg[] = 'PHP版本必须≥' . self::LESS_PHP_VERSION;
        if (Tool::isLinux()) {
            $checkExt = ["pcntl", "posix"];
            foreach ($checkExt as $ext) {
                !Tool::isExtensionLoaded($ext) && $errorMsg[] = $ext . '扩展没有安装';
            }
            $checkFunc = [
                "stream_socket_server",
                "stream_socket_client",
                "pcntl_signal_dispatch",
                "pcntl_signal",
                "pcntl_alarm",
                "pcntl_fork",
                "pcntl_wait",
                "posix_getuid",
                "posix_getpwuid",
                "posix_kill",
                "posix_setsid",
                "posix_getpid",
                "posix_getpwnam",
                "posix_getgrnam",
                "posix_getgid",
                "posix_setgid",
                "posix_initgroups",
                "posix_setuid",
                "posix_isatty",
            ];
            foreach ($checkFunc as $func) {
                Tool::isFunctionDisabled($func) && $errorMsg[] = $func . '函数被禁用';
            }
        }

        if (!empty($errorMsg)) {
            $this->errorMsg = array_merge($this->errorMsg, $errorMsg);
        }
    }

    /**
     * 初始化路由
     */
    private function initRoute()
    {
        $this->route = new Route();
        $this->registerRoute();
    }

    /**
     * 注册路由
     */
    private function registerRoute()
    {
        $this->route
            ->addRoute('GET', self::INDEX_PATH, [$this, 'crontabIndex'])
            ->addRoute('POST', self::ADD_PATH, [$this, 'crontabAdd'])
            ->addRoute('GET', self::READ_PATH, [$this, 'crontabRead'])
            ->addRoute('POST', self::EDIT_PATH, [$this, 'crontabEdit'])
            ->addRoute('POST', self::MODIFY_PATH, [$this, 'crontabModify'])
            ->addRoute('POST', self::DELETE_PATH, [$this, 'crontabDelete'])
            ->addRoute('POST', self::START_PATH, [$this, 'crontabStart'])
            ->addRoute('POST', self::RELOAD_PATH, [$this, 'crontabReload'])
            ->addRoute('GET', self::FLOW_PATH, [$this, 'crontabFlow'])
            ->addRoute('GET', self::POOL_PATH, [$this, 'crontabPool'])
            ->addRoute('GET', self::PING_PATH, [$this, 'crontabPing'])
            ->register();
    }

    /**
     * 初始化 worker
     * @param string $socketName
     * @param array $contextOption
     */
    private function initWorker($socketName = '', $contextOption = [])
    {
        $socketName = $socketName ?: 'http://127.0.0.1:2345';
        $this->worker = new Worker($socketName, $contextOption);
        $this->worker->name = $this->workerName;
        if (isset($contextOption['ssl'])) {
            $this->worker->transport = 'ssl';//设置当前Worker实例所使用的传输层协议，目前只支持3种(tcp、udp、ssl)。默认为tcp。
        }
        $this->registerCallback();
    }

    /**
     * 注册子进程回调函数
     */
    private function registerCallback()
    {
        $this->worker->onWorkerStart = [$this, 'onWorkerStart'];
        $this->worker->onWorkerReload = [$this, 'onWorkerReload'];
        $this->worker->onWorkerStop = [$this, 'onWorkerStop'];
        $this->worker->onConnect = [$this, 'onConnect'];
        $this->worker->onMessage = [$this, 'onMessage'];
        $this->worker->onClose = [$this, 'onClose'];
        $this->worker->onBufferFull = [$this, 'onBufferFull'];
        $this->worker->onBufferDrain = [$this, 'onBufferDrain'];
        $this->worker->onError = [$this, 'onError'];
    }

    /**
     * 启用安全模式
     * @return $this
     */
    public function setSafeKey($key)
    {
        $this->safeKey = $key;

        return $this;
    }

    /**
     * 是否调试模式
     * @return $this
     */
    public function setDebug()
    {
        $this->debug = true;

        return $this;
    }

    /**
     * 设置当前Worker实例的名称,方便运行status命令时识别进程
     * 默认为none
     * @param string $name
     * @return $this
     */
    public function setName($name = "Workerman Http Crontab")
    {
        $this->worker->name = $name;

        return $this;
    }

    /**
     * 设置当前Worker实例以哪个用户运行
     * 此属性只有当前用户为root时才能生效，建议$user设置权限较低的用户
     * 默认以当前用户运行
     * windows系统不支持此特性
     * @param string $user
     * @return $this
     */
    public function setUser($user = "root")
    {
        $this->worker->user = $user;

        return $this;
    }

    /**
     * 以daemon(守护进程)方式运行
     * windows系统不支持此特性
     * @return $this
     */
    public function setDaemon()
    {
        Worker::$daemonize = true;

        return $this;
    }

    /**
     * 设置所有连接的默认应用层发送缓冲区大小。默认1M。可以动态设置
     * @param float|int $size
     * @return $this
     */
    public function setMaxSendBufferSize($size = 1024 * 1024)
    {
        TcpConnection::$defaultMaxSendBufferSize = $size;

        return $this;
    }

    /**
     * 设置每个连接接收的数据包。默认10M。超包视为非法数据，连接会断开
     * @param float|int $size
     * @return $this
     */
    public function setMaxPackageSize($size = 10 * 1024 * 1024)
    {
        TcpConnection::$defaultMaxPackageSize = $size;

        return $this;
    }

    /**
     * 指定日志文件
     * 默认为位于workerman下的 workerman.log
     * 日志文件中仅仅记录workerman自身相关启动停止等日志，不包含任何业务日志
     * @param string $path
     * @return $this
     */
    public function setLogFile($path = "./workerman.log")
    {
        Worker::$logFile = $path;

        return $this;
    }

    /**
     * 指定打印输出文件
     * 以守护进程方式(-d启动)运行时，所有向终端的输出(echo var_dump等)都会被重定向到 stdoutFile指定的文件中
     * 默认为/dev/null,也就是在守护模式时默认丢弃所有输出
     * windows系统不支持此特性
     * @param string $path
     * @return $this
     */
    public function setStdoutFile($path = "./workerman_debug.log")
    {
        Worker::$stdoutFile = $path;

        return $this;
    }

    /**
     * 设置数据库链接信息
     * @param array $config
     * @return $this
     */
    public function setDbConfig(array $config = [])
    {
        $this->dbConfig = array_merge($this->dbConfig, array_change_key_case($config));

        return $this;
    }

    /**
     * 设置任务表名
     * @param string $taskTable
     * @return HttpCrontab
     */
    public function setTaskTable(string $taskTable)
    {
        $this->taskTable = $taskTable;
        return $this;
    }

    /**
     * 设置任务日志表名
     * @param string $taskLogTable
     * @return HttpCrontab
     */
    public function setTaskLogTable(string $taskLogTable)
    {
        $this->taskLogTable = $taskLogTable;
        return $this;
    }

    /**
     * 设置任务锁表名
     * @param string $taskLockTable
     * @return HttpCrontab
     */
    public function setTaskLockTable(string $taskLockTable)
    {
        $this->taskLockTable = $taskLockTable;
        return $this;
    }

    /**
     * 设置Worker子进程启动时的回调函数，每个子进程启动时都会执行
     * @param Worker $worker
     */
    public function onWorkerStart($worker)
    {
        $this->db = new Db($this->dbConfig, $this->taskTable, $this->taskLogTable, $this->taskLockTable);
        $this->db->checkTaskTables();
        $this->crontabInit();
        //定时检查日志分表
        Timer::add(1, [$this->db, 'checkTaskLogTable']);
    }

    /**
     * @param Worker $worker
     */
    public function onWorkerStop($worker)
    {

    }

    /**
     * 设置Worker收到reload信号后执行的回调
     * 如果在收到reload信号后只想让子进程执行onWorkerReload，不想退出，可以在初始化Worker实例时设置对应的Worker实例的reloadable属性为false
     * @param Worker $worker
     */
    public function onWorkerReload($worker)
    {

    }

    /**
     * 当客户端与Workerman建立连接时(TCP三次握手完成后)触发的回调函数
     * 每个连接只会触发一次onConnect回调
     * 此时客户端还没有发来任何数据
     * 由于udp是无连接的，所以当使用udp时不会触发onConnect回调，也不会触发onClose回调
     * @param TcpConnection $connection
     */
    public function onConnect($connection)
    {

    }

    /**
     * 当客户端连接与Workerman断开时触发的回调函数
     * 不管连接是如何断开的，只要断开就会触发onClose
     * 每个连接只会触发一次onClose
     * 由于udp是无连接的，所以当使用udp时不会触发onConnect回调，也不会触发onClose回调
     * @param TcpConnection $connection
     */
    public function onClose($connection)
    {

    }

    /**
     * 当客户端通过连接发来数据时(Workerman收到数据时)触发的回调函数
     * @param TcpConnection $connection
     * @param $data
     */
    public function onMessage($connection, $request)
    {
        if ($request instanceof Request) {
            if (!is_null($this->safeKey) && $request->header('key') !== $this->safeKey) {
                $connection->send($this->response('', 'Connection Not Allowed', 403));
            } else {
                try {
                    $routeInfo = $this->route->dispatch($request->method(), $request->path());
                    $connection->send($this->response(call_user_func($routeInfo[1], $request)));
                } catch (HttpException $e) {
                    $connection->send($this->response('', $e->getMessage(), $e->getStatusCode()));
                }
            }
        }
    }

    /**
     * 缓冲区满则会触发onBufferFull回调
     * 每个连接都有一个单独的应用层发送缓冲区，如果客户端接收速度小于服务端发送速度，数据会在应用层缓冲区暂存
     * 只要发送缓冲区还没满，哪怕只有一个字节的空间，调用Connection::send($A)肯定会把$A放入发送缓冲区,
     * 但是如果已经没有空间了，还继续Connection::send($B)数据，则这次send的$B数据不会放入发送缓冲区，而是被丢弃掉，并触发onError回调
     * @param TcpConnection $connection
     */
    public function onBufferFull($connection)
    {

    }

    /**
     * 在应用层发送缓冲区数据全部发送完毕后触发
     * @param TcpConnection $connection
     */
    public function onBufferDrain($connection)
    {

    }

    /**
     * 客户端的连接上发生错误时触发
     * @param TcpConnection $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {

    }

    /**
     * 初始化定时任务
     * @return bool
     */
    private function crontabInit()
    {
        $ids = $this->db->getTaskIds();

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $this->crontabRun($id);
            }
        }

        return true;
    }

    /**
     * 定时器列表
     * @param Request $request
     * @return array
     */
    private function crontabIndex($request)
    {
        list($page, $limit, $where) = $this->buildTableParames($request->get());
        list($whereStr, $bindValues) = $this->parseWhere($where);

        return $this->db->getTaskList($whereStr, $bindValues, $page, $limit);
    }

    /**
     * 创建定时任务
     * @param Request $request
     * @return bool
     */
    private function crontabAdd($request)
    {
        $data = $request->post();
        $data['create_time'] = $data['update_time'] = time();
        $id = $this->db->insertTask($data);
        $id && $this->crontabRun($id);

        return $id ? true : false;
    }

    /**
     * 读取定时任务
     * @param Request $request
     * @return array|bool
     */
    private function crontabRead($request)
    {
        $row = [];
        if ($id = $request->get('id')) {
            $row = $this->db->getTask($id);
        }
        return $row;
    }

    /**
     * 编辑定时任务
     * @param Request $request
     * @return bool
     */
    private function crontabEdit($request)
    {
        if ($id = $request->get('id')) {
            $post = $request->post();
            $row = $this->db->getTask($id);
            if (empty($row)) {
                return false;
            }

            $rowCount = $this->db->updateTask($id, $post);
            if ($this->db->isTaskEnabled($row['status'])) {
                if ($row['frequency'] !== $post['frequency'] || $row['shell'] !== $post['shell']) {
                    $this->crontabDestroy($id);
                    $this->crontabRun($id);
                }
            }

            return $rowCount ? true : false;
        } else {
            return false;
        }
    }

    /**
     * 修改定时器
     * @param Request $request
     * @return mixed
     */
    private function crontabModify($request)
    {
        $post = $request->post();
        if (in_array($post['field'], ['status', 'sort'])) {
            $row = $this->db->updateTask($post['id'], [$post['field'] => $post['value']]);

            if ($post['field'] === 'status') {
                if ($this->db->isTaskEnabled($post['value'])) {
                    $this->crontabRun($post['id']);
                } else {
                    $this->crontabDestroy($post['id']);
                }
            }

            return $row ? true : false;
        } else {
            return false;
        }
    }

    /**
     * 清除定时任务
     * @param Request $request
     * @return bool|mixed
     */
    private function crontabDelete($request)
    {
        if ($idStr = $request->post('id')) {
            $ids = explode(',', $idStr);

            foreach ($ids as $id) {
                $this->crontabDestroy($id);
            }
            $rows = $this->db->deleteTask($idStr);

            return $rows ? true : false;
        }

        return true;
    }

    /**
     * 立即执行一次定时任务
     * @param Request $request
     * @return bool
     */
    private function crontabStart(Request $request)
    {
        $ids = explode(',', $request->post('id'));

        foreach ($ids as $id) {
            $row = $this->db->getTask($id);
            $this->crontabRunNow($id);
        }

        return true;
    }

    /**
     * 重启定时任务
     * @param Request $request
     * @return bool
     */
    private function crontabReload(Request $request)
    {
        $ids = explode(',', $request->post('id'));

        foreach ($ids as $id) {
            $row = $this->db->getTask($id);
            if ($row && $this->db->isTaskEnabled($row['status'])) {
                $this->crontabDestroy($id);
                $this->crontabRun($id);
            }
        }

        return true;
    }

    /**
     * 销毁定时器
     * @param $id
     */
    private function crontabDestroy($id)
    {
        if (isset($this->crontabPool[$id])) {
            $this->crontabPool[$id]['crontab']->destroy();
            unset($this->crontabPool[$id]);
        }
    }

    /**
     * 创建定时器
     * 0   1   2   3   4   5
     * |   |   |   |   |   |
     * |   |   |   |   |   +------ day of week (0 - 6) (Sunday=0)
     * |   |   |   |   +------ month (1 - 12)
     * |   |   |   +-------- day of month (1 - 31)
     * |   |   +---------- hour (0 - 23)
     * |   +------------ min (0 - 59)
     * +-------------- sec (0-59)[可省略，如果没有0位,则最小时间粒度是分钟]
     * @param int $id
     */
    private function crontabRun($id)
    {
        $task = $this->db->getTask($id);

        if (!empty($task) && $this->db->isTaskEnabled($task['status'])) {
            $this->crontabPool[$task['id']] = [
                'id' => $task['id'],
                'shell' => $task['shell'],
                'frequency' => $task['frequency'],
                'remark' => $task['remark'],
                'create_time' => date('Y-m-d H:i:s'),
                'crontab' => new Crontab($task['frequency'], function () use (&$task) {
                    $shell = trim($task['shell']);
                    $this->debug && $this->writeln('执行定时器任务#' . $task['id'] . ' ' . $task['frequency'] . ' ' . $shell);
                    $sid = $task['id'];
                    //防止重复执行
                    if (!$this->db->checkTaskLock($sid)) {
                        //加锁
                        $this->db->taskLock($sid);

                        $time = time();
                        $startTime = microtime(true);
                        exec($shell, $output, $code);
                        $endTime = microtime(true);

                        $task['running_times'] += 1;
                        $this->db->updateTask($task['id'], [
                            'running_times' => $task['running_times'],
                            'last_running_time' => $time,
                        ]);

                        $this->db->insertTaskLog($task['id'], [
                            'command' => $shell,
                            'output' => join(PHP_EOL, $output),
                            'return_var' => $code,
                            'running_time' => round($endTime - $startTime, 6),
                            'create_time' => $time,
                            'update_time' => $time,
                        ]);

                        //解锁
                        $this->db->taskUnlock($sid);
                    }
                })
            ];
        }
    }

    private function crontabRunNow($id)
    {
        $task = $this->db->getTask($id);
        $shell = trim($task['shell']);
        $this->debug && $this->writeln('执行定时器任务#' . $task['id'] . ' ' . $task['frequency'] . ' ' . $shell);
        $sid = $task['id'];
        $time = time();
        $startTime = microtime(true);
        exec($shell, $output, $code);
        $endTime = microtime(true);
        $task['running_times'] += 1;
        $this->db->updateTask($task['id'], [
            'running_times' => $task['running_times'],
            'last_running_time' => $time,
        ]);

        $this->db->insertTaskLog($task['id'], [
            'command' => $shell,
            'output' => join(PHP_EOL, $output),
            'return_var' => $code,
            'running_time' => round($endTime - $startTime, 6),
            'create_time' => $time,
            'update_time' => $time,
        ]); 
    }

    /**
     * 定时器池
     * @return array
     */
    private function crontabPool()
    {
        $data = [];
        foreach ($this->crontabPool as $row) {
            unset($row['crontab']);
            $data[] = $row;
        }

        return $data;
    }

    /**
     * 心跳
     * @return string
     */
    private function crontabPing()
    {
        return 'pong';
    }

    /**
     * 执行日志
     * @param Request $request
     * @return array
     */
    private function crontabFlow($request)
    {
        list($page, $limit, $where, $excludeFields) = $this->buildTableParames($request->get(), ['month']);
        $request->get('sid') && $where[] = ['sid', '=', $request->get('sid')];
        list($whereStr, $bindValues) = $this->parseWhere($where);

        $suffix = $excludeFields['month'] ?? '';

        return $this->db->getTaskLogList($suffix, $whereStr, $bindValues, $page, $limit);
    }

    /**
     * 输出日志
     * @param $msg
     * @param bool $ok
     */
    private function writeln($msg, $ok = true)
    {
        echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . ($ok ? " [Ok] " : " [Fail] ") . PHP_EOL;
    }


    private function response($data = '', $msg = '信息调用成功！', $code = 200)
    {
        return new Response($code, [
            'Content-Type' => 'application/json; charset=utf-8',
        ], json_encode(['code' => $code, 'data' => $data, 'msg' => $msg]));
    }

    /**
     * 构建请求参数
     * @param array $get
     * @param array $excludeFields 忽略构建搜索的字段
     * @return array
     */
    private function buildTableParames($get, $excludeFields = [])
    {
        $page = isset($get['page']) && !empty($get['page']) ? (int)$get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? (int)$get['limit'] : 15;
        $filters = isset($get['filter']) && !empty($get['filter']) ? $get['filter'] : '{}';
        $ops = isset($get['op']) && !empty($get['op']) ? $get['op'] : '{}';
        // json转数组
        $filters = json_decode($filters, true);
        $ops = json_decode($ops, true);
        $where = [];
        $excludes = [];

        foreach ($filters as $key => $val) {
            if (in_array($key, $excludeFields)) {
                $excludes[$key] = $val;
                continue;
            }
            $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';

            switch (strtolower($op)) {
                case '=':
                    $where[] = [$key, '=', $val];
                    break;
                case '%*%':
                    $where[] = [$key, 'LIKE', "%{$val}%"];
                    break;
                case '*%':
                    $where[] = [$key, 'LIKE', "{$val}%"];
                    break;
                case '%*':
                    $where[] = [$key, 'LIKE', "%{$val}"];
                    break;
                case 'range':
                    list($beginTime, $endTime) = explode(' - ', $val);
                    $where[] = [$key, '>=', strtotime($beginTime)];
                    $where[] = [$key, '<=', strtotime($endTime)];
                    break;
                case 'in':
                    $where[] = [$key, 'IN', $val];
                    break;
                default:
                    $where[] = [$key, $op, "%{$val}"];
            }
        }

        return [$page, $limit, $where, $excludes];
    }

    /**
     * 解析列表where条件
     * @param $where
     * @return array
     */
    private function parseWhere($where)
    {
        if (!empty($where)) {
            $whereStr = '';
            $bindValues = [];
            foreach ($where as $index => $item) {
                if ($item[0] === 'create_time') {
                    $whereStr .= $item[0] . ' ' . $item[1] . ' :' . $item[0] . $index . ' AND ';
                    $bindValues[$item[0] . $index] = $item[2];
                } elseif ($item[1] === 'IN') {
                    //@todo workerman/mysql包对in查询感觉有问题 临时用如下方式进行转化处理
                    $whereStr .= '(';
                    foreach (explode(',', $item[2]) as $k => $v) {
                        $whereStr .= $item[0] . ' = :' . $item[0] . $k . ' OR ';
                        $bindValues[$item[0] . $k] = $v;
                    }
                    $whereStr = rtrim($whereStr, 'OR ');
                    $whereStr .= ') AND ';
                } else {
                    $whereStr .= $item[0] . ' ' . $item[1] . ' :' . $item[0] . ' AND ';
                    $bindValues[$item[0]] = $item[2];
                }
            }
        } else {
            $whereStr = '1 = 1';
            $bindValues = [];
        }

        $whereStr = rtrim($whereStr, 'AND ');

        return [$whereStr, $bindValues];
    }

    /**
     * 运行所有Worker实例
     * Worker::runAll()执行后将永久阻塞
     * Worker::runAll()调用前运行的代码都是在主进程运行的，onXXX回调运行的代码都属于子进程
     * windows版本的workerman不支持在同一个文件中实例化多个Worker
     * windows版本的workerman需要将多个Worker实例初始化放在不同的文件中
     */
    public function run()
    {
        if (empty($this->errorMsg)) {
            $this->writeln("启动系统任务");
            Worker::runAll();
        } else {
            foreach ($this->errorMsg as $v) {
                $this->writeln($v, false);
            }
        }
    }
}
