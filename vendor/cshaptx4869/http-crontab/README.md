# 接口化秒级定时任务

## 概述

基于 **Workerman** + **MySQL** 的接口化秒级定时任务管理，兼容 Windows 和 Linux 系统。



## 定时器格式说明

```
0   1   2   3   4   5
|   |   |   |   |   |
|   |   |   |   |   +------ day of week (0 - 6) (Sunday=0)
|   |   |   |   +------ month (1 - 12)
|   |   |   +-------- day of month (1 - 31)
|   |   +---------- hour (0 - 23)
|   +------------ min (0 - 59)
+-------------- sec (0-59)[可省略，如果没有0位,则最小时间粒度是分钟]
```



## 简单使用

- **新建 run.php**

```php
<?php

require_once "./vendor/autoload.php";

use Fairy\HttpCrontab;

date_default_timezone_set('PRC');

//数据库配置
//启动脚本后会自行创建所需的数据表
//定时器任务执行日志按月自动分表
$dbConfig = [
    'hostname' => '127.0.0.1',
    'hostport' => '3306',
    'username' => 'root',
    'password' => 'root',
    'database' => 'test',
    'charset' => 'utf8mb4'
];

//启动后默认监听 http://127.0.0.1:2345 
//可在new的时候传递第一个参数改变监听地址
(new HttpCrontab())->setDebug(true)
    ->setName('System Crontab')
    ->setDbConfig($dbConfig)
    ->run();
```

- **启动服务**

![](https://i.loli.net/2021/09/23/dTGRMSjkQZyWIbH.png)



## 集成项目

[**easyadmin** ](https://github.com/cshaptx4869/easyadmin)是基于 ThinkPHP6.1 和 Layui2.7 的快速开发的后台管理系统。

- **启动服务**

![](https://foruda.gitee.com/images/1680915958035390338/0f322a61_5507348.jpeg)

```bash
$ php think crontab -h
Usage:
  crontab [options] [--] <action>

Arguments:
  action                start|stop|restart|reload|status|connections

Options:
  -d, --daemon          Run the http crontab server in daemon mode.
      --name[=NAME]     Crontab name [default: "Crontab Server"]
      --debug           Print log
  -h, --help            Display this help message
  -V, --version         Display this console version
  -q, --quiet           Do not output any message
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```



- **UI操作界面**

![](https://foruda.gitee.com/images/1680915293572426711/2433d945_5507348.jpeg)



 <h1 class="curproject-name"> 定时器接口说明 </h1> 



## PING

<a id=PING> </a>

### 基本信息

**Path：** /crontab/ping

**Method：** GET

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": "pong",
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>


## 修改

<a id=修改> </a>

### 基本信息

**Path：** /crontab/modify

**Method：** POST

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": true,
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

**Headers**

| 参数名称     | 参数值                            | 是否必须 | 示例 | 备注 |
| ------------ | --------------------------------- | -------- | ---- | ---- |
| Content-Type | application/x-www-form-urlencoded | 是       |      |      |

**Body**

| 参数名称 | 参数类型 | 是否必须 | 示例   | 备注                              |
| -------- | -------- | -------- | ------ | --------------------------------- |
| id       | text     | 是       | 1      |                                   |
| field    | text     | 是       | status | 字段[status; sort; remark; title] |
| value    | text     | 是       | 1      | 值                                |



### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>boolean</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>


## 列表

<a id=列表> </a>

### 基本信息

**Path：** /crontab/index

**Method：** GET

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": {
&nbsp;&nbsp;&nbsp; "list": [
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "title": "输出 tp 版本",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "type": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "frequency": "*/3 * * * * *",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "shell": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_times": 3,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "last_running_time": 1625636646,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "remark": "没3秒执行",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sort": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "status": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636609,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636609
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; }
&nbsp;&nbsp;&nbsp; ],
&nbsp;&nbsp;&nbsp; "count": 1
&nbsp; },
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

**Query**

| 参数名称 | 是否必须 | 示例                     | 备注         |
| -------- | -------- | ------------------------ | ------------ |
| page     | 是       | 1                        | 页码         |
| limit    | 是       | 15                       | 每页条数     |
| filter   | 否       | {"title":"输出 tp 版本"} | 检索字段值   |
| op       | 否       | {"title":"%*%"}          | 检索字段操作 |

### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>object</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> list</span></td><td key=1><span>object []</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5><p key=3><span style="font-weight: '700'">item 类型: </span><span>object</span></p></td></tr><tr key=0-1-0-0><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> id</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-1><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> title</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-2><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> type</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-3><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> frequency</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-4><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> shell</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-5><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> running_times</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-6><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> last_running_time</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-7><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> remark</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-8><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> sort</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-9><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> status</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-10><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> create_time</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-11><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> update_time</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-1><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> count</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>


## 删除

<a id=删除> </a>

### 基本信息

**Path：** /crontab/delete

**Method：** POST

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": true,
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

**Headers**

| 参数名称     | 参数值                            | 是否必须 | 示例 | 备注 |
| ------------ | --------------------------------- | -------- | ---- | ---- |
| Content-Type | application/x-www-form-urlencoded | 是       |      |      |

**Body**

| 参数名称 | 参数类型 | 是否必须 | 示例 | 备注 |
| -------- | -------- | -------- | ---- | ---- |
| id       | text     | 是       | 1,2  |      |



### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>boolean</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>


## 定时器池

<a id=定时器池> </a>

### 基本信息

**Path：** /crontab/pool

**Method：** GET

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": [
&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "shell": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "frequency": "*/3 * * * * *",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "remark": "没3秒执行",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": "2021-07-07 13:43:29"
&nbsp;&nbsp;&nbsp; }
&nbsp; ],
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>object []</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5><p key=3><span style="font-weight: '700'">item 类型: </span><span>object</span></p></td></tr><tr key=0-1-0><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> id</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-1><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> shell</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-2><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> frequency</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-3><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> remark</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-4><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> create_time</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>


