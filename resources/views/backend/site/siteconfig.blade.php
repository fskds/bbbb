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
    <link rel="stylesheet" href="/lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <form class="layui-form" lay-filter="site-form" id="submits" onsubmit="return false">
        {{csrf_field()}}
        {{method_field('put')}}
            <div class="layui-tab layui-tab-card" lay-filter="component-tabs-brief">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                    <li>联系方式</li>
                    <li>SEO设置</li>
                </ul>
                
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-card-body" pad15>
                            <div class="layui-form" wid100 lay-filter="">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">网站名称</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="site_name" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">网站域名</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="site_url" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">网站LOGO</label>
                                    <div class="layui-input-block">
                                        <div class="layui-upload">
                                            <div class="layui-upload-list">
                                                <input type="hidden" name="site_logo" value="">
                                                <img class="layui-upload-img" id="up_logo" src="">
                                                <p id="up_logo_text"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">备案信息</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="site_icp" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">统计代码</label>
                                    <div class="layui-input-block">
                                        <textarea name="site_tongji" class="layui-textarea"></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">版权信息</label>
                                    <div class="layui-input-block">
                                        <textarea name="site_copyright" class="layui-textarea"></textarea>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="layui-tab-item">
                        <div class="layui-card-body" pad15>
                            <div class="layui-form" wid100 lay-filter="">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">公司名称</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="co_name" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">公司地址</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="address" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">地图lat</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="map_lat" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">地图lng</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="map_lng" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">联系电话</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="co_phone" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">联系邮箱</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="co_email" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">QQ</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="co_qq" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">WeChat</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="co_wechat" value="" class="layui-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-tab-item">
                        <div class="layui-card-body" pad15>
                            <div class="layui-form" wid100 lay-filter="">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">首页SEO标题</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="seo_title" lay-verify="seo_title" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">首页SEO关键字</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="seo_keywords" lay-verify="seo_keywords" value="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">首页SEO描述</label>
                                    <div class="layui-input-block">
                                        <textarea name="seo_description" class="layui-textarea"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="set_website">确认保存
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        
    </div>
</div>
<script src="/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
@can('website.site')
<script>
    layui.use(['element','form'], function() {
        var $ = layui.jquery
            ,form  = layui.form
            ,element = layui.element;
        $.ajax({
            type: 'get',
            url: '{{ route('website.site.data') }}',
            dataType: 'json',
            success: function (result) {
                form.val('site-form', {
                    'site_name':result.site_name,
                    'site_url':result.site_url,
                    'site_logo':result.v,
                    'site_icp':result.site_icp,
                    'site_tongji':result.site_tongji,
                    'site_copyright':result.site_copyright,
                    'co_name':result.co_name,
                    'address':result.address,
                    'map_lat':result.map_lat,
                    'map_lng':result.map_lng,
                    'co_phone':result.co_phone,
                    'co_email':result.co_email,
                    'co_qq':result.co_qq,
                    'co_wechat':result.co_wechat,
                    'seo_title':result.seo_title,
                    'seo_keywords':result.seo_keywords,
                    'seo_description':result.seo_description
                });
                console.log(result);
            }

        });
        form.on('submit(set_website)', function(data){ 
            $.ajax({
                type:'put', 
                data:data.field, 
                url: '{{ route('website.site.update')}}',
                dataType: 'json',
                success: function (res) {

                    if(res == 1){
                        layer.msg('更新站点属性成功',{icon:1})
                    }else{
                        layer.msg('更新站点属性失败',{icon:2})
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                } 

            });
        });
    })
</script>
@endcan
</body>
</html>