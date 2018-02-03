<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //
    public function login(){
        return view('admin/login');

    }
    public function loginout(Request $request){
        $request->session()->flush();
        return redirect('zyfanddzy/laoban');
    }

    public function loginRes(Request $request){
        $username = $request -> input('username');
        $password = $request -> input('password');
        $res = DB::table('admin') -> where([
            'username'=>$username,
            'password'=>$password,
            'type' => '1'
        ]) -> first();
        //dd($res);
        $res = (array)$res;
        if($res){

            session([
                'admin_username' => $res['username'],
                'type' => 'manage',
            ]); //储存登陆标志

            return redirect('admin/number')->with('status', 'success');
        }else{
            return redirect('zyfanddzy/laoban')->with('status', 'error');
        }
        //var_dump($res);
    }

    public function index(Request $request){
        if(!session('username')){
            return redirect('zyfanddzy/laoban');
        }
        //dd(session('username'));
        return view('admin/index');
    }

    //玩家获取
    public function getData(){
        $customer_number = '';
        $customer_area = '';
        if(Cookie::has('customer_name')){
            $customer_number = Cookie::get('customer_name');
        }
        if(Cookie::has('customer_area')){
            $customer_area = Cookie::get('customer_area');
        }
        $maps = config('setting.maps');

        $areas = [
            'AZQQ' => '安卓QQ',
            'AZVX' => '安卓微信',
            'IOSQQ' => '苹果QQ',
            'IOSVX' => '苹果微信',
        ];
        return view('admin/getdata') -> with([
            'areas' => $areas,
            'customer_number' => $customer_number,
            'customer_area' => $customer_area,
            'maps' => $maps
        ]);
    }

    public function chaxunRes(Request $request){
        $statuss = config('setting.statuss');
        $res = DB::table('number') -> where([
            'number' => $request -> input('number'),
            'area' => $request -> input('area'),
        ]) -> first();
        //dd($res);
        if($res){
            $res -> status_name = $statuss[$res -> status];
        }else{
            $res  = '123';
        }

        //dd($res);
        return redirect('getData') -> with('res',$res)->cookie('customer_name',$request -> input('number')) -> cookie('customer_area',$request->input('area'));;
    }

    public function yanzhengma(Request $request){
        $res = DB::table('number') -> where([
            'id' => $request -> input('yanzheng_id')
        ]) -> update([
            'yanzhengma' => $request -> input('yanzhengma')
        ]);


        if($res){
            echo 'success';
        }else{
            echo 'error';
        }
        return redirect('manage/number') -> with('yanzhengma','success');

    }




}
