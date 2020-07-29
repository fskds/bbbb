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
		<input type="hidden" name="id" value="{{$image->id}}">
        @include('backend.image._form')
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

        var uploadInst = upload.render({
            elem: '#upload_img'
       //     ,headers: { 'X-CSRF-TOKEN': tag_token }
            ,url: '{{route('site.image.store')}}'
            ,auto:false //选择文件后不自动上传
            ,bindAction: '#addsaveBtn'
            ,data: { _token: tag_token }
            ,done: function(res){
                alert(arrayObj.join(res))

    //            var domian = 'http://'+window.location.host;
                //显示图片
    //            $('#pre_img').attr('src', domian + res.msg);
                //给img隐藏域赋值，用于提交保存
   //             $('input[name="img"]').val(res.msg);
            }
            ,error: function(){
                //请求异常回调
                console.log('error')
                //layer.msg('上传图片错误');
            }

        });
        //监听提交
        form.on('submit(editsaveBtn)', function (data) {
				var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引  
				$.ajax({
					type:'put', 
					data:data.field, 
					url: '{{route('admin.image.update',$image->id)}}',
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