<?php

/**
 * Created by PhpStorm.
 * User: lili
 * Date: 2018/8/5
 * Time: 00:52
 */
namespace App\crawler\config;

use App\crawler\services\EyesService;
use App\crawler\services\JdService;

class Config
{
    private static $instance;

    public $crawlerCategory = [
        'tianyan' => [
            'url' => 'https://www.tianyancha.com/search/p%u\?key\=%s',
            'service' => EyesService::class,
            'method' => 'getEyes'
        ],
        'jingdong' => [
            'url' => 'https://search.jd.com/Search\?keyword\=%s\&enc\=utf-8\&qrst\=1\&rt\=1\&stop\=1\&vt\=2\&stock\=1\&page\=%u\&s\=231\&click\=0',
            'service' => JdService::class,
            'method' => 'getJD'
        ]
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