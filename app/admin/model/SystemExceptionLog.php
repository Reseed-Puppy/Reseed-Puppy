<?php

namespace app\admin\model;

use app\common\model\TimeModel;
use Throwable;

class SystemExceptionLog extends TimeModel
{
    protected $name = 'system_exception_log';
    protected $deleteTime = false;

    /**
     * @param Throwable $exception
     */
    static public function report(Throwable $exception)
    {
        $request = \request();
        self::create([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->url(),
            'header' => var_export($request->header(), true),
            'param' => var_export($request->param(), true)
        ]);
    }
}