<?php

/**
 * Created by PhpStorm.
 * User: lili
 * Date: 2018/8/5
 * Time: 00:10
 */
namespace App\crawler;

use App\crawler\config\Config;

class Service
{
    private static $instance;

    //防止直接创建对象
    private function __construct()
    {

    }

    //防止克隆对象
    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;

    }

    public function executeService($params)
    {
        $config = Config::getInstance()->crawlerCategory;
        $configType = $config[$params[count($params) - 1]];
        $class = $configType['service'];
        $method = $configType['method'];
        $serviceRefertion = new \ReflectionClass($class);
        if (!$serviceRefertion->hasMethod($method)) {
            throw new \Exception('method参数错误');
        }
        $service = $serviceRefertion->newInstanceArgs();
        $refertionMethod = $serviceRefertion->getMethod($method);
        $result = $refertionMethod->invokeArgs($service, $params);
        return $result;
    }
}