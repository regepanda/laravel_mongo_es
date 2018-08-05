<?php

/**
 * Created by PhpStorm.
 * User: lili
 * Date: 2018/8/5
 * Time: 00:10
 */
namespace App\crawler;

use App\crawler\services\EyesService;

class Service
{
    private static $instance;

    public $crawlerServices = [
        'EyesService' => EyesService::class
    ];

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

    public function executeService($class, $method, $params)
    {
        $serviceRefertion = new \ReflectionClass($this->crawlerServices[$class]);
        if (!$serviceRefertion->hasMethod($method)) {
            throw new \Exception('method参数错误');
        }
        $service = $serviceRefertion->newInstanceArgs();
        $refertionMethod = $serviceRefertion->getMethod($method);
        $result = $refertionMethod->invokeArgs($service, $params);
        return $result;
    }
}