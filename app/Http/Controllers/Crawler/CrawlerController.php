<?php
/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 18-5-24
 * Time: 下午5:35
 */

namespace App\Http\Controllers\Crawler;

use App\crawler\config\Config;
use App\crawler\Service;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

class CrawlerController extends Controller
{
    public function executeEyesCrawler()
    {
        $crawlerCate = Request::input('crawler_cate', 'jingdong');
        $page = Request::input('page', 3);
        $keyWorld = Request::input('key_world', '床上四件套');

        $result = Service::getInstance()->executeService([$page, $keyWorld, $crawlerCate]);
        $crawlerCategory = array_keys(Config::getInstance()->crawlerCategory);
        //@todo 这里需要王宇飞对$crawlerCategory变量进行入库操作
        if ($crawlerCate == 'jingdong') {
            //天眼数据入库
        } elseif ($crawlerCate == 'tianyan') {
            //京东数据入库，这里记得把图片的地址拿去请求图片资源，然后上传到自己的本地服务器
        }
        $dataFromMysql = [];
        return view('/index.show', compact(
            'result',
            'dataFromMysql',
            'crawlerCategory'
        ));
    }
}