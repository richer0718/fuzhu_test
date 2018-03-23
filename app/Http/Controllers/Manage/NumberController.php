<?php

namespace App\Http\Controllers\Manage;

use App\Log;
use GuzzleHttp\Client;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class NumberController extends Controller
{
    //
    public function index($url_status = null,Request $request){
        //var_dump($url_status);exit;

        //配置
        $areas = config('setting.showareas');
        $maps = config('setting.maps');
        $modes = config('setting.modes');
        $statuss = config('setting.statuss');

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
            $status_name = '长期账号';
            $order = 'updated_time';
            $desc = 'asc';
        }else{
            $status_name = '挂机账号';
            //检测时间，老的在上面
            $order = 'created_time';
            $desc = 'desc';

        }
        $add_user = session('username');
        $res = DB::table('number') -> where(function($query) use($url_status,$add_user,$request){
            $query -> where('add_user','=',$add_user);
            if($request -> input('number')){
                $query -> where('number','like','%'.trim($request -> input('number')).'%' );
                //dd($request -> input('number'));
            }else{
                if($url_status == '1'){
                    //历史账号 -> 完成订单 0
                    $query -> whereIn('status',[0,-1]);

                }elseif($url_status == '3'){
                    //问题订单  -1
                    $query -> where('status','<',-1);
                }elseif($url_status == '2'){
                    //长期账号
                    $query -> where('mode','>',0);
                    //$query -> where('status','>=',0);
                }else{
                    //挂机 检测时间 asc
                    $order1 = '';
                    //$query -> where('mode','=',0);
                    $query -> where('status','>',0);
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
            }





        })  -> orderBy($order,$desc) -> paginate(3000);
        //dd($res);
        foreach($res as $k => $vo){
            $res[$k] -> area_name = $areas[$vo -> area];
            $res[$k] -> truemap = $vo -> map;
            $res[$k] -> map = $maps[$vo -> map]['show'];
            $res[$k] -> mode = $modes[$vo -> mode];
            $res[$k] -> status = $statuss[$vo -> status];
        }
        //返回价格列表
        $price_str = htmlspecialchars_decode(config('setting.remarks'));
        //查找此代理的信息
        $userinfo = DB::table('daili') -> where([
            'username' => session('username')
        ]) -> first();

        $note_res = '目前排队账号:';
        $note_res .= '</br>';

        //统计newtable
        //IOSWZRY-2
        $result1 = DB::table('newtable') -> where([
            //'info' => 'IOSWZRY-2',
            'mark' => NULL
        ])->whereIn('info',[
            'IOSWZRY-2',
            'IOSWZRY2-2'
        ]) -> count();

        $result2 = DB::table('newtable') -> where([
            'mark' => NULL
        ]) ->whereIn('info',[
            'AZWZRY-2',
            'AZWZRY2-2'
        ])-> count();

        $result3 = DB::table('newtable') -> where([
            'info' => 'AZFC-2',
            'mark' => NULL
        ]) -> count();

        $result4 = DB::table('newtable') -> where([
            'info' => 'IOSFC-2',
            'mark' => NULL
        ]) -> count();
        $note_res .= '王者荣耀-苹果：'.$result1.'个';
        $note_res .= '<br>';
        $note_res .= '王者荣耀-安卓：'.$result2.'个';
        $note_res .= '<br>';
        $note_res .= 'QQ飞车-安卓：'.$result3.'个';
        $note_res .= '<br>';
        $note_res .= 'QQ飞车-苹果：'.$result4.'个';
        $note_res .= '<br>';


        return view('manage/number/index') -> with([
            'res' => $res,
            'url_status' => $url_status,
            'areas'=>$areas,
            'maps' => $maps,
            'statuss' => $statuss,
            'price_str' => $price_str,
            'userinfo' => $userinfo,
            'status_name' => $status_name,
            'note_res' => $note_res
        ]);
    }



    public function addNumber(){
        $xishus = config('setting.prices');
        $info = null;
        if(session('info')){
            $info = session('info');
        }
        //配置
        $maps = config('setting.maps');
        //dump($maps);exit;
        return view('manage/number/addNumber') -> with([
            'info' => $info,
            'xishus' => $xishus,
            'maps' => $maps
        ]);
    }

    public function addNumberRes(Request $request){

        //判断必填
        if(!$request -> input('number') || !$request -> input('pass') || !$request -> input('area') || !$request -> input('map') || !$request -> input('save_time')){
            return false;
        }
        //dd($request);
        //return $request -> input('number');


       //先判断下此账号是否存在与此系统中
        $isset = DB::table('number') -> where([
            'number' => $request -> input('number')
        ]) -> first();
        if($isset && $isset -> 	save_time > 0){
            if($request -> input('methods') == 'batch'){
                return 'error';
            }
            return redirect('manage/number') -> with('isset','该账号有剩余代刷次数没有回收，请回收后，再上传！如有疑问，请联系QQ：972102275');
        }

        //根据大区，获取系数

        $xishus = config('setting.prices');
        $maps = config('setting.maps');

        //不存在 直接新增
        if($request -> input('mark')){
            $mark = 1;
        }else{
            $mark = 0;
        }

        if($request -> input('jiaji')){
            $jiaji = 1;
            $endstr = 3;
        }else{
            $endstr = 2;
            $jiaji = 0;
        }

        //（当前时间+上号时间*60）*1000'
        $jiange = intval(time() + intval($request -> input('shanghao_time'))*3600  );

        //如果是点击备用
        if($request -> input('spare') == 'beiyong'){
            if($isset){
                return redirect('manage/addNumber') -> with('isset','该账号有剩余代刷次数没有回收，请回收后，再上传！如有疑问，请联系QQ：972102275');
            }
            $res = DB::table('number') -> insert([
                'is_jiaji' => $jiaji,
                'is_mark' => $mark,
                'order_id' => $request -> input('order_id'),
                'wangwang' => $request -> input('wangwang'),
                'xiaoqu' => $request -> input('xiaoqu'),
                'number' => $request -> input('number'),
                'pass' => $request -> input('pass'),
                'area' => $request -> input('area').$maps[$request -> input('map')]['pre'],
                'map' => $request -> input('map'),
                'save_time' => $request -> input('save_time'),
                'use_time' => $request -> input('save_time'),
                'mode' => $request -> input('mode'),
                'shanghao_time' => intval($request -> input('shanghao_time')) * 3600,
                'end_date' => $request -> input('end_date'),
                'wangwang_type' => $request -> input('wangwang_type'),
                'remark' => $request -> input('remark'),
                'created_time' => time(),
                'updated_time' => $jiange,
                'add_user' => session('username'),
                'status' => -22
            ]);

            //添加成功后直接返回success
            if($res){
                return redirect('manage/addNumber') -> with('spareRes','备用成功');
            }

        }


        //该代理账号的“总点数”大于或等于“刷图次数*系数”

        $xishu = $xishus[$request -> input('area').$maps[$request -> input('map')]['pre']];

        $userinfo = DB::table('daili') -> where([
            'username' => session('username')
        ]) -> first();
        //代理的点数
        $point_user = intval($userinfo -> point);
        //要扣除的点数
        $point_cut = intval($xishu) * intval($request -> input('save_time'));
        if($request -> input('jiaji')){
            $point_cut = $point_cut * 1.5;
        }

        if($point_user >= $point_cut){
            //他的余额够支付
            //调他的接口
            $daqu = $request -> input('area');
            $number = $request -> input('number');
            $pass = $request -> input('pass');

            //IOSWZRY-2  IOSWZRY-2
            $string = substr($request -> input('area'),0,2);



            $maps = config('setting.maps');
            if($string == 'AZ'){
                $youxi = $userinfo -> upload.'AZ'.$maps[$request->input('map')]['jiaji'].'-'.$endstr;
            }else{
                $youxi = $userinfo -> upload.'IOS'.$maps[$request->input('map')]['jiaji'].'-'.$endstr;
            }
            //dump($youxi);exit;

            $end_str = $maps[$request -> input('map')]['pre'];
            /*
            if($end_str != 'FC'){
                $end_str = '';
            }
            */

            DB::table('newtable3') -> where([
                'name' => $daqu.$end_str.'-'.$number,
            ]) -> delete();
            //插入
            $res = DB::table('newtable3') -> insert([
                'name' => $daqu.$end_str.'-'.$number,
                'passwd' => $pass,
                'info' => $youxi,
                'jiange2' => $jiange,
            ]);
            if($res){
                //echo 'success';
            }else{
                if($request -> input('methods') == 'batch'){
                    return 'error';
                }

                //添加失败
                return redirect('manage/number') -> with('isset','上传失败，联系QQ：972102275');
            }

            //添加成功后
            //扣除他的点数
            DB::table('daili') -> where([
                'username' => session('username')
            ]) -> decrement('point',$point_cut);

            //记录扣除日志
            $log = new Log();
            //将字符串转换成中文
            $areas = config('setting.areas');
            $temp_area = $areas[$request -> input('area')];
            $maps = config('setting.maps');
            $temp_map = $maps[$request -> input('map')]['name'];


            $log -> write(session('username'),'挂机',$point_cut,$request -> input('number'),'',$temp_area.','.$request -> input('xiaoqu').','.$temp_map.','.$request -> input('save_time').'次,'.$request -> input('order_id'));

            if($isset){
                //如果存在 则删除老数据
                DB::table('number') -> where([
                    'id' => $isset -> id
                ]) -> delete();
            }

            //dump($request -> input('area').$maps[$request -> input('map')]['pre']);exit;
            $res = DB::table('number') -> insert([
                'is_jiaji' => $jiaji,
                'is_mark' => $mark,
                'order_id' => $request -> input('order_id'),
                'wangwang' => $request -> input('wangwang'),
                'xiaoqu' => $request -> input('xiaoqu'),
                'number' => $request -> input('number'),
                'pass' => $request -> input('pass'),
                'area' => $request -> input('area').$maps[$request -> input('map')]['pre'],
                'map' => $request -> input('map'),
                'save_time' => $request -> input('save_time'),
                'use_time' => $request -> input('save_time'),
                'mode' => $request -> input('mode'),
                'shanghao_time' => intval($request -> input('shanghao_time')) * 3600,
                'end_date' => $request -> input('end_date'),
                'wangwang_type' => $request -> input('wangwang_type'),
                'remark' => $request -> input('remark'),
                'created_time' => time(),
                'updated_time' => $jiange,
                'add_user' => session('username'),
                'status' => 1
            ]);

            //挂机中数量加一 总账号数量+1
            DB::table('daili') -> where([
                'username' => session('username')
            ]) -> increment('number_guaji');

            DB::table('daili') -> where([
                'username' => session('username')
            ]) -> increment('number_all');

            if($request -> input('methods') == 'batch'){
                return 'success';
            }

            return redirect('manage/number') -> with('insertres','yes');

        }else{
            if($request -> input('methods') == 'batch'){
                return 'nomoney';
            }

            //不够支付，返回
            return redirect('manage/addNumber')->withInput($request->flash());
        }



        //先判断她的点数


    }

    public function rechargeRes(Request $request){
        //调接口充值
        $req_res = file_get_contents('http://feifeifuzhu.com/feifei/index.php/Admin/getCode/zhucema/'.$request -> input('code').'/youxi/WZRY');
        $req_res = trim($req_res);
        //dd($req_res);
        if($req_res){
            if($req_res == 'error'){
                //充值失败
                return redirect('manage/number') -> with('rechargeres','error');
            }else{
                $code = explode(',',$req_res);
                //判断code
                if(count($code) == 9 && $code[0] == 0 && $code[1] == 0 && $code[2] == 0 && $code[3] == 0 && $code[4] == 'WZRY' && intval($code[5]) > 0 && $code[8] == 0){
                    //code  符合要求 返回页面让他确认
                    return redirect('manage/number') -> with('recharge_true',array('point'=>$code[5],'code'=>$request -> input('code')));
                }else{
                    //充值失败
                    return redirect('manage/number') -> with('rechargeres','error');
                }
            }
        }else{
            //充值失败
            return redirect('manage/number') -> with('rechargeres','error');
        }




    }

    //确认充值
    public function rechargeConfirm(Request $request){
        //调接口验证第二次传来的数据
        $req_res = file_get_contents('http://feifeifuzhu.com/feifei/index.php/Admin/getCode/zhucema/'.$request -> input('code').'/youxi/WZRY');
        $req_res = trim($req_res);
        var_dump($req_res);
        if($req_res){
            if($req_res == 'error'){
                //充值失败
                return redirect('manage/number') -> with('rechargeres','error');
            }else{
                $code = explode(',',$req_res);
                //判断code
                if(count($code) == 9 && $code[0] == 0 && $code[1] == 0 && $code[2] == 0 && $code[3] == 0 && $code[4] == 'WZRY' && intval($code[5]) > 0 && $code[8] == 0){

                    //正式充值成功
                    //将这个代码作废

                    $delete_url = 'http://feifeifuzhu.com/feifei/index.php/Admin/uploadNumber/bh1/'.session('username').'/bh2/'.$code[5].'/bh3/1/bh4/1/youxi/WZRY/shuliang/0/zhucema/'.$request -> input('code').'/leixing/dk/daoqishijian/-'.time();
                    $delete_code = file_get_contents($delete_url);
                    //var_dump($delete_code);

                    if(strstr($delete_code,'success')){

                        //var_dump($delete_code);
                        //代理点数加
                        //DB::connection()->enableQueryLog();
                        DB::table('daili') -> where([
                            'username' => session('username')
                        ]) -> increment('point', intval($code[5]));

                        DB::table('daili') -> where([
                            'username' => session('username')
                        ]) -> increment('point_all',intval($code[5]));


                        //$log = DB::getQueryLog();
                        //dd($log);   //打印sql语句
                        //记录日志
                        $log = new Log();
                        $log -> write(session('username'),'充值',intval($code[5]),'',$request -> input('code'));
                        //echo 11;exit;
                        //充值成功
                        return redirect('manage/number') -> with('rechargeres','success');
                    }else{
                        //echo 22;exit;
                        return redirect('manage/number') -> with('rechargeres','error');
                    }




                }else{
                    //充值失败
                    return redirect('manage/number') -> with('rechargeres','error');
                }
            }
        }else{
            //充值失败
            return redirect('manage/number') -> with('rechargeres','error');
        }
    }

    //充值日志
    public function log(Request $request){
        $logs = DB::table('recharge_log') -> where(function($query) use($request){
            $query -> where('username',session('username'));
            if($request -> input('zhanghao')){
                $query -> where('zhanghao',$request -> input('zhanghao'));
            }
            if($request -> input('log_type')){
                $query -> where('log_type',$request -> input('log_type'));
            }
            if(!empty($request -> createtime_left)){
                $query -> where('created_at','>',strtotime($request -> createtime_left));
            }

            if(!empty($request -> createtime_right)){
                $query -> where('created_at','<',strtotime($request -> createtime_right));
            }
        }) -> orderBy('created_at','desc') -> get();

        $count_point = 0;
        foreach($logs as $vo){
            $vo -> class_name = '';
            //计算总数
            switch ($vo -> log_type){
                case '充值':$count_point = $count_point + intval($vo -> point);break;
                case '挂机':$count_point = $count_point - intval($vo -> point);$vo -> class_name='super';break;
                case '违约':$count_point = $count_point - intval($vo -> point);$vo -> class_name='super';break;
                case '回收':$count_point = $count_point + intval($vo -> point);break;
            }
        }

        return view('manage/number/log') -> with([
            'logs' => $logs,
            'count_point' => $count_point
        ]);
    }

    //停挂之前，检查该账号的信息
    public function stopNumber(Request $request){
        //代理点击“确认”后， 挂机状态 改成“手动停挂”参数是-1，挂机设备，改为0，检测时间改为当前时间
        $number_info = DB::table('number') -> where([
            'id' => $request -> input('id'),
            'add_user' => session('username')
        ]) -> first();

        //找出他的系数

        $xishus = config('setting.prices');
        $maps = config('setting.maps');
        $danjia = $xishus[$number_info->area];
        //总共返还的点数
        $price_all = intval($danjia) * intval($number_info -> save_time);

        //查下此人点数

        $userinfo = DB::table('daili') -> where([
            'username' => session('username')
        ]) -> first();
        $point_user = $userinfo -> point;
        //var_dump($point_user);
        //var_dump($price_all);
        if(intval($point_user) + intval($price_all) <100){
            //需要给他更新的点数为
            $poing_result = 0;



        }else{
            $poing_result = $point_user + $price_all - 100 ;
            ////把违约 剩下的钱还给他

        }

        //返还多少点 就是回收多少点
        $log_res = new Log();
        $log_res -> write(session('username'),'回收',intval($price_all),$number_info -> number);




        //var_dump($poing_result);exit;

        DB::table('number') -> where([
            'id' => $request -> input('id'),
            'add_user' => session('username')
        ]) -> update([
            'status' => '-1',
            'device' => 0,
            'save_time'=>0,
            'updated_time' => time()
        ]);

        //更新扣的点数
        DB::table('daili') -> where([
            'username' => session('username')
        ]) -> update([
            'point' => $poing_result
        ]);

        //挂机中数量减一
        DB::table('daili') -> where([
            'username' => session('username')
        ]) -> decrement('number_guaji');
        //历史数量加一
        DB::table('daili') -> where([
            'username' => session('username')
        ]) -> increment('number_lishi');


        //扣点数

        //扣除违约点数
        $log = new Log();
        $log -> write(session('username'),'违约',$point_user - $poing_result + $price_all);


        return redirect('manage/number') -> with('stopres','yes');
    }

    //重新上传账号
    public function uploadNumber($id){
        $res = DB::table('number') -> where([
            'id' => $id
        ]) -> first();
        //dd($res);
        //把此账号的信息带过去
        return redirect('manage/addNumber') -> with('info',$res);
    }
    //批量上传
    public function uploadNumbers(){
        $id = $_POST['id'];
        $res = DB::table('number') -> where([
            'id' => $id
        ]) -> first();
        $post_arr = (array)$res;
        //var_dump($post_arr);exit;
        //先转换了大区 再去上传 去那边再转换一遍
        $setting  = config('setting.areastoarea');
        $post_arr['area'] = $setting[$post_arr['area']];
        return $post_arr;
        //请求添加
        /*
        $http = new Client();
        $url = url('manage/addNumberRes');
        $response = $http->post($url,$post_arr);
        var_dump($response->getBody());
        */
    }


    public function delete_number($id){
        if(!$_GET['url_status']){
            $_GET['url_status'] = 3;
        }
        $res = DB::table('number') -> where([
            'id' => $id,
            'add_user' => session('username'),
            'save_time' => 0
        ]) -> delete();
        if($res){

            //代理的总数量减一
            DB::table('daili') -> where([
                'username' => session('username')
            ]) -> decrement('number_all');

            return redirect('manage/number/'.$_GET['url_status']) -> with('delete_number','success');
        }else{
            return redirect('manage/number/'.$_GET['url_status']) -> with('delete_number','error');
        }
    }

    public function yanzhengma(Request $request){
        $res = DB::table('number') -> where([
            'id' => $request -> input('yanzheng_id')
        ]) -> update([
            'yanzhengma' => $request -> input('yanzhengma')
        ]);
        if($res){
            return redirect('manage/number') -> with('yanzhengma','success');
        }else{
            return redirect('manage/number') -> with('yanzhengma','error');
        }

    }

    //客服列表
    public function kefu(){
        $res = DB::table('kefu') -> paginate(100);
        return view('manage/kefu') -> with([
            'res' => $res
        ]);
    }


    public function xiugaiRes(Request $request){
        if($request -> input('is_mark') == 'on'){
            $is_mark = 1;
        }else{
            $is_mark = 0;
        }
        //开始修改
        $res = DB::table('number') -> where([
            'number' => $request -> input('show_number')
        ]) -> update([
            'order_id' => $request -> input('order_id'),
            'wangwang_type' => $request -> input('wangwang_type'),
            'wangwang' => $request -> input('wangwang'),
            'remark' => $request -> input('remark'),
            'is_mark' => $is_mark
        ]);
        if($request -> input('url_status')){
            return redirect('manage/number/'.$request -> input('url_status'));
        }else{
            return redirect('manage/number');
        }



    }

    //删除所有
    public function deleteAllData(Request $request){
        $datas = $request -> input('data');
        $arr = explode(',',$datas);
        foreach($arr as $vo){
            //删除每个id
            $res = DB::table('number') -> where([
                'id' => $vo,
                'add_user' => session('username'),
                'save_time' => 0
            ]) -> delete();
        }
        echo 'success';
    }

    //导出
    public function exportFile(Request $request){
        set_time_limit(0);
        $data = $request -> input('data');
        $data = explode(',',$data);
        //大区
        //$areas = config('setting.areas');
        $areas = config('setting.showareas');
        //地图
        $maps = config('setting.maps');

        $res_arr[] = ['订单编号','旺旺/QQ','游戏账号','游戏密码','大区','小区','代挂次数','代挂地图','检测时间','操作时间'];
        foreach($data as $k => $vo){
            //查这个id的信息
            $temp = DB::table('number') -> where([
                'id' => $vo
            ]) -> first();
            dump($areas);
            dump($maps);
            dump($temp);
            dump($maps[$temp->map]['name']);
            dump($areas[$temp->area]);
            exit;
            $res_arr[] = ["'".(string)$temp -> order_id,$temp -> wangwang,$temp->number,$temp->pass,$areas[$temp->area],$temp->xiaoqu,$temp->use_time,$maps[$temp->map]['name'],date('Y-m-d H:i',$temp->updated_time),date('Y-m-d H:i',$temp->created_time)];

        }

        Excel::create('订单'.date('Y-m-d'),function($excel) use ($res_arr){
            $excel->sheet('order', function($sheet) use ($res_arr){
                $sheet->rows($res_arr);
            });
        })->export('xls');

    }

}



