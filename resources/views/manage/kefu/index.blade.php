@extends('layouts.manage_common')
@section('right-box')
    <style>
        #mytable tr td{
            border:1px solid #000000;
        }
    </style>
    <script src="{{ asset('js/laydate/laydate.js') }}"></script>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main"  >

        <!--
        <form method="post">
        <table class="table">
            <tr>





            </tr>

            <tr>
                <td colspan="7">
                    <button class="btn btn-default" type="submit">搜索</button>
                    <button class="btn btn-default" type="button" onclick="location.href='{{Request::getRequestUri()}}' ">重置</button>
                    <!--<button class="btn btn-default" type="button">导出</button>-->
            <!--
            </td>
            </tr>
        </table>
            {{ csrf_field() }}
        </form>
            -->


        <ol class="breadcrumb">
            <li><a data-toggle="modal" href="{{ url('manage/addKefu') }}">新增账号</a></li>
        </ol>

        <h1 class="page-header">基本信息 <span class="badge"></span></h1>
        <div class="table-responsive"  >
            <table class="table table-striped table-hover" id="mytable">
                <thead>
                <tr>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">ID</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">账号</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg">密码</span></th>
                    <th><span class="glyphicon glyphicon-signal"></span> <span class="visible-lg">职位</span></th>
                    <th><span class="glyphicon glyphicon-camera"></span> <span class="visible-lg">姓名</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">电话</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">备注</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">权限</span></th>
                    <th><span class="glyphicon glyphicon-pencil"></span> <span class="visible-lg">操作</span></th>
                </tr>
                </thead>
                <tbody>
                @unless(!$res)
                    @foreach($res as $k => $vo)
                        <tr>
                            <td>{{ $k + 1  }}</td>
                            <td>{{$vo -> username }}</td>
                            <td>{{$vo -> password }}</td>
                            <td>@if($vo -> zhiwei == 1)售前@else售后@endif</td>
                            <td>{{$vo -> name}}</td>
                            <td>{{$vo -> tel}}</td>
                            <td>{{$vo -> remark}}</td>
                            <td>{{ $vo -> quanxian_list }}</td>
                            <td><a href="{{ url('manage/editKefu').'/'.$vo -> id }}">修改</a><a onclick="delete_data({{ $vo -> id }})">删除</a></td>

                        </tr>
                    @endforeach
                @endunless
                </tbody>
                @if(count($res))
                <tfoot>
                    <tr>

                        <td colspan="9">{{ $res -> links() }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>


    <script>
        @if(session('address') == 'success')
            alert('添加成功');
        @endif
        @if(session('address') == 'error')
            alert('添加失败');
        @endif
        @if(session('updateres') == 'success')
            alert('修改成功');
        @endif

        @if(session('deleteres') == 'success')
            alert('删除成功');
        @endif
        @if(session('deleteres') == 'error')
            alert('删除失败');
        @endif



    </script>
    <script>
        function delete_data(id){
            if(confirm('您确定删除么？')){
                location.href='{{ url('manage/deleteKefu') }}'+'/'+id;
            }
        }
        $(function(){
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