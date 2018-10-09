<?php

namespace App\Http\Controllers;


use App\Model\ElasticSearch\EsClientConnection;

class TestController extends Controller
{
    public function test()
    {
        (new EsClientConnection())->search();
    }
}
