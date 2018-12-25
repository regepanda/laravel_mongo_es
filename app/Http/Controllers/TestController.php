<?php

namespace App\Http\Controllers;

use App\Model\ElasticSearch\EsClientConnection;
use Illuminate\Support\Facades\Request;

class TestController extends Controller
{
    public function get()
    {
        /**
         * 模糊查询
         * 1.match为模糊查询语句，关键字会被分词，然后再去匹配倒排索引，最后返回源文档
         */
        $bodyOne = [
            'query' => [
                'match' => [
                    'name' => '十日西班牙,葡萄牙乐享休闲游(中国上海出发)(^_^)'
                ]
            ],
//            'from' => '0',
//            'size' => '200',
//            'sort' => [
//                'product_id' => 'asc'
//            ]
        ];

        /**
         * 精确查询，使用非评分模式.精确查找一般会配合过滤器使用［单个过滤器］
         * 1.用constant_score将term查询转化成为过滤器
         * 2.term为精确查询，关键字不会被分词．直接匹配倒排索引中的次元，然后拿到源文档
         */
        $bodyTwo = [
            'query' => [
                'constant_score' => [
                    'filter' => [
                        'term' => [
                            'name' => '西班牙'
                        ]
                    ]
                ]
            ]
        ];

        /**
         * 组合过滤器(bool),可以接受多个其他过滤器作为参数［复合过滤器］
         * 1.must
         *  所有的语句都 必须（must） 匹配，与 AND 等价。
         * 2.must_not
         *  所有的语句都 不能（must not） 匹配，与 NOT 等价。
         * 3.should
         *  至少有一个语句要匹配，与 OR 等价。
         */
        $bodyThree = [
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'term' => [
                                'advertised_price' => 438.0
                            ]
                        ],
                        [
                            'term' => [
                                'name' => '西班牙'
                            ]
                        ]
                    ],
                    'must_not' => [
                        'term' => [
                            'name' => '葡萄牙'
                        ]
                    ]
                ]
            ]
        ];
        $results = (new EsClientConnection())->search($bodyTwo);
        dump($results);
    }

    public function add()
    {
        $params = Request::all();
        (new EsClientConnection())->addIndexDoc($params);
    }

    public function update()
    {
        $params = Request::all();
        $result = (new EsClientConnection())->updateDoc($params['id'], $params['body']);
        dump($result);
    }
}
