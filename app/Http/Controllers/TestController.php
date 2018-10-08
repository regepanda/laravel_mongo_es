<?php

namespace App\Http\Controllers;


class TestController extends \UserService
{
    public function test()
    {
        return (new \UserService())->allInfo();
    }
}
