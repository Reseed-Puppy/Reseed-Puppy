<?php

namespace Fairy;

use Fairy\constant\YesNoConstant;
use Workerman\MySQL\Connection;

class Db
{
    /**
     * 数据库句柄
     * @var Connection
     */
    private $db;

    /**
     * 定时任务表
     * @var string
     */
    private $taskTable;

    /**
     * 定时任务日志表
     * @var string
     */
    private $taskLogTable;

    /**
     * 定时任务锁表
     * @var string
     */
    private $taskLockTable;

    /**
     * 定时任务日志表后缀 按月分表
     * @var string|null
     */
    private $taskLogTableSuffix;

    /**
     * 当前定时任务日志表
     * @var string|null
     */
    private $currentTaskLogTable;

    /**
     * 数据库配置
     * @var array
     */
    private $dbConfig;

    /**
     * @param array $config
     * @param $taskTable
     * @param $taskLogTable
     * @param $taskLockTable
     */
    public function __construct(array $config, $taskTable, $taskLogTable, $taskLockTable)
    {
        $this->dbConfig = $config;
        if ($this->dbConfig['prefix']) {
            $this->taskTable = $this->dbConfig['prefix'] . $taskTable;
            $this->taskLogTable = $this->dbConfig['prefix'] . $taskLogTable;
            $this->taskLockTable = $this->dbConfig['prefix'] . $taskLockTable;
        } else {
            $this->taskTable = $taskTable;
            $this->taskLogTable = $taskLogTable;
            $this->taskLockTable = $taskLockTable;
        }

        $this->db = new Connection(
            $this->dbConfig['hostname'],
            $this->dbConfig['hostport'],
            $this->dbConfig['username'],
            $this->dbConfig['password'],
            $this->dbConfig['database'],
            $this->dbConfig['charset']
        );
    }

    /**
     * 获取定时任务id
     * @return array
     */
    public function getTaskIds()
    {
        return $this->db
            ->select('id')
            ->from($this->taskTable)
            ->where("status= :status")
            ->bindValues(['status' => YesNoConstant::YES])
            ->orderByDESC(['sort'])
            ->column();
    }

    /**
     * 获取任务信息
     * @param $id
     * @return array
     */
    public function getTask($id)
    {
        return $this->db
            ->select('*')
            ->from($this->taskTable)
            ->where('id= :id')
            ->bindValues(['id' => $id])
            ->row();
    }

    /**
     * 获取任务列表
     * @param $whereStr
     * @param $bindValues
     * @param $page
     * @param $limit
     * @return array
     */
    public function getTaskList($whereStr, $bindValues, $page, $limit)
    {
        $list = $this->db
            ->select('*')
            ->from($this->taskTable)
            ->where($whereStr)
            ->orderByDESC(['sort'])
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->bindValues($bindValues)
            ->query();

        $count = $this->db
            ->select('count(id)')
            ->from($this->taskTable)
            ->where($whereStr)
            ->bindValues($bindValues)
            ->single();

        return ['list' => $list, 'count' => $count];
    }

    /**
     * 新增任务
     * @param array $data
     * @return mixed|string|null
     */
    public function insertTask(array $data)
    {
        $data['shell'] = "curl http://127.0.0.1:5000?download_id=".$data['download_id'];
        return $this->db
            ->insert($this->taskTable)
            ->cols($data)
            ->query();
    }

    /**
     * 更新任务信息
     * @param $id
     * @param array $data
     * @return mixed|string|null
     */
    public function updateTask($id, array $data)
    {
        return $this->db
            ->update($this->taskTable)
            ->cols($data)
            ->where('id = :id')
            ->bindValues(['id' => $id])
            ->query();
    }

    /**
     * 删除任务
     * @param $id
     * @return mixed|string|null
     */
    public function deleteTask($id)
    {
        return $this->db
            ->delete($this->taskTable)
            ->where('id in (' . $id . ')')
            ->query();
    }

    /**
     * 任务是否启用
     * @param $status
     * @return bool
     */
    public function isTaskEnabled($status)
    {
        return $status == YesNoConstant::YES;
    }

    /**
     * 获取执行日职列表
     * @param $suffix
     * @param $whereStr
     * @param $bindValues
     * @param $page
     * @param $limit
     * @return array
     */
    public function getTaskLogList($suffix, $whereStr, $bindValues, $page, $limit)
    {
        $tableName = $suffix ? $this->taskLogTable . '_' . str_replace('-', '', $suffix) : $this->currentTaskLogTable;

        if ($this->isTableExist($tableName)) {
            $list = $this->db
                ->select('*')
                ->from($tableName)
                ->where($whereStr)
                ->orderByDESC(['Id'])
                ->limit($limit)
                ->offset(($page - 1) * $limit)
                ->bindValues($bindValues)
                ->query();

            $count = $this->db
                ->select('count(id)')
                ->from($tableName)
                ->where($whereStr)
                ->bindValues($bindValues)
                ->single();
        } else {
            $list = [];
            $count = 0;
        }

        return ['list' => $list, 'count' => $count];
    }

    /**
     * 插入执行日志
     * @param $taskId
     * @param array $data
     * @return mixed|string|null
     */
    public function insertTaskLog($taskId, array $data)
    {
        $data['sid'] = $taskId;
        return $this->db
            ->insert($this->currentTaskLogTable)
            ->cols($data)
            ->query();
    }

    /**
     * 获取任务锁信息
     * @param $taskId
     * @return array
     */
    public function getTaskLock($taskId)
    {
        return $this->db
            ->select('*')
            ->from($this->taskLockTable)
            ->where('sid = :sid')
            ->bindValues(['sid' => $taskId])
            ->row();
    }

