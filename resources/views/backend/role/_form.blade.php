{{csrf_field()}}
<div class="layui-form-item">
    <label class="layui-form-label">名称</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="name" lay-verify="required" value="{{$role->name ?? old('name')}}" placeholder="如:admin">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">显示名称</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="display_name" lay-verify="required" value="{{$role->display_name ?? old('display_name')}}" placeholder="如：管理员" >
    </div>
</div>
<div class="layui-form-item layui-hide">
	<div class="layui-input-block">
		<input class="layui-btn layui-btn-normal" id="addsaveBtn" lay-submit lay-filter="addsaveBtn" value="确认添加">
		<input class="layui-btn layui-btn-normal" id="editsaveBtn" lay-submit lay-filter="editsaveBtn" value="确认编辑">
	</div>
</div>
