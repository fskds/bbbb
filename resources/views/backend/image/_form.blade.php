{{csrf_field()}}
<div class="layui-form-item">
    <label class="layui-form-label">上传图片</label>
    <div class="layui-input-inline">
        <div class="layui-upload">
            <button type="button" class="layui-btn" id="upload_img">上传图片</button>
        </div>
    </div>
</div>
<input type="hidden" name="img" value="">
<div class="layui-form-item">
    <label class="layui-form-label">图片展示</label>
    <div class="layui-input-inline">
        <div class="layui-upload-list">
            <img class="layui-upload-img admin-pic-create-img" id="pre_img">
            <p id="demoText"></p>
        </div>
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">图片标签</label>
    <div class="layui-input-block">
        <input type="text" name="title" value="{{$image->title ?? old('title')}}" lay-verify="required" class="layui-input" placeholder="如：系统管理">
    </div>
</div>

<div class="layui-form-item layui-hide">
	<div class="layui-input-block">
		<input class="layui-btn layui-btn-normal" id="addsaveBtn" lay-submit lay-filter="addsaveBtn" value="确认添加">
		<input class="layui-btn layui-btn-normal" id="editsaveBtn" lay-submit lay-filter="editsaveBtn" value="确认编辑">
	</div>
</div>