## 日志

<a id=日志> </a>

### 基本信息

**Path：** /crontab/flow

**Method：** GET

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": {
&nbsp;&nbsp;&nbsp; "list": [
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 12,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.115895",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636673,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636673
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 11,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.104641",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636670,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636670
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 10,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.106585",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636667,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636667
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 9,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.10808",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636664,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636664
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 8,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.107653",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636661,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636661
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 7,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.105938",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636658,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636658
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 6,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.10461",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636655,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636655
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 5,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.109786",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636652,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636652
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 4,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.115853",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636649,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636649
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 3,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.16941",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636646,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636646
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 2,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.109524",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636643,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636643
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; },
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "id": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "sid": 1,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "command": "php think version",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "output": "v6.0.7",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "return_var": 0,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "running_time": "0.108445",
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "create_time": 1625636640,
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "update_time": 1625636640
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; }
&nbsp;&nbsp;&nbsp; ],
&nbsp;&nbsp;&nbsp; "count": 12
&nbsp; },
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

**Query**

| 参数名称 | 是否必须 | 示例        | 备注         |
| -------- | -------- | ----------- | ------------ |
| page     | 是       | 1           | 页码         |
| limit    | 是       | 15          | 每页条数     |
| filter   | 否       | {"sid":"1"} | 检索字段值   |
| op       | 否       | {"sid":"="} | 检索字段操作 |

### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>object</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> list</span></td><td key=1><span>object []</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5><p key=3><span style="font-weight: '700'">item 类型: </span><span>object</span></p></td></tr><tr key=0-1-0-0><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> id</span></td><td key=1><span>number</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-1><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> sid</span></td><td key=1><span>number</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-2><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> command</span></td><td key=1><span>string</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-3><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> output</span></td><td key=1><span>string</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-4><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> return_var</span></td><td key=1><span>number</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-5><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> running_time</span></td><td key=1><span>string</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-6><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> create_time</span></td><td key=1><span>number</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-0-7><td key=0><span style="padding-left: 40px"><span style="color: #8c8a8a">├─</span> update_time</span></td><td key=1><span>number</span></td><td key=2>必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1-1><td key=0><span style="padding-left: 20px"><span style="color: #8c8a8a">├─</span> count</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>


## 添加

<a id=添加> </a>

### 基本信息

**Path：** /crontab/add

**Method：** POST

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": true,
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

**Headers**

| 参数名称     | 参数值                            | 是否必须 | 示例 | 备注 |
| ------------ | --------------------------------- | -------- | ---- | ---- |
| Content-Type | application/x-www-form-urlencoded | 是       |      |      |

**Body**

| 参数名称  | 参数类型 | 是否必须 | 示例              | 备注                                     |
| --------- | -------- | -------- | ----------------- | ---------------------------------------- |
| title     | text     | 是       | 输出 tp 版本      | 任务标题                                 |
| type      | text     | 是       | 0                 | 任务类型[0请求url; 1执行sql; 2执行shell] |
| frequency | text     | 是       | */3 * * * * *     | 任务频率                                 |
| shell     | text     | 是       | php think version | 任务脚本                                 |
| remark    | text     | 是       | 没3秒执行         | 备注                                     |
| sort      | text     | 是       | 0                 | 排序                                     |
| status    | text     | 是       | 1                 | 状态[0禁用; 1启用]                       |



### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>boolean</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>


## 重启

<a id=重启> </a>

### 基本信息

**Path：** /crontab/reload

**Method：** POST

**接口描述：**

<pre><code>{
&nbsp; "code": 200,
&nbsp; "data": true,
&nbsp; "msg": "信息调用成功！"
}
</code></pre>



### 请求参数

**Headers**

| 参数名称     | 参数值                            | 是否必须 | 示例 | 备注 |
| ------------ | --------------------------------- | -------- | ---- | ---- |
| Content-Type | application/x-www-form-urlencoded | 是       |      |      |

**Body**

| 参数名称 | 参数类型 | 是否必须 | 示例 | 备注 |
| -------- | -------- | -------- | ---- | ---- |
| id       | text     | 是       | 1,2  |      |



### 返回数据

<table>
  <thead class="ant-table-thead">
    <tr>
      <th key=name>名称</th><th key=type>类型</th><th key=required>是否必须</th><th key=default>默认值</th><th key=desc>备注</th><th key=sub>其他信息</th>
    </tr>
  </thead><tbody className="ant-table-tbody"><tr key=0-0><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> code</span></td><td key=1><span>number</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-1><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> data</span></td><td key=1><span>boolean</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr><tr key=0-2><td key=0><span style="padding-left: 0px"><span style="color: #8c8a8a"></span> msg</span></td><td key=1><span>string</span></td><td key=2>非必须</td><td key=3></td><td key=4><span style="white-space: pre-wrap"></span></td><td key=5></td></tr>
               </tbody>
              </table>

​            
