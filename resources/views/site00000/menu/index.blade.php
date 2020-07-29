@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <div class="layui-btn-group ">
                @can('site.menu.destroy')
                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>
                @endcan
                @can('site.menu.create')
                    <a class="layui-btn layui-btn-sm" href="{{ route('site.menu.create') }}">添 加</a>
                @endcan
                <button class="layui-btn layui-btn-sm" id="returnParent" pid="0">返回上级</button>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="icon">
                <i class="layui-icon @{{ d.icon.class }}"></i>
            </script>
            <script type="text/html" id="options">
                <div class="layui-btn-group">
                    @can('site.menu')
                        <a class="layui-btn layui-btn-sm" lay-event="children">子菜单</a>
                    @endcan
                    @can('site.menu.edit')
                        <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
                    @endcan
                    @can('site.menu.destroy')
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
                    @endcan
                </div>
            </script>
        </div>
    </div>
@endsection

@section('script')

    @can('site.menu')
    <script>
        layui.use(['layer','table','form'],function () {
            var layer = layui.layer;
            var form  = layui.form;
            var table = layui.table;
            // 用户表格初始化
            var dataTable = table.render({
                elem: '#dataTable'
                ,height: 500
                ,url: "{{ route('site.menu.data') }}" // 数据接口
                ,page: true // 开启分页
                ,cols: [[   // 表头
                    {checkbox: true,fixed: true}
                    ,{field: 'id', title: 'ID', sort: true, width: 80}
                    ,{field: 'name', title: '菜单名称'}
                    ,{field: 'display_name', title: '显示名称'}
                    ,{field: 'route', title: '路由'}
					,{field: 'sort', title: '排序',width:60}
					,{field: 'state', title: '状态',width:60, templet:function(d){
						if(d.state == "0"){
							return "禁用";
						}else if(d.state == "1"){
							return "正常";
						}
					}}
                    ,{fixed: 'right', width: 260, align: 'center', title: '操作', toolbar: '#options'}
                ]]
            });

            // 监听工具条
            table.on('tool(dataTable)', function(obj) {
				
                var data = obj.data         // 获得当前行数据
                    ,layEvent = obj.event;  // 获得 lay-event 对应的值
                if (layEvent === 'del') {
                    layer.confirm('确认删除吗？', function(index) {
                        $.post("{{ route('site.menu.destroy') }}", {
                                _method: 'delete',
                                ids:[data.id]
                            }, function (result) {
                            if (result.code == 0) {
                                obj.del(); // 删除对应行（tr）的DOM结构
                            }
                            layer.close(index);
                            layer.msg(result.msg, {icon: 6})
                        });
                    });
                } else if(layEvent === 'edit') {
                    location.href = '/admin/menu/' + data.id + '/edit';
                } else if (layEvent === 'children') {
                    var pid = $("#returnParent").attr("pid");
                    if (data.parent_id != 0) {
                        $("#returnParent").attr("pid",pid + '_' + data.parent_id);
                    }
                    dataTable.reload({
                        where: {
                            parent_id: data.id
                        },
                        page: {
                            curr: 1
                        }
                    })
                }
            });

            // 按钮批量删除
            $("#listDelete").click(function () {
                layer.msg("由于权限重要性，系统已禁止批量删除", {icon: 5});
            });
            // 返回上一级
            $("#returnParent").click(function() {
                var pid = $(this).attr("pid");
                if (pid != '0') {
                    ids = pid.split('_');
                    parent_id = ids.pop();
                    $(this).attr("pid", ids.join('_'));
                } else {
                    parent_id = pid;
                }
                dataTable.reload({
                    where: {
                        parent_id: parent_id
                    },
                    page: {
                        curr: 1
                    }
                })
            })
        })
    </script>
    @endcan
@endsection
