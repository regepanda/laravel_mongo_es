<?php

namespace App\Console\Commands\Hprose;

use Illuminate\Console\Command;
use Hprose\Swoole\Server;

class HttpServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HttpServer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HttpServer';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $server = new Server("http://0.0.0.0:8086");
        $server->addFunction(function ($name) {
            return "Hello $name!";
        });
        $server->start();
    }
}
