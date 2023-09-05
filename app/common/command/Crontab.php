<?php

namespace app\common\command;

use EasyAdmin\console\CliEcho;
use Fairy\HttpCrontab;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Crontab extends Command
{
    protected function configure()
    {
        $this->setName('crontab')
            ->addArgument('action', Argument::REQUIRED, 'start|stop|restart|reload|status|connections')
            ->addOption('daemon', 'd', Option::VALUE_NONE, 'Run the http crontab server in daemon mode.')
            ->addOption('name', null, Option::VALUE_OPTIONAL, 'Crontab name', 'Crontab Server')
            ->addOption('debug', null, Option::VALUE_NONE, 'Print log')
            ->setDescription('Run http crontab server');
    }

    protected function execute(Input $input, Output $output)
    {
        $action = trim($input->getArgument('action'));
        if (!in_array($action, ['start', 'stop', 'restart', 'reload', 'status', 'connections'])) {
            CliEcho::error('action参数值非法');
            return false;
        }
        $options = $input->getOptions();
        $env = file_exists(root_path() . '.env') ? parse_ini_file('.env', true) : [];
        $url = '';
        if (isset($env['EASYADMIN']['CRONTAB_BASE_URI']) && $env['EASYADMIN']['CRONTAB_BASE_URI']) {
            if (!preg_match('/https?:\/\//', $env['EASYADMIN']['CRONTAB_BASE_URI'])) {
                CliEcho::error('CRONTAB_BASE_URI 配置值非法');
                return false;
            }
            $url = $env['EASYADMIN']['CRONTAB_BASE_URI'];
        }
        $server = new HttpCrontab($url);
        $server->setName($options['name'])
            ->setDbConfig($env['DATABASE'] ?? [])
            ->setTaskTable('system_crontab')
            ->setTaskLogTable('system_crontab_flow')
            ->setTaskLockTable('system_crontab_lock');
        if (isset($env['EASYADMIN']['CRONTAB_SAFE_KEY']) && $env['EASYADMIN']['CRONTAB_SAFE_KEY']) {
            $server->setSafeKey($env['EASYADMIN']['CRONTAB_SAFE_KEY']);
        }
        $options['debug'] && $server->setDebug();
        $server->run();
    }
}
