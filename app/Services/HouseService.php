<?php

/**
 * Created by PhpStorm.
 * User: lili
 * Date: 2018/8/2
 * Time: 14:31
 */
namespace App\Services;

use phpQuery;
use QL\QueryList;

class HouseService
{
    public function scrapy()
    {
        $data = QueryList::get('https://www.tianyancha.com/search?key=%E5%8C%97%E4%BA%AC%E6%88%BF%E5%9C%B0%E4%BA%A7')
            // 设置采集规则
            ->rules([
                'content'=>array('.title text-ellipsis>a','href'),
//                'link'=>array('h3>a','href')
            ])
            ->query()->getData();

        echo '<pre>';
        print_r($data->all());
    }
}