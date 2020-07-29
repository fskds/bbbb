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
            <legend>数据备份</legend>
            <div style="margin: 10px 10px 10px 10px">
                本功能在恢复备份数据的同时，将全部覆盖原有数据，请确认是否需要恢复，以免造成数据损失</br>
                数据恢复功能只能恢复由当前版本导出的数据文件，其他软件导出格式可能无法识别；
            </div>
        </fieldset>
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                @can('system.role.create')
				<button class="layui-btn layui-btn-sm layui-btn-normal data-delete-btn" lay-event="add"> 添加备份 </button>
				@endcan
            </div>
        </script>
        <table id="currentTableId" lay-filter="currentTableId"></table>
        <form id="layui-form" class="layui-form" action="{{ route('system.sql.download') }}" method="post">
            <input type="hidden" name="filename" >
            {{csrf_field()}}
        </form>
        <script type="text/html" id="currentTableBar">
			<div class="layui-btn-group">
                @can('system.sql.recover')
                    <a class="layui-btn layui-btn-danger layui-btn-sm " lay-event="recover">恢复</a>
                @endcan
                @can('system.sql.download')
                    <a class="layui-btn layui-btn-normal layui-btn-sm " lay-event="download">下载</a>
                @endcan
                @can('system.sql.destroy')
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
            url: '{{ route('system.sql.data') }}',
            toolbar: '#toolbarDemo',
			cache: false,
            cols: [[
                    {checkbox: true, fixed: true}
                    ,{field: 'filename', title: '文件名'}
                    ,{field: 'filesize', title: '文件大小',width: 100,}
                    ,{field: 'time', title: '创建时间',width: 200,}
                    ,{fixed: 'right', width: 250, align: 'center', title: '操作', toolbar: '#currentTableBar'}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });
        /**
         * toolbar监听事件
         */
        table.on('toolbar(currentTableId)', function (obj) {
			var data = obj.data
				,layEvent = obj.event; // 获得 lay-event 对应的值
            if (obj.event === 'add') {  // 监听添加操作
                layer.confirm('确认添加备份', function (index) {
                    $.post("{{ route('system.sql.store') }}",{_method:'post',_token:'{{csrf_token()}}'},function (res) {
                        if (res.code==1){
                            table.reload('currentTableId');
                            layer.msg(res.msg,{icon:res.code});
                        }
                        layer.msg(res.msg,{icon:res.code});
                    });
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
            if (layEvent === 'recover') {
				layer.confirm('真的恢复数据吗', function (index) {
					$.post("{{ route('system.sql.recover')}}",{_method:'post',_token:'{{csrf_token()}}',filename: data.filename},function (res) {
                            if (res.code==1){
								layer.close(index);
								layer.msg(res.msg,{icon:res.code});
                            }
                            layer.msg(res.msg,{icon:res.code});
                        });
                });

            }else if (layEvent === 'delete') {
				layer.confirm('真的删除数据么', function (index) {
					$.post("{{ route('system.sql.destroy') }}",{_method:'delete',_token:'{{csrf_token()}}',filename: data.filename},function (res) {
                            if (res.code==1){
                                obj.del(); //删除对应行（tr）的DOM结构
								layer.close(index);
								layer.msg(res.msg,{icon:res.code});
                            }
                            layer.msg(res.msg,{icon:res.code});
                        });
                });

            }else if (layEvent === 'download') {

                
                
				layer.confirm('真的下载数据么', function (index) {
                    $('#layui-form input[name=filename]').val(data.filename);
                    $('#layui-form').submit();
                    layer.close(index);
					// $.post("{{ route('system.sql.download') }}",{_method:'post',_token:'{{csrf_token()}}',filename: data.filename},function (res) {
                            // if (res.code==1){
								// layer.close(index);
								// layer.msg(res.msg,{icon:res.code});
                            // }
                            // layer.msg(res.msg,{icon:res.code});
                        // });
                });

            }
        });

    });
</script>
@endcan
</body>
</html>