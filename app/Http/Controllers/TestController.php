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
use App\Services\HouseService;
use Illuminate\Support\Facades\Request;

class TestController extends Controller
{
    public function test1()
    {
        $conn = new MongoClientConnection();
        $data = $conn->getAll('people');
        dump($data);
    }

    public function test2()
    {
        $esObj = new EsClientConnection();
        $esObj->search();
    }

    public function test()
    {
        $script = public_path().'/house.py';
        $outPut = shell_exec("python $script https://www.tianyancha.com/search/p2?key=%E5%8C%97%E4%BA%AC%E6%88%BF%E5%9C%B0%E4%BA%A7");
        dump(json_decode($outPut, true));

//        $param = Request::all();
//        file_put_contents('/usr/local/var/www/wwwlogs/text.txt', json_encode($param).PHP_EOL, FILE_APPEND);
//        $res = [
//            'code' => 0,
//            'message' => 'successful'
//        ];
//        return response()->json($res);
    }
}