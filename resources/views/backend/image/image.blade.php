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
            <legend>搜索信息</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">标题</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" autocomplete="off" class="layui-input">
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
				@can('site.image.destroy')
                <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"> 添加 </button>
                @endcan
                @can('site.image.create')
				<button class="layui-btn layui-btn-sm layui-btn-danger data-delete-btn" lay-event="listDelete"> 删除 </button>
				@endcan
				<button class="layui-btn layui-btn-sm layui-btn-warm data-delete-btn" lay-event="hasDel"> 回收站 </button>
            </div>
        </script>
		<script type="text/html" id="toolbarDel">
            <div class="layui-btn-container">
				<button class="layui-btn layui-btn-sm layui-btn-warm data-delete-btn" lay-event="toolbar"> 返回 </button>
            </div>
        </script>
        <table id="currentTableId" lay-filter="currentTableId"></table>
        <script type="text/html" id="currentTableBar">
			<div class="layui-btn-group">
				@can('site.image.edit')
				<a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
				@endcan
				@can('site.image.destroy')
				<a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete " lay-event="delete">删除</a>
				@endcan
			</div>
        </script>
        <script type="text/html" id="delTableBar">
			<div class="layui-btn-group">
				@can('site.image.edit')
				<a class="layui-btn layui-btn-normal layui-btn-xs data-count-hasedit" lay-event="hasedit">恢复</a>
				@endcan
				@can('site.image.destroy')
				<a class="layui-btn layui-btn-xs layui-btn-danger data-count-hasdel " lay-event="hasdel">删除</a>
				@endcan
			</div>
        </script>
    </div>
