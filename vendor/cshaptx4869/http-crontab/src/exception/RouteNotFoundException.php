<?php

namespace Fairy\exception;

class RouteNotFoundException extends HttpException
{
    /**
     * 路由未定义异常
     */
    public function __construct()
    {
        parent::__construct(404, 'Route Not Found');
    }
}