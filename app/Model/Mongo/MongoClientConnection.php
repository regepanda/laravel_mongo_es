<?php

namespace App\Model\Mongo;

class MongoClientConnection 
{
    protected $database = '';
    protected $mo;

    /**
     * 构造方法
     */
    public function __construct() 
    {
        $server = '127.0.0.1';
        $user = '';
        $password = '';
        $port = 27017;
        $database = 'test';
        $mongo = $this->getInstance($server, $user, $password, $port);
        $this->database = $mongo->$database;
    }

    /**
     * 数据库单例方法
     * @param $server
     * @param $user
     * @param $password
     * @param $port
     * @return Mongo
     */
    public function getInstance($server, $user, $password, $port) 
    {
        if (isset($this->mo)) {
            return $this->mo;
        } else {
            if (!empty($server)) {
                if (!empty($port)) {
                    if (!empty($user) && !empty($password)) {
                        $this->mo = new \MongoClient("mongodb://{$user}:{$password}@{$server}:{$port}");
                    } else {
                        $this->mo = new \MongoClient("mongodb://{$server}:{$port}");
                    }
                } else {
                    $this->mo = new \MongoClient("mongodb://{$server}");
                }
            } else {
                $this->mo = new \MongoClient();
            }
            return $this->mo;
        }
    }

    /**
     * 查询表中所有数据
     * @param $table
     * @param array $where
     * @param array $sort
     * @param string $limit
     * @param string $skip
     * @return array|int
     */
    public function getAll($table, $where = [], $sort = [], $limit = '', $skip = '') 
    {
        if (!empty($where)) {
            $data = $this->database->$table->find($where);
        } else {
            $data = $this->database->$table->find();
        }

        if (!empty($sort)) {
            $data = $data->sort($sort);
        }

        if (!empty($limit)) {
            $data = $data->limit($limit);
        }

        if (!empty($skip)) {
            $data = $data->skip($skip);
        }

        $newData = array();
        while ($data->hasNext()) {
            $newData[] = $data->getNext();
        }
        if (count($newData) == 0) {
            return 0;
        }
        return $newData;
    }

    /**
     * 查询指定一条数据
     * @param $table
     * @param array $where
     * @return int
     */
    public function getOne($table, $where = array()) 
    {
        if (!empty($where)) {
            $data = $this->database->$table->findOne($where);
        } else {
            $data = $this->database->$table->findOne();
        }
        return $data;
    }

    /**
     * 统计个数
     * @param $table
     * @param array $where
     * @return mixed
     */
    public function getCount($table, $where = array()) 
    {
        if (!empty($where)) {
            $data = $this->database->$table->find($where)->count();
        } else {
            $data = $this->database->$table->find()->count();
        }
        return $data;
    }

    /**
     * 直接执行mongo命令
     * @param $sql
     * @return array
     */
    public function toExcute($sql) 
    {
        $result = $this->database->execute($sql);
        return $result;
    }

    /**
     * 分组统计个数
     * @param $table
     * @param $where
     * @param $field
     */
    public function groupCount($table, $where, $field) 
    {
        $cond = [
            [
                '$match' => $where,
            ],
            [
                '$group' => [
                    '_id' => '$' . $field,
                    'count' => ['$sum' => 1],
                ],
            ],
            [
                '$sort' => array("count" => -1),
            ],
        ];
        $this->database->$table->aggregate($cond);
    }

    /**
     * 删除数据
     * @param $table
     * @param $where
     * @return array|bool
     */
    public function toDelete($table, $where) 
    {
        $re = $this->database->$table->remove($where);
        return $re;
    }

    /**
     * 插入数据
     * @param $table
     * @param $data
     * @return array|bool
     */
    public function toInsert($table, $data) 
    {
        $re = $this->database->$table->insert($data);
        return $re;
    }

    /**
     * 更新数据
     * @param $table
     * @param $where
     * @param $data
     * @return bool
     */
    public function toUpdate($table, $where, $data)
    {
        $re = $this->database->$table->update($where, array('$set' => $data));
        return $re;
    }

    /**
     * 获取唯一数据
     * @param $table
     * @param $key
     * @param array $query
     * @return array
     */
    public function distinctData($table, $key, $query = []) 
    {
        if (!empty($query)) {
            $where = array('distinct' => $table, 'key' => $key, 'query' => $query);
        } else {
            $where = array('distinct' => $table, 'key' => $key);
        }

        $data = $this->database->command($where);
        return $data['values'];
    }
}
