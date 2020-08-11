{{csrf_field()}}
<div class="layui-form-item">
    <label class="layui-form-label">上传图片</label>

    <div class="layui-input-block">
        <button type="button" class="layui-btn" id="upload_img">上传图片</button>
        <img class="layui-upload-img admin-pic-create-img" id="pre_img" @if(isset($image))src="/upload/{{$image->path}}/{{$image->name}}" @endif>
        <input type="hidden" name="name" value="{{$image->name ?? old('name')}}" lay-verify="required">
        <input type="hidden" name="path" value="{{$image->path ?? old('path')}}" >
        <input type="hidden" name="size" value="{{$image->size ?? old('size')}}">
    </div>

</div>

<div class="layui-form-item">
    <label class="layui-form-label">图片标签</label>
    <div class="layui-input-block">
        <input type="text" name="title" value="{{$image->title ?? old('title')}}" lay-verify="required" class="layui-input" placeholder="如：系统管理">
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button class="layui-btn" lay-submit lay-filter="set_website">确认保存
        </button>
    </div>
</div>
<div class="layui-form-item layui-hide">
	<div class="layui-input-block">
		<input class="layui-btn layui-btn-normal" id="addsaveBtn" lay-submit lay-filter="addsaveBtn" value="确认添加">
		<input class="layui-btn layui-btn-normal" id="editsaveBtn" lay-submit lay-filter="editsaveBtn" value="确认编辑">
	</div>
</div>
