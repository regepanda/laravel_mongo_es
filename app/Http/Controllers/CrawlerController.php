<?php
/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 18-5-24
 * Time: 下午5:35
 */

namespace App\Http\Controllers;

use App\crawler\config\Config;
use App\crawler\Service;
use Illuminate\Support\Facades\Request;

class CrawlerController extends Controller
{
    public function executeEyesCrawler()
    {
        $crawlerCate = Request::input('crawler_cate');
        $page = Request::input('page');
        $keyWorld = Request::input('key_world');

        $result = Service::getInstance()->executeService('EyesService', 'getEyes', [$crawlerCate, $page, $keyWorld]);
        $crawlerCategory = array_keys(Config::getInstance()->crawlerCategory);

        return view('', compact(
            'result',
            'crawlerCategory'
        ));
    }
}