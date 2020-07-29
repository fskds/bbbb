<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="/lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="/css/public.css" media="all">
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="layui-form layuimini-form">
	<form class="layui-form" action="">
		{{method_field('put')}}
		<input type="hidden" name="id" value="{{$permission->id}}">
        @include('backend.permission._form')
	</form>
</div>
<script src="/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/js/lay-config.js" charset="utf-8"></script>
<script>

    layui.use(['form','iconPickerFa'], function () {
        var $ = layui.$,
            iconPickerFa = layui.iconPickerFa,
			form = layui.form,
            layer = layui.layer;

        iconPickerFa.render({
            // 选择器，推荐使用input
            elem: '#iconPicker',
            // fa 图标接口
            url: "/lib/font-awesome-4.7.0/less/variables.less",
            // 是否开启搜索：true/false，默认true
            search: true,
            // 是否开启分页：true/false，默认true
            page: true,
            // 每页显示数量，默认12
            limit: 12,
            // 点击回调
            click: function (data) {
                console.log(data);
            },
            // 渲染成功后的回调
            success: function (d) {
                console.log(d);
            }
        });
        form.on('switch(switch)', function (data) {
            if (this.checked) {
                $("input[name='status']").val('1');
            } else {
                $("input[name='status']").val('0');
            }

        });
        //监听提交
        form.on('submit(editsaveBtn)', function (data) {
				var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引  
				$.ajax({
					type:'put', 
					data:data.field, 
					url: '{{route('admin.permission.update',$permission->id)}}',
					dataType: 'json',
					success: function (res) {
						if(res.code ==1)
						{
							parent.layui.table.reload('currentTableId'); //重载表格	
							parent.layer.close(index); //再执行关闭	
							parent.layer.msg(res.msg, {icon: res.code});
                            parent.location.reload(); //重载界面
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