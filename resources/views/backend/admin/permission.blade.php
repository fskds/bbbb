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
	<form class="layui-form layui-form-pane"action="{{route('admin.admin.assignPermission',$user->id)}}" method="post">
		{{csrf_field()}}
		{{method_field('put')}}
		<div class="layui-form-item layui-form-text">
			<div class="layui-input-block" >
				@forelse($permissions as $first)
                    <div class="cate-first"><input id="menu{{$first['id']}}" type="checkbox" name="permissions[]" value="{{$first['id']}}" title="{{$first['display_name']}}" lay-skin="primary" {{$first['own'] ?? ''}} ></div>
                    @if(isset($first['child']))
                        <div class="cate-second">
                        @foreach($first['child'] as $second)
                            <input id="menu{{$first['id']}}-{{$second['id']}}" type="checkbox" name="permissions[]" value="{{$second['id']}}" title="{{$second['display_name']}}" lay-skin="primary" {{$second['own'] ?? ''}}>
                            @if($second['child']->count())
                                <div class="cate-third">
                                @foreach($second['child'] as $third)
                                    <input id="menu{{$first['id']}}-{{$second['id']}}-{{$third['id']}}" type="checkbox" name="permissions[]" value="{{$third['id']}}" title="{{$third['display_name']}}" lay-skin="primary" {{$third['own'] ?? ''}}>
                                    @if($third['child']->count())
                                        <div class="cate-fourth">
                                        @foreach($third['child'] as $fourth)
                                            <input type="checkbox" id="menu{{$first['id']}}-{{$second['id']}}-{{$third['id']}}-{{$fourth['id']}}" name="permissions[]" value="{{$fourth['id']}}" title="{{$fourth['display_name']}}" lay-skin="primary" {{$fourth['own'] ?? ''}}>
                                        @endforeach
                                        </div>    
                                    @endif
                                @endforeach
                                </div>
                            @endif
                        @endforeach
                        </div>
                    @endif
                @empty
                    <div style="text-align: center;padding:20px 0;">
                        无数据
                    </div>
                @endforelse
            </div> 
		</div>
		<div class="layui-form-item layui-hide">
			<div class="layui-input-block">
				<input class="layui-btn layui-btn-normal" id="permissionsaveBtn" lay-submit lay-filter="permissionsaveBtn" value="确认权限">
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

        form.on('submit(permissionsaveBtn)', function (data) {
				var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引  
				$.ajax({
					type:'put', 
					data:data.field, 
					url: '{{route('admin.admin.assignPermission',$user->id)}}',
					dataType: 'json',
					success: function (res) {
						if(res.code==1)
						{
							parent.layui.table.reload('currentTableId'); //重载表格	
							parent.layer.close(index); //再执行关闭	
							parent.layer.msg(res.msg, {icon: 1});
						}else{
							parent.layer.msg(res.msg, {icon: 4});
						}
					},
					error:function(XMLHttpRequest, textStatus, errorThrown) {
						layer.alert(JSON.stringify(XMLHttpRequest));
					},

				});
				return false;
        });
        
		form.on('checkbox', function (data) {
            var check = data.elem.checked;//是否选中
            var checkId = data.elem.id;//当前操作的选项框
            if (check) {
                var ids = checkId.split("-");
                if (ids.length == 4) {
                    $("#" + (ids[0] + '-' + ids[1]+ '-' + ids[2])).prop("checked", true);
                    $("#" + (ids[0] + '-' + ids[1])).prop("checked", true);
                    $("#" + (ids[0])).prop("checked", true);
                } else if (ids.length == 3) {
                    $("#" + (ids[0])).prop("checked", true);
                    $("#" + (ids[0] + '-' + ids[1])).prop("checked", true);
                    $("input[id*=" + ids[0] + '-' + ids[1] + '-' + ids[2] + "-]").each(function(i, ele) {
                        $(ele).prop("checked", true);
                    });
                } else if (ids.length == 2) {
                    $("#" + (ids[0])).prop("checked", true);
                    $("input[id*=" + ids[0] + '-' + ids[1] + "-]").each(function(i, ele) {
                        $(ele).prop("checked", true);
                    });

                } else if (ids.length == 1){
                    $("input[id*=" + ids[0] + "-]").each(function(i, ele) {
                        $(ele).prop("checked", true);
                    });
                }
            } else {
                var ids = checkId.split("-");
                if (ids.length == 3) {
                    $("input[id*=" + ids[0] + '-' + ids[1] + '-' + ids[2] + "-]").each(function(i, ele) {
                        $(ele).prop("checked", false);
                    });
                } else if (ids.length == 2) {
                    $("input[id*=" + ids[0] + '-' + ids[1] + "-]").each(function(i, ele) {
                        $(ele).prop("checked", false);
                    });
                } else if (ids.length == 1) {
                    $("input[id*=" + ids[0] + "-]").each(function(i, ele) {
                        $(ele).prop("checked", false);
                    });
                }
            }
            form.render();
        });

    });
	
	
	
	
</script>
</body>
</html>


