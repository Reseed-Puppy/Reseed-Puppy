<?php

namespace Fairy\exception;

class RouteMethodNotAllowException extends HttpException
{
    /**
     * 路由方法不允许异常
     */
    public function __construct()
    {
        parent::__construct(405, 'Method Not Allowed');
    }
}