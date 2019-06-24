<?php
/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 19-6-24
 * Time: 上午10:43
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PushRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PushRedis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PushRedis';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $ids = app('db')->connection('mysql')->table('user_pay')->lists('user_id');
        $ids = array_unique($ids);
        foreach ($ids as $id) {
            $redis->lPush('user_ids', $id);
            dump($id);
        }
    }
}