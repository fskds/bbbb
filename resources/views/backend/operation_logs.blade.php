<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <fieldset class="table-search-fieldset">
            <legend>搜索操作记录</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">操作用户</label>
                            <div class="layui-input-inline">
                                <input type="text" name="user_name" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">主菜单</label>
                            <div class="layui-input-inline">
                                <input type="text" name="menu_name" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">子菜单</label>
                            <div class="layui-input-inline">
                                <input type="text" name="sub_menu_name" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-primary"  lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                @can('system.role.create')
				<button class="layui-btn layui-btn-sm layui-btn-danger data-delete-btn" lay-event="listDelete"> 删除 </button>
				@endcan
            </div>
        </script>
        <table id="currentTableId" lay-filter="currentTableId"></table>
        <script type="text/html" id="currentTableBar">
			<div class="layui-btn-group">
                @can('system.log.destroy')
                    <a class="layui-btn layui-btn-danger layui-btn-sm " lay-event="delete">删除</a>
                @endcan
			</div>
        </script>
    </div>
</div>
<script src="/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
@can('system.log')
<script>
    layui.use(['form', 'table'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table;

        var tableIns = table.render({
            elem: '#currentTableId',
            url: '{{ route('admin.log.data') }}',
			where: {model: 'log'},
            toolbar: '#toolbarDemo',
			cache: false,
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '提示',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-tips'
            }],
            cols: [[
                    {checkbox: true, fixed: true}
                    ,{field: 'id', title: 'ID', sort: true, width:80}
                    ,{field: 'user_name', title: '用户名'}
                    ,{field: 'menu_name', title: '主菜单'}
                    ,{field: 'sub_menu_name', title: '子菜单'}
                    ,{field: 'ip', title: 'IP'}
                    ,{field: 'operate_name', title: '操作'}
                    ,{field: 'input', title: '操作信息'}
                    ,{field: 'created_at', title: '创建时间'}
                    ,{fixed: 'right', width: 100, align: 'center', title: '操作', toolbar: '#currentTableBar'}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });
        // 监听搜索操作
        form.on('submit(data-search-btn)', function (data) {
            //执行搜索重载
            table.reload('currentTableId', {
                page: {
                    curr: 1
                }
                , where: {
                    'user_name': data.field.user_name,
                    'menu_name': data.field.menu_name,
                    'sub_menu_name': data.field.sub_menu_name,
                }
            
            }, 'data');

            return false;
        });
        /**
         * toolbar监听事件
         */
        table.on('toolbar(currentTableId)', function (obj) {
			var data = obj.data
				,layEvent = obj.event; // 获得 lay-event 对应的值
            if (obj.event === 'listDelete') {  // 监听批量删除操作
                var checkStatus = table.checkStatus('currentTableId')
                    , data = checkStatus.data;
				var ids = []
				,hasCheck = table.checkStatus('currentTableId')
				,hasCheckData = hasCheck.data;
				if (hasCheckData.length > 0) {
                    $.each(hasCheckData,function(index,element) {
                        ids.push(element.id);
                    })
                }
				if (ids.length > 0) {
                    layer.confirm('确认删除吗？', function(index) {
                        $.post("{{ route('admin.log.destroy') }}", {_method: 'delete',_token:'{{csrf_token()}}',ids: ids}, function(res) {
                            if (res.code == 1) {
                                tableIns.reload();
                                layer.close(index);
                                layer.msg(res.msg, {icon:res.code});
                            }else{
								layer.msg(res.msg, {icon:res.code});
							}
                            
                            
                        });
                    })
                } else {
                    layer.msg('请选择删除项', {icon: 5});
                }
            }
            
        });

        //监听表格复选框选择
        table.on('checkbox(currentTableId)', function (obj) {
            console.log(obj)
        });

        table.on('tool(currentTableId)', function (obj) {
            var data = obj.data
				,layEvent = obj.event; // 获得 lay-event 对应的值
            if (layEvent === 'delete') {
				layer.confirm('真的删除行么', function (index) {
					$.post("{{ route('admin.log.destroy') }}",{_method:'delete',_token:'{{csrf_token()}}',ids: [data.id]},function (res) {
                            if (res.code==1){
                                obj.del(); //删除对应行（tr）的DOM结构
								layer.close(index);
								layer.msg(res.msg,{icon:res.code});
                            }
                            layer.msg(res.msg,{icon:res.code});
                        });
                });

            }
        });

    });
</script>
@endcan
</body>
</html>