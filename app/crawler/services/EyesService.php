<?php
/**
 * Created by PhpStorm.
 * User: lili
 * Date: 2018/8/5
 * Time: 00:08
 */

namespace App\crawler\services;


use App\crawler\config\Config;

class EyesService
{
    public function getUrl($crawlerCate, $page, $keyWord)
    {
        $url = Config::getInstance()->crawlerCategory;
        return sprintf($url[$crawlerCate], $page, $keyWord);
    }

    public function getEyes($crawlerCate, $page, $keyWord)
    {
        $url = $this->getUrl($crawlerCate, $page, $keyWord);
        $script = app_path().'/crawler/script/house.py';
        $outPut = shell_exec("python $script $url");
        return json_decode($outPut, true);
    }
}