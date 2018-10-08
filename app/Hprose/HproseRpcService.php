<?php

/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 18-9-20
 * Time: 下午4:11
 */
namespace App\Hprose;

use App\Http\Controllers\Controller;
use Hprose\Http\Server;
use Illuminate\Support\Facades\Request;

class HproseRpcService extends Controller
{
    public function __construct()
    {
        $config = $this->initConfig();
        $server = new Server();
        if (env('APP_ENV') == 'prod') {
            $server->onBeforeInvoke = function ($name, $args, $byref, \stdClass $context) use($config) {
                //首先获取Header里面的基本信息
                $timeStamp = Request::header('time-stamp');
                if (empty($timeStamp)) {
                    new \Exception('time-stamp必须在header里面提交');
                }
                $sign = Request::header('sign');
                if (empty($sign)) {
                    new \Exception('sign必须在header里面提交');
                }
                $appName = Request::header('app-name');
                if (empty($appName)) {
                    new \Exception('app-name必须在header里面提交');
                }
                $trueTimeStamp = time();
                if (abs($trueTimeStamp - $timeStamp) > 300) {
                    new \Exception('你提交的time-stamp和服务器时间戳差距太大');
                }

                $appId = $config['appid'];
                $appSecret = $config['appsecret'];
                $argArr = $args;
                sort($argArr);
                $argJson = json_encode($argArr);

                $signStr = "{$timeStamp}-{$appId}-{$argJson}-{$appSecret}";
                $trueSign = md5($signStr);
                if ($trueSign != $sign) {
                    new \Exception('Auth Field!');
                }
            };
        }
        $server->addInstanceMethods($this);
        $server->start();
    }
    
    public function initConfig()
    {
        $rpcConfig = config('hprose.http_rpc.app_config');
        return $rpcConfig;
    }
}