    /**
     * 插入任务锁数据
     * @param $taskId
     * @param int $isLock
     * @return mixed|string|null
     */
    public function insertTaskLock($taskId, $isLock = 0)
    {
        $now = time();
        return $this->db
            ->insert($this->taskLockTable)
            ->cols([
                'sid' => $taskId,
                'is_lock' => $isLock,
                'create_time' => $now,
                'update_time' => $now
            ])->query();
    }

    /**
     * 更新任务锁信息
     * @param $taskId
     * @param array $data
     */
    public function updateTaskLock($taskId, array $data)
    {
        return $this->db
            ->update($this->taskLockTable)
            ->cols($data)
            ->where('sid = :sid')
            ->bindValue('sid', $taskId)
            ->query();
    }

    /**
     * 加锁
     * @param $taskId
     * @return bool
     */
    public function taskLock($taskId)
    {
        return $this->updateTaskLock($taskId, ['is_lock' => YesNoConstant::YES, 'update_time' => time()]);
    }

    /**
     * 解锁
     * @param $taskId
     * @return bool
     */
    public function taskUnlock($taskId)
    {
        return $this->updateTaskLock($taskId, ['is_lock' => YesNoConstant::NO, 'update_time' => time()]);
    }

    /**
     * 重置锁
     * @return mixed|string|null
     */
    private function taskLockReset()
    {
        return $this->db
            ->update($this->taskLockTable)
            ->cols(['is_lock' => YesNoConstant::NO, 'update_time' => time()])
            ->query();
    }

    /**
     * 任务是否加锁
     * @param $taskId
     * @return bool
     */
    public function isTaskLocked($isLock)
    {
        return $isLock == 1;
    }

    /**
     * 检查任务锁
     * @param $taskId
     * @return bool
     */
    public function checkTaskLock($taskId)
    {
        $taskLockInfo = $this->getTaskLock($taskId);
        if (!$taskLockInfo) {
            $this->insertTaskLock($taskId);
            return false;
        } else {
            return $this->isTaskLocked($taskLockInfo['is_lock']);
        }
    }

    /**
     * 检测表是否存在
     */
    public function checkTaskTables()
    {
        $date = date('Ym', time());
        if ($date !== $this->taskLogTableSuffix) {
            $this->taskLogTableSuffix = $date;
            $this->currentTaskLogTable = $this->taskLogTable . "_" . $this->taskLogTableSuffix;
            $allTables = $this->getDbTables();
            !in_array($this->taskTable, $allTables) && $this->createTaskTable();
            !in_array($this->currentTaskLogTable, $allTables) && $this->createTaskLogTable();
            if (in_array($this->taskLockTable, $allTables)) {
                $this->taskLockReset();
            } else {
                $this->createTaskLockTable();
            }
        }
    }

    /**
     * 检测执行日志分表
     */
    public function checkTaskLogTable()
    {
        $date = date('Ym', time());
        if ($date !== $this->taskLogTableSuffix) {
            $this->taskLogTableSuffix = $date;
            $this->currentTaskLogTable = $this->taskLogTable . "_" . $this->taskLogTableSuffix;
            if ($this->isTableExist($this->currentTaskLogTable) === false) {
                $this->createTaskLogTable();
            }
        }
    }

    /**
     * 获取数据库表名
     * @return array
     */
    public function getDbTables()
    {
        return $this->db
            ->select('TABLE_NAME')
            ->from('information_schema.TABLES')
            ->where("TABLE_TYPE='BASE TABLE'")
            ->where("TABLE_SCHEMA='" . $this->dbConfig['database'] . "'")
            ->column();
    }

    /**
     * 数据表是否存在
     * @param $tableName
     * @return bool
     */
    public function isTableExist($tableName)
    {
        return $this->db
                ->select('TABLE_NAME')
                ->from('information_schema.TABLES')
                ->where("TABLE_TYPE='BASE TABLE'")
                ->where("TABLE_SCHEMA='" . $this->dbConfig['database'] . "'")
                ->where("TABLE_NAME='" . $tableName . "'")
                ->single() !== false;
    }

    /**
     * 创建定时器任务表
     */
    private function createTaskTable()
    {
        $sql = <<<SQL
 CREATE TABLE IF NOT EXISTS `{$this->taskTable}`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务标题',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '任务类型[0请求url,1执行sql,2执行shell]',
  `frequency` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务频率',
  `shell` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '任务脚本',
  `running_times` int(11) NOT NULL DEFAULT '0' COMMENT '已运行次数',
  `last_running_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近运行时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务备注',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序，越大越前',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '任务状态状态[0:禁用;1启用]',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务表' ROW_FORMAT = DYNAMIC
SQL;

        return $this->db->query($sql);
    }

    /**
     * 定时器任务流水表
     */
    private function createTaskLogTable()
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `{$this->currentTaskLogTable}`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid` int(60) NOT NULL COMMENT '任务id',
  `command` varchar(255) NOT NULL COMMENT '执行命令',
  `output` text NOT NULL COMMENT '执行输出',
  `return_var` tinyint(4) NOT NULL COMMENT '执行返回状态[0成功; 1失败]',
  `running_time` varchar(10) NOT NULL COMMENT '执行所用时间',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sid`(`sid`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务流水表{$this->taskLogTableSuffix}' ROW_FORMAT = DYNAMIC
SQL;

        return $this->db->query($sql);
    }

    /**
     * 定时器任务流水表
     */
    private function createTaskLockTable()
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `{$this->taskLockTable}`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid` int(60) NOT NULL COMMENT '任务id',
  `is_lock` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否锁定(0:否,1是)',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sid`(`sid`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务锁表' ROW_FORMAT = DYNAMIC
SQL;

        return $this->db->query($sql);
    }
}