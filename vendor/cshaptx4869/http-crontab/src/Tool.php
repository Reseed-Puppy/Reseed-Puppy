<?php

namespace Fairy;

class Tool
{
    /**
     * 函数是否被禁用
     * @param $method
     * @return bool
     */
    public static function isFunctionDisabled($method)
    {
        return in_array($method, explode(',', ini_get('disable_functions')));
    }

    /**
     * 扩展是否加载
     * @param $extension
     * @return bool
     */
    public static function isExtensionLoaded($extension)
    {
        return in_array($extension, get_loaded_extensions());
    }

    /**
     * 是否是Linux操作系统
     * @return bool
     */
    public static function isLinux()
    {
        return strpos(PHP_OS, "Linux") !== false ? true : false;
    }

    /**
     * 版本比较
     * @param $version
     * @param string $operator
     * @return bool
     */
    public static function versionCompare($version, $operator = ">=")
    {
        return version_compare(phpversion(), $version, $operator);
    }
}