<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NumberController extends Controller
{
    //
    public function index($url_status = null,Request $request){
        //配置
        $areas = config('setting.showareas');
        $maps = config('setting.maps');
        $modes = config('setting.modes');
        $statuss = config('setting.statuss');

        //代理统计
        $res_daili = DB::table('daili') -> get();

        //$res_all = DB::table('daili') -> get();
        $count_guaji = 0;
        $count_lishi = 0;
        $count_all = 0;
        $count_point = 0;
        $count_point_all = 0;

        foreach($res_daili as $vo){
            $count_guaji += $vo -> number_guaji;
            $count_lishi += $vo -> number_lishi;
            $count_all += $vo -> number_all;
            $count_point += $vo -> point;
            $count_point_all += $vo -> point_all;
        }


        $status_name = '';
        if($url_status == '1'){
            $status_name = '完成订单';
            //检测时间，新的在上面
            $order = 'updated_time';
            $desc = 'desc';
        }elseif($url_status == '3'){
            $status_name = '问题订单';
            //检测时间，新的在上面
            $order = 'updated_time';
            $desc = 'desc';
        }elseif($url_status == '2'){
            $status_name = '所有账号';
            //剩余次数多的在上
            $order = 'save_time';
            $desc = 'desc';
        }else{
            $status_name = '挂机账号';
            //检测时间，老的在上面
            $order = 'updated_time';
            $desc = 'desc';

        }


        $res = DB::table('number') -> where(function($query) use($url_status,$request){
            if($request -> input('number')){
                $query -> where('number','like','%'.trim($request -> input('number')).'%' );
                //dd($request -> input('number'));
            }
            if($request -> input('daili')){
                $query -> where('add_user','like','%'.trim($request -> input('daili')).'%' );
                //dd($request -> input('number'));
            }
            if($request -> input('area')){
                $query -> where('area',trim($request -> input('area')));
            }
            if($request -> input('map')){
                $query -> where('map',trim($request -> input('map')));
            }
            if($request -> input('status')){
                $query -> where('status',trim($request -> input('status')));
            }



            if($url_status == '1'){
                //历史账号 -> 完成订单 0
                $query -> whereIn('status',[0,-1]);

            }elseif($url_status == '3'){
                //问题订单  -1
                $query -> where('status','<',-1);
            }elseif($url_status == '2'){
                //所有账号
                //$query -> where('mode','>',0);
                $query -> where('status','>=',-1);
                //$query -> where('status','>=',0);
            }else{
                //挂机 检测时间 asc
                //$order1 = '';
                //$query -> where('mode','=',0);
                $query -> where('status','>',0);
            }



        }) ->  orderBy($order,$desc) -> paginate(3000);

        foreach($res as $k => $vo){
            $res[$k] -> area = $areas[$vo -> area];
            $res[$k] -> map = $maps[$vo -> map]['name'];
            $res[$k] -> mode = $modes[$vo -> mode];
            $res[$k] -> status = $statuss[$vo -> status];
        }

        //挂机中的数量
        $count_guaji = DB::table('number') -> where('status','>',0) -> count();
        $count_paidui = DB::table('number') -> where('status','=',0) -> count();
        //ios挂机
        $count_guaji_ios = DB::table('newtable2') -> where('info2','like','IOS%') -> count();
        //安卓挂机
        $count_guaji_az = DB::table('newtable2') -> where('info2','like','JAY%') -> count();

        return view('admin/number/index') -> with([
            'res' => $res,
            'count_guaji'=>$count_guaji,
            'count_guaji_ios'=>$count_guaji_ios,
            'count_guaji_az'=>$count_guaji_az,
            'count_paidui'=>$count_paidui,
            'areas'=>$areas,
            'maps' => $maps,
            'statuss' => $statuss,
            'count_lishi' => $count_lishi,
            'count_all' => $count_all,
            'count_point' => $count_point,
            'count_point_all' => $count_point_all,
            'status_name' => $status_name
        ]);



    }
}
