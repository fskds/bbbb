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
                            <label class="layui-form-label">导航</label>
                            <div class="layui-input-inline">
                                <input type="text" name="search_name" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-primary" id="data-search-btn" lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                        </div>
                    </div>
                <form>
            </div>
        </fieldset>
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
				@can('site.nav.create')
                <button class="layui-btn layui-btn-normal layui-btn-sm data-add-btn" lay-event="add"> 添加 </button>
                @endcan
				<button class="layui-btn layui-btn-sm layui-btn-warm data-delete-btn" lay-event="hasDel"> 回收站 </button>
                <button class="layui-btn layui-btn-sm layui-btn-warm data-delete-btn" lay-event="btn-expand"> 全部展开 </button>
                <button class="layui-btn layui-btn-sm layui-btn-warm data-delete-btn" lay-event="btn-fold"> 全部折叠 </button>
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
				@can('site.nav.edit')
				<a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
				@endcan
				@can('site.nav.destroy')
				<a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete " lay-event="delete">删除</a>
				@endcan
			</div>
        </script>
        <script type="text/html" id="delTableBar">
			<div class="layui-btn-group">
				@can('site.nav.destroy')
				<a class="layui-btn layui-btn-normal layui-btn-xs data-count-hasedit" lay-event="hasedit">恢复</a>
				@endcan
				@can('site.nav.destroy')
				<a class="layui-btn layui-btn-xs layui-btn-danger data-count-hasdel " lay-event="hasdel">删除</a>
				@endcan
			</div>
        </script>
    </div>
