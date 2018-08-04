<?php
/**
 * Created by PhpStorm.
 * User: lili
 * Date: 2018/8/5
 * Time: 00:08
 */

namespace App\crawler\services;


class EyesService
{
    public function getUrl($page, $keyWord)
    {
        return sprintf("https://www.tianyancha.com/search/p%u?key=%s", $page, $keyWord);
    }

    public function getEyes($page, $keyWord)
    {
        $url = $this->getUrl($page, $keyWord);
        $script = app_path().'/crawler/script/house.py';
        $outPut = shell_exec("python $script $url");
        return json_decode($outPut, true);
    }
}