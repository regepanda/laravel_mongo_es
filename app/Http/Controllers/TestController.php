<?php
/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 18-5-24
 * Time: 下午5:35
 */

namespace App\Http\Controllers;

use App\Model\Mongo\MongoClientConnection;
use App\Model\ElasticSearch\EsClientConnection;

class TestController extends Controller
{
    public function test1()
    {
        $conn = new MongoClientConnection();
        $data = $conn->getAll('people');
        dump($data);
    }

    public function test()
    {
        $esObj = new EsClientConnection();
        $esObj->search();
    }
}