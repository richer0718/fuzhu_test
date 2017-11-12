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
    <form id="myForm" method="post" action="{{ url('manage/addKefuRes') }}" onsubmit="return chekform()">
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" >
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">添加账号</h1>

                <table class="table table-striped table-bordered" style="width:450px;">
                    <tr>
                        <td style="width:120px;">账号：</td>
                        <td><input type="text"  class="form-control"  name="username" value="{{ old('username')  }}" maxlength="10" required  /></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">密码：</td>
                        <td><input type="text"  class="form-control"  name="password" value="{{ old('password')  }}" maxlength="20" required  /></td>
                    </tr>
                    <tr>
                        <td>职位：</td>
                        <td><label><input type="radio" name="zhiwei" value="1" checked />售前  </label> <label><input type="radio" name="zhiwei" value="2" />售后 </label></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">姓名：</td>
                        <td><input type="text"  class="form-control"  name="name" value="{{ old('name')  }}" required  /></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">电话：</td>
                        <td><input type="number"  class="form-control"  name="tel" value="{{ old('tel')  }}" required  /></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">备注：</td>
                        <td><input type="text"  class="form-control"  name="remark" value="{{ old('remark')  }}" required  /></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">权限：</td>
                        <td><label><input type="checkbox" name="quanxian[]" value="1" />上传</label>  <label><input type="checkbox" name="quanxian[]" value="2" />查询</label>  <label><input type="checkbox" name="quanxian[]" value="3" />问题订单</label>  <label><input type="checkbox" name="quanxian[]" value="4" />完成订单</label></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-success" type="submit" id="addButton">添加</button>
                            <button class="btn btn-default" type="button" id="rest">重置</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    </form>


    <script>

        @if(session('isset'))
            alert('该用户名已存在');
        @endif



        $(function(){
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
                $('#myForm').submit();
            })




        })
        function chekform(){

            return true;
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
    </script>


@stop