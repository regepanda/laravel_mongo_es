<?php
/**
 * Created by PhpStorm.
 * User: regepanda
 * Date: 19-6-24
 * Time: 上午11:00
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Csv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Csv';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = storage_path().'/pl.csv';
        $file = fopen($file,'r');
        $i = 0;
        while ($data = fgetcsv($file)) {
            if ($i == 0) {
                $i ++;
                continue;
            }
            app('db')->connection('mysql')->table('user_pay')->insert([
                'user_id' => $data[0],
                'order_create_time' => date('Y-m-d H:i:s', strtotime($data[2])),
                'pay_status' => $data[5]
            ]);
            dump($data);
        }
        fclose($file);
    }
}