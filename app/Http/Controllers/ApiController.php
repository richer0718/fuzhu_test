<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //表1
    public function uploadNumber(){
        if($_GET['name'] && $_GET['passwd'] && $_GET['info']){
            $repeat = DB::table('newtable') -> where([
                'name' => trim($_GET['name'])
            ]) -> first();
            if($repeat){
                echo 'repeat';
            }else{
                //插入
                $res = DB::table('newtable') -> insert([
                    'name' => trim($_GET['name']),
                    'passwd' => trim($_GET['passwd']),
                    'info' => trim($_GET['info']),
                ]);
                if($res){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }
        }else{
            echo 'error';
        }
    }

    public function deleteNumber(){
        if($_GET['name']){
            $res = DB::table('newtable') -> where([
                'name' => trim($_GET['name'])
            ]) -> delete();
            if($res){
                echo 'success';
            }else{
                echo 'nosuccess';
            }
        }else{
            echo 'error';
        }
    }



    //表2
    public function  updateDeviceData(){
        if($_GET['info'] && $_GET['info2']){
            $number = DB::table('newtable') -> where([
                'info' => trim($_GET['info']),
                'mark' => ''
            ]) -> first();
            if($number){
                $isset = DB::table('newtable2') -> where([
                    'info2' => trim($_GET['info2'])
                ]) -> first();
                if($isset){
                    //更新
                    $res = DB::table('newtable2') -> where([
                        'info2' => trim($_GET['info2'])
                    ]) -> update([
                        'info' => trim($_GET['info']),
                        'name' => $isset -> name,
                        'passwd' => $isset -> passwd,
                        'time' => time()
                    ]);
                }else{
                    //插入
                    $res = DB::table('newtable2') -> insert([
                        'info' => trim($_GET['info']),
                        'name' => $isset -> name,
                        'passwd' => $isset -> passwd,
                        'info2' => trim($_GET['info2']),
                        'time' => time()
                    ]);
                }

                if($res){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }else{
                echo 'nouse';
            }

        }else{
            echo 'error';
        }
    }


    //表2获取
    public function getDeviceData(){
        if($_GET['info2']){
            $isset = DB::table('newtable2') -> where([
                'info2' => trim($_GET['info2'])
            ]) -> first();
            if($isset){
                echo $isset -> name.','.$isset -> passwd.','.$isset -> info;
            }else{
                echo 'nodata';
            }
        }else{
            echo 'error';
        }
    }



    //表4
    public function addNumberTable4(){
        if($_GET['name'] && $_GET['pwe'] && $_GET['wheree'] && $_GET['beizhu1'] && $_GET['beizhu2'] && $_GET['beizhu3'] && $_GET['beizhu4']){
            $isset = DB::table('newtable4') -> where([
                'name' => trim($_GET['name'])
            ])->first();
            if($isset){
                echo 'isset';
            }else{
                $res = DB::table('newtable4') -> insert([
                    'name' => trim($_GET['name']),
                    'pwe' => trim($_GET['pwe']),
                    'wheree' => trim($_GET['wheree']),
                    'beizhu1' => trim($_GET['beizhu1']),
                    'beizhu2' => trim($_GET['beizhu2']),
                    'beizhu3' => trim($_GET['beizhu3']),
                    'beizhu4' => trim($_GET['beizhu4']),
                    'created_at' => time()
                ]);
                if($res){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }
        }else{
            echo 'error';
        }
    }

    public function deleteNumberTable4(){
        if($_GET['name']){
            $res = DB::table('newtable4') -> where([
                'name' => $_GET['name']
            ]) -> delete();
            if($res){
                echo 'success';
            }else{
                echo 'error';
            }
        }else{
            echo 'error';
        }
    }


    public function updateNumberTable4(){
        //替换：就是，不管存在不存在，直接覆盖。没有就上传，有就替换
        if($_GET['name'] && $_GET['pwe'] && $_GET['wheree'] && $_GET['beizhu1'] && $_GET['beizhu2'] && $_GET['beizhu3'] && $_GET['beizhu4']){
            $isset = DB::table('newtable4') -> where([
                'name' => trim($_GET['name'])
            ])->first();
            if($isset){
                //更新
                $res = DB::table('newtable4') -> where([
                    'name' => trim($_GET['name']),
                ])-> update([
                    'pwe' => trim($_GET['pwe']),
                    'wheree' => trim($_GET['wheree']),
                    'beizhu1' => trim($_GET['beizhu1']),
                    'beizhu2' => trim($_GET['beizhu2']),
                    'beizhu3' => trim($_GET['beizhu3']),
                    'beizhu4' => trim($_GET['beizhu4']),
                    'created_at' => time()
                ]);
                if($res){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }else{
                //新增
                $res = DB::table('newtable4') -> insert([
                    'name' => trim($_GET['name']),
                    'pwe' => trim($_GET['pwe']),
                    'wheree' => trim($_GET['wheree']),
                    'beizhu1' => trim($_GET['beizhu1']),
                    'beizhu2' => trim($_GET['beizhu2']),
                    'beizhu3' => trim($_GET['beizhu3']),
                    'beizhu4' => trim($_GET['beizhu4']),
                    'created_at' => time()
                ]);
                if($res){
                    echo 'success';
                }else{
                    echo 'error';
                }
            }


        }else{
            echo 'error';
        }
    }

    public function getNumberTable4(){
        if($_GET['name']){
            $res = DB::table('newtable4') -> where([
                'name' => trim($_GET['name']),
            ])->first();
            if($res){
                return response() -> json($res);
            }
        }else{
            echo 'error';
        }
    }












}
