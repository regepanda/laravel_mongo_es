<?php

/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 18-6-1
 * Time: 下午3:44
 */
namespace App\Model\Ssdb;

class SsdbClientConnection
{
    public function __construct()
    {
        $ssdb = new \SimpleSSDB('127.0.0.1', 8888);
    }
}