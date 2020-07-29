{{csrf_field()}}

<div class="layui-form-item">
    <label class="layui-form-label">页面标题</label>
    <div class="layui-input-block">
        <input type="text" name="title" value="{{$column->title ?? old('title')}}" lay-verify="required" class="layui-input" placeholder="如：公司服务">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">页面关键词</label>
    <div class="layui-input-block">
        <input type="text" name="keywords" value="{{$column->keywords ?? old('keywords')}}" lay-verify="required" class="layui-input" placeholder="如：公司服务">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">页面描述</label>
    <div class="layui-input-block">
        <input type="text" name="description" value="{{$column->description ?? old('description')}}" lay-verify="required" class="layui-input" placeholder="如：公司服务">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">页面缩略图</label>
    <div class="layui-input-block">
        <input type="text" name="title" value="{{$column->title ?? old('title')}}" lay-verify="required" class="layui-input" placeholder="如：公司服务">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">显示</label>
    <div class="layui-input-block">
		<input type="hidden" name="status" value="{{$nav->status ?? 1}}">
		<input type="checkbox" {{ isset($nav->id) ? '' : 'checked' }}{{ isset($nav->id) && $nav->status == 1 ? 'checked' : '' }} name="switch" lay-skin="switch" lay-filter="switch" lay-text="ON|OFF" >
    </div>
</div>
<div class="layui-form-item layui-hide">
	<div class="layui-input-block">
		<input class="layui-btn layui-btn-normal" id="addsaveBtn" lay-submit lay-filter="addsaveBtn" value="确认添加">
		<input class="layui-btn layui-btn-normal" id="editsaveBtn" lay-submit lay-filter="editsaveBtn" value="确认编辑">
	</div>
</div>
<div class="layui-form-item">
                                                <div class="layui-input-block">
                                                    <button class="layui-btn" lay-submit lay-filter="set_website">确认保存
                                                    </button>
                                                </div>
                                            </div>