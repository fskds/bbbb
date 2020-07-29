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
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="layui-form layuimini-form">
	<form class="layui-form layui-form-pane" action="">
		{{csrf_field()}}
		{{method_field('put')}}
		<div class="layui-form-item layui-form-text">
			<div class="layui-input-block" >
				@forelse($roles as $role)
					<input type="checkbox" name="roles[]" value="{{$role->id}}" title="{{$role->display_name}}" {{ $role->own ? 'checked' : ''  }} >
				@empty
					<div class="layui-form-mid layui-word-aux">还没有角色</div>
				@endforelse
			</div>
		</div>
		<div class="layui-form-item layui-hide">
			<div class="layui-input-block">
				<input class="layui-btn layui-btn-normal" id="rolesaveBtn" lay-submit lay-filter="rolesaveBtn" value="确认角色">
			</div>
		</div>
	</form>
</div>
<script src="/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script>

    layui.use(['form', 'table'], function () {
        var $ = layui.$,
			form = layui.form,
            layer = layui.layer;
        //监听提交
        form.on('submit(rolesaveBtn)', function (data) {
				var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引  
				$.ajax({
					type:'put', 
					data:data.field, 
					url: '{{route('admin.admin.assignRole',$user->id)}}',
					dataType: 'json',
					success: function (res) {
						if(res['code']==1)
						{
							parent.layui.table.reload('currentTableId'); //重载表格	
							parent.layer.close(index); //再执行关闭	
							parent.layer.msg(res['msg'], {icon: 1});
						}else{
							parent.layer.msg(res['msg'], {icon: 4});
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


