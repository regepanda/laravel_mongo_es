<?php

/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 18-5-28
 * Time: 下午5:07
 */
namespace App\Model\ElasticSearch;

use Elasticsearch\ClientBuilder;

class EsClientConnection
{
    public $conn;

    public $db = 'product';

    public $table = 'tour_product';
    
    private $servers = [
        '127.0.0.1:9200'
    ];

    public function __construct()
    {
        $conn = ClientBuilder::create()->setHosts($this->servers)->build();
        if ($conn) {
            $this->conn = $conn;
            $mapping = config('databaseForEs.indexMappings');
            if (!$this->conn->indices()->exists(['index' => $this->db])) {
                $this->conn->indices()->create(['index' => $this->db, 'body' => $mapping]);
            }
        } else {
            throw new \Exception('connection fail');
        }
        
    }

    public function addIndexDoc($body)
    {
        $params = [
            'index' => $this->db,
            'type' => $this->table,
            'body' => $body
        ];
        return $this->conn->index($params);
    }

    public function addIndexBulk($arrays)
    {
        $params['body'] = [];
        foreach ($arrays as $array) {
            $params['body'][] = [
                'index' => [
                    '_index' => $this->db,
                    '_type' => $this->table,
                ]
            ];
            $params['body'][] = $array;
        }
        return $this->conn->bulk($params);
    }

    public function search()
    {
        /**
         * 模糊查询
         * 1.match为模糊查询语句，关键字会被分词，然后再去匹配倒排索引，最后返回源文档
         */
        $bodyOne = [
            'query' => [
                'match' => [
                    'name' => '十日西班牙,葡萄牙乐享休闲游(中国上海出发)(^_^)',
                    'image_url' => 'jpg' ,
                    'tag.tagName' =>'tag'
                ]
            ],
            'from' => '0',
            'size' => '200',
            'sort' => [
                'age' => 'desc'
            ]
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

        $queryFour = [
            'query' => [
                
            ]
        ];

        //组建查新SDL
        $params = [
            'index' =>  $this->db,   //['my_index1', 'my_index2'],可以通过这种形式进行跨库查询
            'type' => $this->table,//['my_type1', 'my_type2'],
            'body' => $bodyThree

        ];
        $result = $this->conn->search($params);
        $resources = $result['hits']['hits'];
        dump($resources);die;
        foreach ($resources as &$resource) {
            $resource = $resource['_source'];
        }
        dump($resources);die;
    }
}