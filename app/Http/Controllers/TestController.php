<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(){
        //var_dump($_FILES);
        return view('admin/test');
    }

}
