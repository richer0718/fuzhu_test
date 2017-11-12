<!doctype html>
<html lang="zh-CN">
<head>
    @include('layouts.common_admin')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/login.css')}}">
</head>

<body class="user-select">
<div class="container">
    <div class="siteIcon"><img src="{{asset('admin/images/icon/icon.png')}}" alt="" data-toggle="tooltip" data-placement="top" title="欢迎使用飞飞辅助系统" draggable="false" /></div>
    <form action="{{url('kefu/loginRes')}}" method="post" autocomplete="off" class="form-signin">
        <h2 class="form-signin-heading">登录</h2>
        <label for="userName" class="sr-only">用户名</label>
        <input type="text" id="userName" name="username" class="form-control" placeholder="请输入用户名" required autofocus autocomplete="off" maxlength="10">
        <label for="userPwd" class="sr-only">密码</label>
        <input type="password" id="userPwd" name="password" class="form-control" placeholder="请输入密码" required autocomplete="off" maxlength="18">
        <input type="text" name="code" class="form-control" placeholder="请输入验证码" required autofocus autocomplete="off" maxlength="10">
        <img src="{{ url('captcha') }}" onclick="this.src='{{ url('captcha/mews') }}?r='+Math.random();" alt="">
        <!--
        <select name="type" class="form-control" style="margin-bottom:10px;">
            <option value="0">平台管理员</option>
            <option value="1">社区管理员</option>
        </select>
        -->

        <a><button class="btn btn-lg btn-primary btn-block" type="submit" id="signinSubmit">登录</button></a>
        {{ csrf_field() }}
    </form>
    <!--
    <div class="footer">
        <p><a href="{{url('admin/index')}}" data-toggle="tooltip" data-placement="left" title="不知道自己在哪?">回到后台 →</a></p>
    </div>
    -->
</div>


<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<script>
    @if(session('code') == 'error')
        alert('验证码错误');
    @endif

    $('#reg').click(function(){
        $('#reg-box').modal('show');
    })
    $('#findpass').click(function(){
        $('#findpass-box').modal('show');
    })





$('[data-toggle="tooltip"]').tooltip();
    window.oncontextmenu = function(){
        //return false;
    };
    $('.siteIcon img').click(function(){
        window.location.reload();
    });
    $('#signinSubmit').click(function(){
        if($('#userName').val() === ''){
            $(this).text('用户名不能为空');
        }else if($('#userPwd').val() === ''){
            $(this).text('密码不能为空');
        }else{
            $(this).text('请稍后...');
        }
    });

    @if (session('status') == 'error')
        alert('登陆失败！');
    @endif





    function chekform(){
        //检测两次输入的密码是否一致
        if( $('input[name=repassword]').val() !=  $('input[name=newpassword]').val()  ){
            alert('两次输入密码不一致');return false;
        }
        //检验手机格式
        if (!isPhoneNo($('input[name=tel]').val())) {
            alert("手机号码格式不正确！");
            return false;
        }
        return true;
    }

    function isPhoneNo(phone) {
        var pattern = /^1[34578]\d{9}$/;
        return pattern.test(phone);
    }




    function chekform2(){
        //检测两次输入的密码是否一致
        if( $('input[name=repassword2]').val() !=  $('input[name=newpassword2]').val()  ){
            alert('两次输入密码不一致');return false;
        }
    }

</script>
</body>
</html>
