<?php
/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 19-6-24
 * Time: 上午11:00
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $result = [];
        while ($userId = $redis->lPop('user_ids')) {
            $userOrderPayeds = app('db')->connection('mysql')->table('user_pay')
                ->where('user_id', $userId)
                ->where('pay_status', 2)
                ->orderBy('order_create_time', 'asc')
                ->lists('order_create_time');
            if (empty($userOrderPayeds)) {
                continue;
            }
            $userorders = app('db')->connection('mysql')->table('user_pay')
                ->select(['order_create_time', 'pay_status'])
                ->where('user_id', $userId)
                ->orderBy('order_create_time', 'asc')
                ->get();
            $userorders = json_decode(json_encode($userorders), true);
            foreach ($userOrderPayeds as $key => $userOrderPayed) {
                $result[$userId][$key]['user_id'] = $userId;
                $result[$userId][$key]['not_pay_time'] = $userOrderPayed;
                $num = $this->tmp($userorders, $userOrderPayed);
                $result[$userId][$key]['recent_next_time'] = isset($userorders[$num + 1]) ? $userorders[$num + 1]['order_create_time'] : 0;
                $result[$userId][$key]['pay_status'] = isset($userorders[$num + 1]) ? $userorders[$num + 1]['pay_status'] : 0;
            }
            dump($userId);
        }
        $exportStr = '';
        foreach ($result as $userId => $res) {
            foreach ($res as $value) {
                $exportStr .= "{$value['user_id']}\t,{$value['not_pay_time']}\t,{$value['recent_next_time']}\t,{$value['pay_status']}\t\n";
            }
        }
        $exportStr = iconv("UTF-8", "GB2312//IGNORE", $exportStr);
        file_put_contents('/home/regepanda/user.csv', $exportStr);
    }

    public function tmp($arr, $value)
    {
        foreach ($arr as $k => $v) {
            if ($v['order_create_time'] == $value) {
                return $k;
            }
        }
    }
}