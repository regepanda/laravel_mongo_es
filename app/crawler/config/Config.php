<?php

/**
 * Created by PhpStorm.
 * User: lili
 * Date: 2018/8/5
 * Time: 00:52
 */
namespace App\crawler\config;

class Config
{
    private static $instance;

    public $crawlerCategory = [
        'tianyan',
        'jingdong'
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
}