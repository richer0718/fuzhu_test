<meta charset="utf-8">
<meta name="renderer" content="webkit">
<link rel="stylesheet" type="text/css" href="{{asset('admin/css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/css/style.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/css/font-awesome.min.css')}}">
<script src="{{asset('admin/js/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('layer/2.2/layer.js')}}"></script>
<!--[if gte IE 9]>
<script src="{{asset('admin/js/jquery-1.11.1.min.js')}}" type="text/javascript"></script>
<script src="{{asset('admin/js/html5shiv.min.js')}}" type="text/javascript"></script>
<script src="{{asset('admin/js/respond.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/selectivizr-min.js')}}" type="text/javascript"></script>
<![endif]-->
<meta name="_token" content="{{ csrf_token() }}" />
<style>
    .breadcrumb li a{
        background-color: lightgreen;
        padding:3px;
        border-radius: 3px;
    }

    #main{
        width:90%;
        margin-left:10%;
    }
</style>