</div>
<script src="/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
@can('site.image')
<script>
    layui.use(['form', 'table'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table;

        var tableIns = table.render({
            elem: '#currentTableId',
            url: '{{ route('site.image.data') }}',
			where: {model: 'image'},
            toolbar: '#toolbarDemo',
			cache: false,
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '提示',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-tips'
            }],
            cols: [[
                {checkbox: true,fixed: true},
                {field: 'id', title: 'id',width:40 ,align: 'center'},
                {field: 'name', title: '图片' , templet:function(d){
                    return "<img src='"+d.path+"/"+d.name+"'>";
                }},
                {field: 'title', title: '标题',width:150},
                {field: 'size', title: '尺寸',width:150},
                {templet: '#currentTableBar',width: 150, align: 'center', title: '操作'}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });
        // 监听搜索操作
        form.on('submit(data-search-btn)', function (data) {
            var result = data.field.name;
            //执行搜索重载
            table.reload('currentTableId', {
                page: {
                    curr: 1
                }
                , where: {
					model: 'search',
                    name: result
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
            if (obj.event === 'add') {  // 监听添加操作
                var index = layer.open({
                    title: '添加用户',
                    type: 2,
                    shade: 0.2,
                    shadeClose: false,
                    area: ['450px', '450px'],
                    content: '{{ route('site.image.create') }}',
					btn: ['确定', '取消'],
					yes: function(index, layero){
					//点击确认触发 iframe 内容中的按钮提交
					var submit = layero.find('iframe').contents().find("#addsaveBtn");
						submit.click();
					},
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
            }else if (obj.event === 'listDelete') {  // 监听批量删除操作
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
                        $.post("{{ route('site.image.destroy') }}", {_method: 'delete',_token:'{{csrf_token()}}',ids: ids}, function(res) {
                            if (res.code == 1) {
                                tableIns.reload();
                            }
                            layer.close(index);
                            layer.msg(res.msg, {icon: 6});
                        });
                    })
                } else {
                    layer.msg('请选择删除项', {icon: 5});
                }
            }else if (obj.event === 'hasDel') {
				tableIns.reload({
					where: {model: 'hasDel'},
					toolbar: '#toolbarDel',
					cols: [[
                        {checkbox: true,fixed: true},
                        {field: 'id', title: 'id',width:40 ,align: 'center'},
                        {field: 'name', title: '图片' , templet:function(d){
                            return "";
                        }},
                        {field: 'title', title: '标题',width:150},
                        {field: 'size', title: '尺寸',width:150},
                        {templet: '#delTableBar',width: 150, align: 'center', title: '操作'}
					]],
				});
				
			}else if (obj.event === 'toolbar') {
				tableIns.reload({
					where: {model: 'image'},
					toolbar: '#toolbarDemo',
					cols: [[
                        {checkbox: true,fixed: true},
                        {field: 'id', title: 'id',width:40 ,align: 'center'},
                        {field: 'name', title: '图片' , templet:function(d){
                            return "<img src=''>";
                        }},
                        {field: 'title', title: '标题',width:150},
                        {field: 'size', title: '尺寸',width:150},
                        {templet: '#currentTableBar',width: 150, align: 'center', title: '操作'}
					]],
				});
				
			}
        });

        //监听表格复选框选择
        table.on('checkbox(currentTableId)', function (obj) {
            console.log(obj)
        });

        table.on('tool(currentTableId)', function (obj) {
            var data = obj.data
				,layEvent = obj.event; // 获得 lay-event 对应的值
			if (obj.event === 'edit') {  // 监听添加操作
                var index = layer.open({
                    title: '编辑用户',
                    type: 2,
                    shade: 0.2,
                    shadeClose: false,
                    area: ['450px', '500px'],
                    content: '/admin/image/' + data.id + '/edit',
					btn: ['确定', '取消'],
					yes: function(index, layero){
					//点击确认触发 iframe 内容中的按钮提交
						var submit = layero.find('iframe').contents().find("#editsaveBtn");
						submit.click();
					},

                });
            } else if (layEvent === 'role') {
				var index = layer.open({
                    title: '更新用户 ' + data.name + '[' + data.username + '] 角色',
                    type: 2,
                    shade: 0.2,
                    shadeClose: false,
                    area: ['450px', '600px'],
                    content: '/admin/image/' + data.id + '/role',
					btn: ['确定', '取消'],
					yes: function(index, layero){
					//点击确认触发 iframe 内容中的按钮提交
						var submit = layero.find('iframe').contents().find("#rolesaveBtn");
						submit.click();
					},
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
            } else if (layEvent === 'permission') {
				var index = layer.open({
                    title: '更新用户 ' + data.name + '[' + data.username + '] 权限',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: false,
                    area: ['800px', '650px'],
                    content: '/admin/image/' + data.id + '/permission',
					btn: ['确定', '取消'],
					yes: function(index, layero){
					//点击确认触发 iframe 内容中的按钮提交
					var submit = layero.find('iframe').contents().find("#permissionsaveBtn");
						submit.click();
					},
                });
				$(window).on("resize", function () {
                    layer.full(index);
                });
            } else if (layEvent === 'delete') {
				layer.confirm('真的删除行么', function (index) {
					$.post("{{ route('site.image.destroy') }}",{_method:'delete',_token:'{{csrf_token()}}',ids: [data.id]},function (res) {
                            if (res.code==1){
                                obj.del(); //删除对应行（tr）的DOM结构
								layer.close(index);
								layer.msg(res.msg,{icon:res.code});
                            }
                            layer.msg(res.msg,{icon:res.code});
                        });
                });

            } else if (layEvent === 'hasedit') {
				layer.confirm('真的恢复行么', function (index) {
					$.post("{{ route('site.image.restore') }}",{_method:'delete',_token:'{{csrf_token()}}',ids: data.id},function (res) {
                            if (res.code==1){
                                obj.del(); //删除对应行（tr）的DOM结构
								layer.close(index);
								layer.msg(res.msg,{icon:res.code});
                            }else{
								layer.msg(res.msg,{icon:res.code});
							}
                            
                        });
                });

            }
        });

    });
</script>
@endcan
</body>
</html>