@extends('layouts.manage_common')
@section('right-box')

    <style>
        table tr{
            margin-top:5px;
        }
        .laydate_box, .laydate_box * {
            box-sizing:content-box;
        }
    </style>
    <script src="{{ asset('js/laydate/laydate.js') }}"></script>
    <div style="height:100%;width:100%;position:fixed;z-index:0;" id="superdiv"></div>
    @foreach($maps as $k => $vo)
        <input type="hidden" class="selects" value="{{ $vo['name'] }}"  />
    @endforeach
    <form id="myForm" method="post" action="{{ url('manage/addNumberRes') }}" onsubmit="return chekform()">
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" >
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">添加账号</h1>
                <div class="col-md-5">

                    <table class="table table-striped table-bordered" style="width:100%;">
                        <tr>
                            <td style="width:120px;">订单号：</td>
                            <td>
                                <input type="number"  class="form-control"  name="order_id" @if(isset($info)) value="{{ $info -> order_id }}" @endif  />
                                <label style="line-height:34px;margin-left:20px;"><input type="checkbox" name="mark" id="checkbox_mark" value="1"  />标记</label>
                                <label style="line-height:34px;margin-left:10px;"><input type="checkbox" name="jiaji" id="checkbox_ji" value="1"  />加急</label>
                                <div style="clear:both;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:120px;">备注：</td>
                            <td><input type="text"  class="form-control"  name="remark" @if(isset($info) || old('remark') ) value="{{ $info -> remark or old('remark')  }}" @endif /></td>
                        </tr>
                        <tr>
                            <td style="width:120px;">类型：</td>
                            <td>
                                <label style="margin: 0;line-height: 30px;font-size: 18px;">旺旺<input type="radio" name="wangwang_type" value="0" /></label>
                                <label style="margin: 0;line-height: 30px;font-size: 18px;">QQ<input type="radio" name="wangwang_type" value="1"/></label>
                                <input type="text"  style="width: 50%;float:right;" class="form-control"  name="wangwang" @if(isset($info)) value="{{ $info -> wangwang }}" @endif />
                            </td>
                        </tr>

                        <tr>
                            <td style="width:120px;"><a style="color:red;">*</a>游戏账号：</td>
                            <td><input type="text"  class="form-control"  name="number" @if(isset($info) || old('number') ) value="{{ $info -> number or old('number')  }}" @endif required  oninput="this.value=this.value.replace(/[\u4e00-\u9fa5]/g,'');" /></td>
                        </tr>
                        <tr>
                            <td><a style="color:red;">*</a>游戏密码：</td>
                            <td><input type="text"  placeholder="游戏密码"  class="form-control" name="pass" @if(isset($info) || old('pass') ) value="{{ $info -> pass or old('pass') }}" @endif required oninput="this.value=this.value.replace(/[\u4e00-\u9fa5]/g,'');" /></td>
                        </tr>
                        <tr>
                            <td>大区：</td>
                            <td>
                                <select name="area" id="area_select">
                                    <option value="AZQQ" @if(isset($info))  @if( substr($info -> area,0,4) == 'AZQQ') selected @endif @endif  @if( old('area') == 'AZQQ') selected @endif >安卓QQ</option>
                                    <option value="AZVX" @if(isset($info))  @if( substr($info -> area,0,4) == 'AZVX') selected @endif @endif  @if( old('area') == 'AZVX') selected @endif >安卓微信</option>
                                    <option value="IOSQQ" @if(isset($info))  @if( substr($info -> area,0,4) == 'IOSQ') selected @endif @endif @if( old('area') == 'IOSQQ') selected @endif >苹果QQ</option>
                                    <option value="IOSVX" @if(isset($info))  @if( substr($info -> area,0,4) == 'IOSV') selected @endif @endif  @if( old('area') == 'IOSVX') selected @endif >苹果微信</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>小区：</td>
                            <td><input type="number" placeholder="留空或者填0 表示刷默认大区" class="form-control" name="xiaoqu"  @if(isset($info)) value="{{ $info -> xiaoqu }}" @endif   /></td>
                        </tr>
                        <tr>
                            <td>刷图选择：</td>
                            <td>
                                <select name="map" id="map_select">
                                    @foreach($maps as $k => $vo)
                                        <option data="{{ $vo['pre'] }}"  number="{{ $vo['time'] }}" value="{{ $k }}" @if(isset($info))  @if( $info -> map == $k) selected @endif @endif   >{{ $vo['name'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><a style="color:red;">*</a>刷图次数：</td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="save_time" min="30" max="10000" @if(isset($info) || old('save_time')) value="{{ $info -> use_time or old('save_time') }}" @else  @endif required/>
                                    <span class="input-group-addon">次(30~10000)</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>养号模式：</td>
                            <td>
                                <select name="mode" id="mode_select">
                                    <option value="0" @if(isset($info))  @if( $info -> mode == '0') selected @endif @endif @if( old('mode') == '0') selected @endif >关闭</option>
                                    <option value="1" @if(isset($info))  @if( $info -> mode == '1') selected @endif @endif  @if( old('mode') == '1') selected @endif >模式一</option>
                                    <option value="2" @if(isset($info))  @if( $info -> mode == '2') selected @endif @endif @if( old('mode') == '2') selected @endif >模式二</option>
                                    <option value="3" @if(isset($info))  @if( $info -> mode == '3') selected @endif @endif @if( old('mode') == '3') selected @endif >模式三</option>
                                </select>
                            </td>
                        </tr>

                        <tr id="super_tr" @if(!isset($info))style="display:none;"@endif >
                            <td>截止时间：</td>
                            <td>
                                <div class="input-group">
                                    <input type="text" id="end_date" name="end_date"  value="@if(isset($info)){{ $info -> end_date }}@endif" class="form-control" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})"  />
                                    <span class="input-group-addon">23:59:59</span>
                                </div>

                            </td>
                        </tr>

                        <tr>
                            <td>上号时间：</td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="shanghao_time" min="0" value="0" required/>
                                    <span class="input-group-addon">小时后开始排队！</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>当前时间：</td>
                            <td>
                                <input type="text"  class="form-control show_login_time"  disabled/>
                            </td>
                        </tr>
                        <input type="hidden" name="spare"  value="0" />
                        <tr>
                            <td colspan="2">
                                <input  class="btn btn-success" type="submit" id="addButton" value="添加"/>
                                <!--
                                <input class="btn btn-primary" type="submit" id="spare"   value="备用" />
                                -->
                                <button class="btn btn-default" type="button" id="rest">重置</button>
                            </td>
                        </tr>

                    </table>
                </div>
                <div class="col-md-7">
                    <textarea style="width:100%;height:250px;" id="textarea"></textarea>
                    <button class="btn btn-lg btn-primary btn-block" type="button" id="daoru">导入</button>
                    <p style="width:100%;height:250px;">
                        <a style="font-size: 18px;font-weight: bolder;">示例：</a></br>
                        订单：8123123123123</br>
                        备注：11.17号交货</br>
                        旺旺：xiaoming</br>
                        QQ：972102275</br>
                        账号：972102275</br>
                        密码：123123123</br>
                        大区：安卓QQ</br>
                        小区：123</br>
                        选图：王者荣耀_金币/经验</br>
                        次数：500</br>
                        QQ，旺旺，二选一，不用全填，也可全都不填。
                    </p>
                </div>

            </div>


        </div>
    </div>
    </form>

    <!-- 确认 -->
    <div class="modal fade " id="queren" tabindex="-1" role="dialog"  >
        <div class="modal-dialog" role="document" style="width:400px;">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >请确认信息</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table" style="margin-bottom:0px;">
                            <tr>
                                <td style="width:120px;">订单号：</td>
                                <td>
                                    <input type="text"  class="form-control"  id="show_order_id" />
                                    <label style="line-height:34px;margin-left:20px;"><input type="checkbox" id="mark_input" disabled   />标记</label>
                                    <label style="line-height:34px;margin-left:10px;"><input type="checkbox" id="jiaji_input" disabled   />加急</label>
                                    <div style="clear:both;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:120px;">备注：</td>
                                <td><input type="text"  class="form-control"  id="show_remark" /></td>
                            </tr>
                            <tr>
                                <td style="width:120px;">旺旺/QQ：</td>
                                <td><input type="text"  class="form-control"  id="show_wangwang" required  /></td>
                            </tr>
                            <tr>
                                <td style="width:120px;">游戏账号：</td>
                                <td><input type="text"  class="form-control"  id="show_number"  disabled/></td>
                            </tr>
                            <tr>
                                <td>游戏密码：</td>
                                <td><input type="text"  class="form-control" id="show_pass" disabled/></td>
                            </tr>
                            <tr>
                                <td>大区：</td>
                                <td><input type="text"  class="form-control" id="show_area" disabled/></td>
                            </tr>
                            <tr>
                                <td>小区：</td>
                                <td><input type="text"  class="form-control" id="show_xiaoqu"   disabled /></td>
                            </tr>
                            <tr>
                                <td>刷图选择：</td>
                                <td><input type="text"  class="form-control" id="show_map" disabled/></td>
                            </tr>
                            <tr>
                                <td>刷图次数：</td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" class="form-control" disabled id="show_savetime"/>
                                        <span class="input-group-addon">次</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>消耗点数：</td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" class="form-control" disabled id="show_zong"/>
                                        <span class="input-group-addon">点</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>养号模式：</td>
                                <td><input type="text"  class="form-control" id="show_mode" disabled/></td>
                            </tr>
                            <tr id="super_trr">
                                <td>截止时间：</td>
                                <td>
                                    <div class="input-group">
                                        <input type="text"  class="form-control" id="show_end_date" disabled/>
                                        <span class="input-group-addon">23:59:59</span>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td>上号时间：</td>
                                <td>
                                    <div class="input-group">
                                        <input type="text"  class="form-control" id="show_shanghaotime" disabled/>
                                        <span class="input-group-addon">小时后开始排队</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>当前时间：</td>
                                <td>
                                    <input type="text"  class="form-control show_login_time"  disabled/>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary" id="querenbutton">确认</button>
                    </div>
                </div>
        </div>
    </div>

    <!-- 系数存储 -->
    @if($xishus)
        @foreach($xishus as $k=>$vo)
            <input type="hidden" id="{{ $k }}" value="{{ $vo }}" />
        @endforeach
    @endif


    <script>
        $(function(){
            @if(!isset($info))
            var map_select_number = $('#map_select option:selected').attr('number');
            $('input[name=save_time]').val(map_select_number);
            @endif

            $('#daoru').click(function(){
                var text = $("#textarea").val();//获取id为ta的textarea的全部内容
                var arr = text.split("\n");//以换行符为分隔符将内容分割成数组
                var pre = '';
                var string = '';
                var value = '';
                var temp_str = '';
                arr.map(function(vo,key){
                    //识别
                    vo = $.trim(vo);
                    pre = vo.substr(0,2);
                    //截取后边的字符串
                    string = vo.substr(3,vo.length);
                    switch (pre){
                        case '订单':
                            //将这个字符串添加到对应位置
                            $('input[name=order_id]').val(string);
                            break;
                        case 'QQ':
                            $('input[name=wangwang_type]').eq(1).attr("checked","true");
                            $('input[name=wangwang]').val(string);
                            break;
                        case '旺旺':
                            $('input[name=wangwang_type]').eq(0).attr("checked","true");
                            $('input[name=wangwang]').val(string);
                            break;
                        case '账号':
                            //将这个字符串添加到对应位置
                            $('input[name=number]').val(string);
                            break;
                        case '密码':
                            //将这个字符串添加到对应位置
                            $('input[name=pass]').val(string);
                            break;
                        case '小区':
                            //将这个字符串添加到对应位置
                            $('input[name=xiaoqu]').val(string);
                            break;
                        case '备注':
                            //将这个字符串添加到对应位置
                            $('input[name=remark]').val(string);
                            break;
                        case '大区':
                            //转换 将小写字母转换成大写
                            string = string.toUpperCase();
                            switch(string){
                                case '安卓QQ':value='AZQQ';break;
                                case '安卓微信':value='AZVX';break;
                                case '苹果QQ':value='IOSQQ';break;
                                case '苹果微信':value='IOSVX';break;
                                default:value='AZQQ';
                            }
                            $('#area_select').val(value);
                            value='';
                            break;


                        case '次数':
                            //将这个字符串添加到对应位置
                            $('input[name=save_time]').val(string);
                            break;
                        case '选图':
                            for(var i= 0;i<$('.selects').length;i++){
                                temp_str = $('.selects').eq(i).val();
                                if(string == temp_str){
                                    $('#map_select option').eq(i).attr('selected','');
                                }
                            }
                            break;

                    }
                })

            })

            //上号时间
            $('#mode_select').change(function(){
                if($('#mode_select').val() != 0){
                    $('#super_tr').show();
                }else{
                    $('#end_date').val('');
                    $('#super_tr').hide();
                }
            })


            $('#rest').click(function(){
                location.reload();
            });
            $('#querenbutton').click(function(){
                $('#superdiv').css('z-index','9999');
                $('#myForm').submit();
            })

            @if(session('pointerror'))
                alert('您的点数，不足以支付本次代刷的费用，请充值后，再上传！');
            @endif
            @if(session('isset'))
                alert('该账号有剩余代刷次数没有回收，请回收后，再上传！如有疑问，请联系QQ：972102275');
            @endif
            @if(session('spareRes'))
                alert('备用成功');
            @endif



        })

        $('#addButton').click(function(){
            $('input[name=spare]').val(0);
        })
        $('#spare').click(function(){
            $('input[name=spare]').val('beiyong');
        })


        function chekform(){
            //如果是第二次确认，则返回true
            var is_true = $('#queren').css('display');
            if(is_true == 'block'){
                return true;
            }

            if($('#mode_select').val() != 0  && $('#end_date').val() == ''){
                //如果开启养号模式 截止时间必填
                alert('截止时间必填');return false;
            }
            if($('#mode_select').val() != 0){
                $('#show_end_date').val($('#end_date').val());
                $('#super_trr').show();
            }else{
                $('#show_end_date').val('');
                $('#super_trr').hide();
            }

            var number_input = $('input[name=number]').val();
            var pass_input = $('input[name=pass]').val();

            if(number_input.indexOf('“')>=0 || number_input.indexOf('”')>=0 || number_input.indexOf('》')>=0 || number_input.indexOf('《')>=0 || number_input.indexOf('？')>=0 || number_input.indexOf(',')>=0 || number_input.indexOf('，')>=0 ||  number_input.indexOf('。')>=0||  number_input.indexOf('！')>=0){
                alert('输入不合法');return false;
            }

            if(pass_input.indexOf('“')>=0 || pass_input.indexOf('”')>=0 || pass_input.indexOf('》')>=0 || pass_input.indexOf('《')>=0 || pass_input.indexOf('？')>=0 || pass_input.indexOf(',')>=0 || pass_input.indexOf('，')>=0 || pass_input.indexOf('。')>=0||  pass_input.indexOf('！')>=0){
                alert('输入不合法');return false;
            }

            var myReg = /^[\u4e00-\u9fa5]+$/;
            if(myReg.test(number_input)){
                alert('输入不合法');return false;
            }
            if(myReg.test(pass_input)){
                alert('输入不合法');return false;
            }



            //把值赋过去
            $('#show_number').val($('input[name=number]').val());
            $('#show_pass').val($('input[name=pass]').val());
            $('#show_area').val($('#area_select option:selected').text());
            $('#show_map').val($('#map_select option:selected').text());
            $('#show_mode').val($('#mode_select option:selected').text());
            $('#show_savetime').val($('input[name=save_time]').val());
            $('#show_shanghaotime').val($('input[name=shanghao_time]').val());
            $('#show_order_id').val($('input[name=order_id]').val());
            $('#show_wangwang').val($('input[name=wangwang]').val());
            $('#show_xiaoqu').val($('input[name=xiaoqu]').val());
            $('#show_remark').val($('input[name=remark]').val());
            if($('#checkbox_mark').is(":checked")){
                $('#mark_input').attr('checked',true);
            }
            if($('#checkbox_ji').is(":checked")){
                $('#jiaji_input').attr('checked',true);
            }



            //消耗点数赋值
            var area_val = $('#area_select option:selected').val();
            //选中的前缀
            var pre = $('#map_select option:selected').attr('data');


            var cishu = parseInt($('input[name=save_time]').val());
            var xishu = parseInt($('#'+area_val+pre).val());
            $('#show_zong').val(cishu*xishu);




            $('#queren').modal('show')
            //弹框让他确认
            return false;
        }
        function issbccase(source){
            if   (source == ''){
                return   true;
            }
            //var   reg= /^[\w\u4e00-\u9fa5\uf900-\ufa2d]*$/;
            //var reg = /[^\uff00-\uffff]/ ;
            if(reg.test(source)){
                return   false;
            }else{
                return   true;
            }
        }

        function checkStr(){

        }

        $('#map_select').change(function(){
            var pre = $('#map_select option:selected').attr('number');
            $('input[name=save_time]').val(pre);
        })
    </script>
    <script>
        function p(s) {
            return s < 10 ? '0' + s: s;
        }

        var myDate = new Date();
        //获取当前年
        var year=myDate.getFullYear();
        //获取当前月
        var month=myDate.getMonth()+1;
        //获取当前日
        var date=myDate.getDate();
        var h=myDate.getHours();       //获取当前小时数(0-23)
        var m=myDate.getMinutes();     //获取当前分钟数(0-59)
        var s=myDate.getSeconds();

        var now=year+'-'+p(month)+"-"+p(date)+" "+p(h)+':'+p(m)+":"+p(s);

        $('.show_login_time').val(now);
    </script>


@stop