</div>
<script src="/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/js/lay-config.js" charset="utf-8"></script>
@can('system.admin')
<script>
    layui.use(['treetable', 'form'], function () {
        var $ = layui.jquery,
            table = layui.table,
            form = layui.form,
            treetable = layui.treetable;

            var tableIns = treetable.render({
                treeColIndex: 2,
                treeSpid: 0,
                treeIdName: 'id',
                treePidName: 'pid',
                toolbar: '#toolbarDemo',
                url: '{{ route('site.nav.data') }}',
                where: {model: 'nav'},
                elem: '#currentTableId',
                page: false,
                cols: [[
                    {field: 'id', title: 'id',width:40 ,align: 'center'},
                    {field: 'name', title: '栏目名称',width:300 },
                    {field: 'href', title: '链接地址'},
                    {field: 'target', title: '打开窗口',width: 120, templet:function(d){
						if(d.target == "_self"){
							return "本窗口";
						}else if(d.target == "_blank"){
							return "新窗口";
						}else if(d.target == "_parent"){
							return "父窗口";
						}else if(d.target == "_top"){
							return "顶层窗口";
						}else if(d.target == ""){
							return "";
						}
					}},
                    {field: 'status', title: '显示',width:100, templet:function(d){
						if(d.status == "0"){
							return "否";
						}else if(d.status == "1"){
							return "是";
						}
					}},
                    {field: 'sort', title: '排序',width: 80},
                    {templet: '#currentTableBar',width: 150, align: 'center', title: '操作'}
                ]],
                done: function () {
                    layer.closeAll('loading');
                }
            });
        // 搜索操作
        form.on('submit(data-search-btn)', function (data) {
            var keyword = data.field.search_name;
            //执行搜索重载
            var searchCount = 0;
            $('#currentTableId').next('.treeTable').find('.layui-table-body tbody tr td').each(function () {
                $(this).css('background-color', 'transparent');
                var text = $(this).text();
                if (keyword != '' && text.indexOf(keyword) >= 0) {
                    $(this).css('background-color', 'rgba(250,230,160,0.5)');
                    if (searchCount == 0) {
                       treetable.expandAll('#currentTableId');
                       $('html,body').stop(true);
                       $('html,body').animate({scrollTop: $(this).offset().top - 150}, 500);
                    }
                    searchCount++;
                }
            });
            if (keyword == '') {
                layer.msg("请输入搜索内容", {icon: 5});
            } else if (searchCount == 0) {
                layer.msg("没有匹配结果", {icon: 5});
            }
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
                    title: '添加导航',
                    type: 2,
                    shade: 0.2,
                    shadeClose: false,
                    area: ['450px', '580px'],
                    content: '{{ route('site.nav.create') }}',
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
            }else if (obj.event === 'hasDel') {
                treetable.render({
                    treeColIndex: 2,
                    treeSpid: 0,
                    treeIdName: 'id',
                    treePidName: 'pid',
                    toolbar: '#toolbarDel',
                    url: '{{ route('site.nav.data') }}',
                    where: {model: 'hasDel'},
                    elem: '#currentTableId',
                    page: false,
                    cols: [[
                        {field: 'id', title: 'id',width:40 ,align: 'center'},
                        {field: 'name', title: '栏目名称',width:300 },
                        {field: 'href', title: '链接地址'},
                        {field: 'target', title: '打开窗口',width: 120, templet:function(d){
                            if(d.target == "_self"){
                                return "本窗口";
                            }else if(d.target == "_blank"){
                                return "新窗口";
                            }else if(d.target == "_parent"){
                                return "父窗口";
                            }else if(d.target == "_top"){
                                return "顶层窗口";
                            }else if(d.target == ""){
                                return "";
                            }
                        }},
                        {field: 'status', title: '显示',width:100, templet:function(d){
                            if(d.status == "0"){
                                return "否";
                            }else if(d.status == "1"){
                                return "是";
                            }
                        }},
                        {field: 'sort', title: '排序',width: 80},
                        {templet: '#delTableBar',width: 150, align: 'center', title: '操作'}
                    ]],
                    done: function () {
                        layer.closeAll('loading');
                    }
                });
			}else if (obj.event === 'toolbar') {
				treetable.render({
                treeColIndex: 2,
                treeSpid: 0,
                treeIdName: 'id',
                treePidName: 'pid',
                toolbar: '#toolbarDemo',
                url: '{{ route('site.nav.data') }}',
                where: {model: 'nav'},
                elem: '#currentTableId',
                page: false,
                cols: [[
                    {field: 'id', title: 'id',width:40 ,align: 'center'},
                    {field: 'name', title: '栏目名称',width:300 },
                    {field: 'href', title: '链接地址'},
                    {field: 'target', title: '打开窗口',width: 120, templet:function(d){
						if(d.target == "_self"){
							return "本窗口";
						}else if(d.target == "_blank"){
							return "新窗口";
						}else if(d.target == "_parent"){
							return "父窗口";
						}else if(d.target == "_top"){
							return "顶层窗口";
						}else if(d.target == ""){
							return "";
						}
					}},
                    {field: 'status', title: '显示',width:100, templet:function(d){
						if(d.status == "0"){
							return "否";
						}else if(d.status == "1"){
							return "是";
						}
					}},
                    {field: 'sort', title: '排序',width: 80},
                    {templet: '#currentTableBar',width: 150, align: 'center', title: '操作'}
                ]],
                done: function () {
                    layer.closeAll('loading');
                }
            });
			}else if (obj.event === 'btn-expand') {
                treetable.expandAll('#currentTableId');
			}else if (obj.event === 'btn-fold') {
                treetable.foldAll('#currentTableId');
			}
        });

        table.on('tool(currentTableId)', function (obj) {
            var data = obj.data
				,layEvent = obj.event; // 获得 lay-event 对应的值
			if (obj.event === 'edit') {  // 监听添加操作
                var index = layer.open({
                    title: '编辑栏目',
                    type: 2,
                    shade: 0.2,
                    shadeClose: false,
                    area: ['450px', '580px'],
                    content: '/admin/nav/' + data.id + '/edit',
					btn: ['确定', '取消'],
					yes: function(index, layero){
					//点击确认触发 iframe 内容中的按钮提交
						var submit = layero.find('iframe').contents().find("#editsaveBtn");
						submit.click();
					},

                });
            } else if (layEvent === 'delete') {
				layer.confirm('真的删除行么', function (index) {
					$.post("{{ route('site.nav.destroy') }}",{_method:'delete',_token:'{{csrf_token()}}',ids: [data.id]},function (res) {
                            if (res.code==1){
                                obj.del(); //删除对应行（tr）的DOM结构
								layer.close(index);
								layer.msg(res.msg,{icon:1});
                            }
                            layer.msg(res.msg,{icon:res.code});
                        });
                });

            } else if (layEvent === 'hasedit') {
				layer.confirm('真的恢复行么', function (index) {
					$.post("{{ route('site.nav.restore') }}",{_method:'delete',_token:'{{csrf_token()}}',ids: data.id},function (res) {
                            if (res.code==1){
                                obj.del(); //删除对应行（tr）的DOM结构
								layer.close(index);
								layer.msg(res.msg,{icon:1});
                            }else{
								layer.msg(res.msg,{icon:3});
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