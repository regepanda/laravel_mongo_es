<?php
/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 18-5-24
 * Time: 下午5:35
 */

namespace App\Http\Controllers;

use App\crawler\Service;

class CrawlerController extends Controller
{
    public function executeCrawler()
    {
        Service::getInstance()->executeService('EyesService', 'getEyes', [1, '北京房地产']);
    }
}