<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登入 - layuiAdmin</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="/layuiadmin/style/login.css" media="all">
</head>
<body>

<div class="layadmin-user-login layadmin-user-display-show" >

    <div class="layadmin-user-login-main">

        @yield('content')
    </div>

    <div class="layui-trans layadmin-user-login-footer">
        <p>© 2018 <a href="" target="_blank">技术支持</a></p>
    </div>
</div>

<script src="/layuiadmin/layui/layui.js"></script>
<script>
    layui.config({
        base: '/layuiadmin/' // 静态资源所在路径
    }).use(['layer'], function () {
        var layer = layui.layer;
        // 表单提示信息
        @if(count($errors)>0)
            @foreach($errors->all() as $error)
                layer.msg("{{$error}}", {icon:5});
                @break
            @endforeach
        @endif
        // 正确提示
        @if(session('success'))
            layer.msg("{{session('success')}}", {icon:6});
        @endif
    })
    if (window.top.location.href != location.href) {
        window.parent.location.reload(true);
    }
</script>
</body>
</html>