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

    /**
     * 链接es
     * EsClientConnection constructor.
     * @throws \Exception
     */
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

    /**
     * 位指定索引增加一个文档
     * @param $body
     * @return array
     */
    public function addIndexDoc($body)
    {
        $params = [
            'index' => $this->db,
            'type' => $this->table,
            'body' => $body
        ];
        return $this->conn->index($params);
    }

    /**
     * 位指定索引批量增加文档
     * @param $arrays
     * @return array
     */
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

    /**
     * 跟新一条文档
     * @param $id
     * @param $updateData
     * @return array
     */
    public function updateDoc($id, $updateData)
    {
        $params = [
            'index' => $this->db,
            'type' => $this->table,
            'id' => $id,
            'body' => [
                'doc' => $updateData  //带上doc表示是文档操作
            ]
        ];
        return $this->conn->update($params);
    }

    /**
     * 查询
     * @param $body
     * @return mixed
     */
    public function search($body)
    {
        //组建查新SDL
        $params = [
            'index' =>  $this->db,   //['my_index1', 'my_index2'],可以通过这种形式进行跨库查询
            'type' => $this->table,//['my_type1', 'my_type2'],
            'body' => $body

        ];
        $result = $this->conn->search($params);
        $resources = $result['hits']['hits'];
        dump($resources);die;
        foreach ($resources as &$resource) {
            $resource = $resource['_source'];
        }
        return $resources;
    }
}