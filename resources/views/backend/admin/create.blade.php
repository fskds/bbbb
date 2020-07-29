<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/css/public.css" media="all">
</head>
<body>
<div class="layui-form layuimini-form">
	<form class="layui-form" action="{{route('admin.admin.store')}}" method="post">
        @include('backend.admin._form')
	</form>
</div>
<script src="/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script>

    layui.use(['form', 'table'], function () {
        var $ = layui.$,
			form = layui.form,
            layer = layui.layer;
			
		form.verify({
			username: function (value) {
                if (value.length < 5) {
                    return '标题至少得5个字符啊';
                }
				if (value.length > 14) {
                    return '标题至少得5个字符啊';
                }
				if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
				return '用户名不能有特殊字符';
				}
				if(/(^\_)|(\__)|(\_+$)/.test(value)){
				return '用户名首尾不能出现下划线\'_\'';
				}
				if(/^\d+\d+\d$/.test(value)){
				return '用户名不能全为数字';
				}
            }
			,name: function (value) {
                if (value.length < 2) {
                    return '标题至少得5个字符啊';
                }
				if (value.length > 14) {
                    return '标题至少得5个字符啊';
                }
				if(/(^\_)|(\__)|(\_+$)/.test(value)){
				return '用户名首尾不能出现下划线\'_\'';
				}
            }
			,pass: [
			/^[\S]{6,14}$/
			,'密码必须6到12位，且不能出现空格'
			] 
		});
        //监听提交
        form.on('submit(addsaveBtn)', function (data) {
				var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引  
				$.ajax({
					type:'post', 
					data:data.field, 
					url: '{{route('admin.admin.store')}}',
					dataType: 'json',
					success: function (res) {
						if(res['code']==1)
						{
							parent.layui.table.reload('currentTableId'); //重载表格	
							parent.layer.close(index); //再执行关闭	
							parent.layer.msg(res.msg, {icon: res.code});
						}else{
							parent.layer.msg(res.msg, {icon: res.code});
						}
					},
					error:function(XMLHttpRequest, textStatus, errorThrown) {
						alert('error');
					},

				});
				return false;
        });

    });
</script>
</body>
</html>


