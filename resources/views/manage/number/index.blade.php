@extends('layouts.manage_common')
@section('right-box')
    <style>
        #mytable tr td{
            border:1px solid #000000;
        }
    </style>
    <script src="{{ asset('js/laydate/laydate.js') }}"></script>
    <form method="post" action="{{ url('manage/exportFile') }}" id="myExportFileForm">
        <input type="hidden" name="data" id="shuju" />
        {{ csrf_field() }}
    </form>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="width:90%;margin-left:10%;overflow: scroll;" >
        <h1 style="text-align: center">{{ $status_name }}</h1>
        <h1 style="font-size:26px;color:purple;">{!! $note_res !!}</h1>
        <h1 class="page-header">总点数：{{ $userinfo -> point_all }}<br>剩余点数：{{ $userinfo -> point }}</h1>

            <h1 class="page-header"> 代挂费用：{{ $price_str }}</h1>

        <form method="post">
        <table class="table">
            <tr>
                <td>游戏账号：</td>
                <td>
                    <input type="text" name="number"  class="form-control" value="@if(!empty($_POST['number'])){{ $_POST['number'] }}@endif"/>
                </td>


                <td>大区：</td>
                <td>
                    <select name="area">
                        <option value="">请选择</option>
                        @foreach($areas as $k => $vo)
                        <option value="{{ $k }}">{{ $vo }}</option>
                        @endforeach
                    </select>
                </td>

                <td>代挂地图：</td>
                <td>
                    <select name="map">
                        <option value="">请选择</option>
                        @foreach($maps as $k => $vo)
                            <option value="{{ $k }}">{{ $vo }}</option>
                        @endforeach

                    </select>
                </td>

                <td>挂机状态：</td>
                <td>
                    <select name="status">
                        <option value="">请选择</option>
                    @foreach($statuss as $k => $vo)
                        <option value="{{ $k }}">{{ $vo }}</option>
                    @endforeach
                    </select>
                </td>



            </tr>

            <tr>
                <td colspan="7">
                    <button class="btn btn-default" type="submit">搜索</button>
                    <button class="btn btn-default" type="button" onclick="location.href='{{Request::getRequestUri()}}' ">重置</button>
                    <!--<button class="btn btn-default" type="button">导出</button>-->
                </td>
            </tr>
        </table>
            {{ csrf_field() }}
        </form>

        <ol class="breadcrumb">
            <li><a data-toggle="modal" href="{{ url('manage/addNumber') }}">新增账号</a></li>
            <li><a data-toggle="modal" data-target="#recharge">充值</a></li>
            <li><a href="{{ url('kefu/login') }}" target="_blank">二级账号登录</a></li>
            <!--
            <li><a href="{{ url('manage/exportFile') }}" target="_blank">导出</a></li>
            -->
        </ol>

        <h1 class="page-header">基本信息 <span class="badge"></span></h1>
        <div class="table-responsive"  >
            <table class="table table-striped table-hover" id="mytable">
                <thead>
                <tr style="height:40px;">
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">
                            <input type="button" value="全选" id="quanxuan"/><br>
                            <input type="button" value="取消" id="quxiao"/><br>
                            <input type="button" value="删除" id="shanchu" /><br>
                            <input type="button" value="导出" id="daochu"/>
                        </span></th>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">ID</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">订单编号</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">旺旺/QQ</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">游戏账号</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">游戏密码</span></th>
                    <th><span class="glyphicon glyphicon-signal"></span> <span class="visible-lg">大区</span></th>
                    <th><span class="glyphicon glyphicon-signal"></span> <span class="visible-lg">小区</span></th>
                    <th><span class="glyphicon glyphicon-camera"></span> <span class="visible-lg">代挂次数</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">剩余次数</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">代挂地图</span></th>
                    <!--
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">养号模式</span></th>
                    -->
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">挂机状态</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">挂机设备</span></th>
                    @if($url_status == 2)
                        <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">截止时间</span></th>
                    @endif
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">检测时间</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">操作账号</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">操作时间</span></th>
                    <th><span class="glyphicon glyphicon-pencil"></span> <span class="visible-lg">操作</span></th>
                </tr>
                </thead>
                <tbody>
                @unless(!$res)
                    @foreach($res as $k => $vo)
                        <tr>
                            <td>@if($url_status)<label><input type="checkbox"  name="numbers_check" class="numbers_check" value="{{ $vo -> id }}" /></label>@endif</td>
                            <td><label>{{ $k + 1  }}</label></td>
                            <td>@if($vo -> is_jiaji == 1)<a style="color:red;">【急】</a>@endif @if($vo -> is_mark == 1)<a style="color:green;">【标】</a>@endif<a>{{$vo -> order_id }}</a><a style="color:red;" class="gai" wangwang ="{{$vo -> wangwang}}" wangwang_type = "{{ $vo -> wangwang_type }}" order_id = "{{$vo -> order_id }}" number="{{ $vo -> number }}" is_mark="{{ $vo -> is_mark }}" > 【改】</a></td>

                            <td>
                                @if($vo -> wangwang)
                                    @if($vo -> wangwang_type == 1)
                                        <img  style="cursor: pointer" onclick="javascript:window.open('http://sighttp.qq.com/msgrd?v=1&uin={{ $vo -> wangwang }}', '_blank', 'height=502, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');"  border="0" SRC=http://wpa.qq.com/pa?p=1:{{ $vo -> wangwang }}:1 alt="点击这里给我发消息">
                                    @else
                                        <a target="_blank" href="http://amos.alicdn.com/getcid.aw?v=2&uid={{ $vo -> wangwang }}&site=cntaobao&s=1&groupid=0&charset=utf-8"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=tb_4194202_2012&site=cntaobao&s=1&charset=utf-8" alt="" title="" /></a>
                                    @endif
                                @endif
                            </td>
                            <td>{{$vo -> number }}</td>
                            <td>{{$vo -> pass }}</td>
                            <td>{{$vo -> area_name }}</td>
                            <td>{{$vo -> xiaoqu }}</td>
                            <td>{{$vo -> use_time}}</td>
                            <td>{{$vo -> save_time}}</td>
                            <td>{{mb_substr($vo -> map,0,4,'utf-8')}}</td>
                            <!--
                            <td>{{$vo -> mode}}</td>
                            -->
                            <td>
                                @if($vo -> status == '微信二维码') <a href="{{ 'http://img.feisushouyou.com/jietu/'.$vo->area.'-'.$vo->number.'.jpg' }}" target="_blank" style="color:red;">扫描微信二维码</a>
                                @elseif($vo -> status == '手机验证码' )  <a class="yanzhengma" data="{{ $vo -> id }}" style="color:red;">输入验证码</a> <a href="{{ 'http://img.feisushouyou.com/jietu/'.$vo->area.'-'.$vo->number.'.jpg' }}" target="_blank" style="color:red;">查看图片</a>
                                @elseif($vo -> status == '正常刷完' ) <a href="{{ 'http://img.feisushouyou.com/jietu/'.$vo->area.'-'.$vo->number.'-wc.jpg' }}" target="_blank" style="color:red;">正常刷完</a>
                                @elseif($vo -> status == '区不存在' ) <a href="{{ 'http://img.feisushouyou.com/jietu/'.$vo->area.'-'.$vo->number.'-xq.jpg' }}" target="_blank" style="color:red;">区不存在</a>
                                @else{{$vo -> status}}
                                @endif
                            </td>
                            <td>{{$vo -> device}}</td>
                            @if($url_status == 2)
                                <td>{{$vo -> end_date}}</td>
                            @endif
                            <td>{{ date('m-d H:i',$vo -> updated_time) }}</td>
                            <td>{{ $vo -> add_user }}@if($vo -> kefu_name){{ ':'.$vo -> kefu_name }}@endif</td>
                            <td>{{ date('m-d H:i',$vo -> created_time) }}</td>
                            <td>
                                @if($url_status == 0)
                                    <a id="stopNumberButton" number="{{ $vo -> id }}"  data="{{$vo -> save_time}}" onclick="tinggua(this)" >停挂</a>
                                @endif
                                @if($url_status == 1 || $url_status == 3)
                                    <a class="delete_number" data = "{{ $vo -> id }}">删除</a> ／ <a href="{{ url('manage/uploadNumber').'/'.$vo -> id }}">上传</a>
                                @endif
                                @if($url_status == 2)
                                        <a class="delete_number" data = "{{ $vo -> id }}">删除</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endunless
                </tbody>
                @if(count($res))
                <tfoot>
                    <tr>

                        <td colspan="@if($url_status == 2) 18 @else 17 @endif">{{ $res -> links() }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    <!-- 充值 -->
    <div class="modal fade " id="recharge" tabindex="-1" role="dialog"  >
        <div class="modal-dialog" role="document" style="width:900px;">
            <form action="{{ url('manage/rechargeRes') }}" method="post" autocomplete="off" draggable="false" id="myForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >充值</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table" style="margin-bottom:0px;">
                            <thead>
                            <tr> </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td wdith="10%">充值码:</td>
                                <td width="90%"><input type="text" value="" class="form-control" name="code" maxlength="" autocomplete="off" required/></td>
                            </tr>

                            {{ csrf_field() }}
                            </tbody>
                            <tfoot>
                            <tr></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 确认充值 -->
    <div class="modal fade " id="recharge_true" tabindex="-1" role="dialog"  >
        <div class="modal-dialog" role="document" style="width:400px;">
            <form action="{{ url('manage/rechargeConfirm') }}" method="post" autocomplete="off" draggable="false" id="myForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >{{ session('username') }} ，您确认充值么？</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table" style="margin-bottom:0px;">
                            <thead>
                            <tr> </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td colspan="2">
                                    <p style="font-size: 20px;">该充值码点数为:{{ session('recharge_true')['point'].'点' }}</p>
                                </td>
                            </tr>
                            <input type="hidden" name="code" value="{{ session('recharge_true')['code'] }}" />

                            {{ csrf_field() }}
                            </tbody>
                            <tfoot>
                            <tr></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 输入手机验证码 -->
    <div class="modal fade " id="yanzhengma_input" tabindex="-1" role="dialog"  >
        <div class="modal-dialog" role="document" style="width:400px;">
            <form action="{{ url('manage/yanzhengma') }}" method="post" autocomplete="off" draggable="false" id="myForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >请输入手机验证码</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table" style="margin-bottom:0px;">
                            <thead>
                            <tr> </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td wdith="20%">验证码:</td>
                                <td width="80%"><input type="number" value="" class="form-control" name="yanzhengma" maxlength="" autocomplete="off" required/></td>
                            </tr>

                            <input type="hidden" name="yanzheng_id"  />

                            {{ csrf_field() }}
                            </tbody>
                            <tfoot>
                            <tr></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- 停挂 -->
    <div class="modal fade " id="stopnumber" tabindex="-1" role="dialog"  >
        <div class="modal-dialog" role="document" style="width:400px;">
            <form action="{{ url('manage/stopNumber') }}" method="post" autocomplete="off" draggable="false" id="myForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >提醒</h4>
                    </div>
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="number_id" />
                    <div class="modal-body">

                        <h4 class="modal-title" >您将手动停止代挂。该账号剩余挂机次数为：<a id="numbertime"></a>次，未使用部分，将回收。中途停挂违约费用扣除100点</h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 修改 -->
    <div class="modal fade " id="xiugai" tabindex="-1" role="dialog"  >
        <div class="modal-dialog" role="document" style="width:900px;">
            <form action="{{ url('manage/xiugaiRes') }}" method="post" autocomplete="off" draggable="false" >
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >修改</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table" style="margin-bottom:0px;">
                            <thead>
                            <tr> </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td style="width:120px;">订单号：</td>
                                <td>
                                    <input type="text"  class="form-control"  id="show_order_id" name="order_id" />

                                    <label style="line-height:34px;margin-left:20px;"><input type="checkbox" id="show_is_mark" name="is_mark"  />标记</label>
                                    <!--
                                    <label style="line-height:34px;margin-left:10px;"><input type="checkbox" id="jiaji_input" disabled   />加急</label>
                                    <div style="clear:both;"></div>
                                    -->
                                </td>
                            </tr>
                            <tr>
                                <td style="width:120px;">类型：</td>
                                <td>
                                    <label style="margin: 0;line-height: 30px;font-size: 18px;">旺旺<input type="radio" name="wangwang_type" value="0" /></label>
                                    <label style="margin: 0;line-height: 30px;font-size: 18px;">QQ<input type="radio" name="wangwang_type" value="1"/></label>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:120px;">旺旺/QQ：</td>
                                <td><input type="text"  class="form-control"  name="wangwang" id="show_wangwang"   /></td>
                            </tr>
                            <input name="show_number" type="hidden" />
                            <tr>
                                <td style="width:120px;">游戏账号：</td>
                                <td><input type="text"  class="form-control"  id="show_number"   disabled/></td>
                            </tr>

                            {{ csrf_field() }}
                            </tbody>
                            <tfoot>
                            <tr></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        @if (session('insertres'))
            alert('添加成功！');
        @endif
        @if (session('editres'))
            alert('修改成功！');
        @endif
        @if (session('stopres'))
            alert('停挂成功！');
        @endif
        @if (session('yanzhengma'))
            alert('输入成功！');
        @endif
        @if (session('rechargeres') && session('rechargeres') == 'success')
            alert('充值成功！');
        @endif
        @if (session('rechargeres') && session('rechargeres') == 'error')
            alert('请核对后，再提交，如有疑问，联系QQ：972102275！');
        @endif


    </script>
    <script>
        $(function(){
            //全选
            $('#quanxuan').click(function(){

                //全部勾选
                var allCheckBoxs = document.getElementsByName("numbers_check");
                for(var i = 0; i < allCheckBoxs.length;i++ ){
                    allCheckBoxs[i].checked = true;
                }


            })
            $('#quxiao').click(function(){

                //全部勾选
                var allCheckBoxs = document.getElementsByName("numbers_check");
                for(var i = 0; i < allCheckBoxs.length;i++ ){
                    allCheckBoxs[i].checked = false;
                }

            })
            $('#shanchu').click(function(){
                var length = $('input[name=numbers_check]:checked').length;
                var data = '';
                if(!length){
                    alert('请至少选择一个');return false;
                }
                for(var i = 0 ; i < length;i++ ){
                    data += $('input[name=numbers_check]:checked').eq(i).val()+',';
                }

                data = data.substr(0,data.length-1);

                if(confirm('您确定要删除么')){
                    alert('请等待');
                    var url = '{{ url('manage/deleteAllData') }}';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {data:data},
                        //dataType:'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data){
                            if(data == 'success'){
                                alert('删除成功');
                                location.reload();
                            }
                        },
                        error: function(xhr, type){
                            alert('Ajax error!')
                        }
                    });
                }



            })

            //导出
            $('#daochu').click(function(){
                var length = $('input[name=numbers_check]:checked').length;
                var data = '';
                if(!length){
                    alert('请至少选择一个');return false;
                }
                for(var i = 0 ; i < length;i++ ){
                    data += $('input[name=numbers_check]:checked').eq(i).val()+',';
                }

                data = data.substr(0,data.length-1);

                if(confirm('您确定要导出么')){
                    $('#shuju').val(data);
                    $('#myExportFileForm').submit();
                }



            })




            //针对编号修改
            $('.gai').click(function(){
                var wangwang = $(this).attr('wangwang');
                var wangwang_type = $(this).attr('wangwang_type');
                var order_id = $(this).attr('order_id');
                var number = $(this).attr('number');
                var is_mark = $(this).attr('is_mark');
                //alert(is_mark);return false;
                $('#show_order_id').val(order_id);
                $('#show_number').val(number);
                $('input[name=show_number]').val(number);
                $('#show_wangwang').val(wangwang);
                if(is_mark == 1){
                    $('#show_is_mark').attr("checked","true");
                }else{
                    $('#show_is_mark').removeAttr("checked");
                }

                $('input[name=wangwang_type]').eq(wangwang_type).attr('checked',true);

                $('#xiugai').modal('show');
            })

            //数据验证
            $('#myForm').submit(function(){
                var length = $.trim( $('input[name=code]').val() ).length ;
                if( length != 16 ){
                    alert('充值码有误');return false;
                }
            })

            @if (session('recharge_true'))
                $('#recharge_true').modal('show')
            @endif

            $('.yanzhengma').click(function(){
                var id = $(this).attr('data');
                $('input[name=yanzheng_id]').val(id);
                $('#yanzhengma_input').modal('show')
            })


            @if (session('isset'))
                alert('{{ session('isset') }}');
            @endif

            @if (session('delete_number') && session('delete_number') == 'success')
                alert('删除成功');
            @endif

            @if (session('delete_number') && session('delete_number') == 'error')
                alert('该账号挂机信息已经变动，请刷新页面后重试！');
            @endif

            //删除数据
            $('.delete_number').click(function(){
                var id  = $(this).attr('data');
                if(confirm('您确定要删除么')){
                    location.href="{{ url('manage/delete_number') }}"+'/'+id;
                }
            })




        })

        function tinggua (sh){
            $('#number_id').val($(sh).attr('number'));
            //将剩余挂机次数显示
            $('#numbertime').text($(sh).attr('data'));
            $('#stopnumber').modal('show');
        }

        @if(session('login_status'))
        /*
        layer.open({
            type: 2,
            title: '公告',
            shadeClose: true,
            shade: 0.8,
            area: ['90%', '90%'],
            content: '{{ url('manage/topdetail') }}' //iframe的url
        });
        */
        @endif

    </script>
@stop