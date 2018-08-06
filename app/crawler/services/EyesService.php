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
    public function getUrl($page, $keyWord, $crawlerCate)
    {
        $config = Config::getInstance()->crawlerCategory;
        $url = $config[$crawlerCate]['url'];
        return sprintf($url, $page, $keyWord);
    }

    public function getEyes($page, $keyWord, $crawlerCate)
    {
        $url = $this->getUrl($page, $keyWord, $crawlerCate);
        $script = app_path().'/crawler/script/tianyan.py';
        $outPut = shell_exec("python $script $url");
        return json_decode($outPut, true);
    }
}