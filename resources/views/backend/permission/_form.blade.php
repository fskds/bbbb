{{csrf_field()}}

<div class="layui-form-item">
    <label class="layui-form-label">父级</label>
    <div class="layui-input-block">
        <select name="pid" lay-search>
            <option value="0">顶级权限</option>
            @forelse($permissions as $perm)
                <option value="{{$perm['id']}}" {{ isset($permission->id) && $perm['id'] == $permission->pid ? 'selected' : '' }} >{{$perm['display_name']}}</option>
                @if(isset($perm['child']))
                    @foreach($perm['child'] as $childs)
                        <option value="{{$childs['id']}}" {{ isset($permission->id) && $childs['id'] == $permission->pid ? 'selected' : '' }} >&nbsp;&nbsp;┗━━{{$childs['display_name']}}</option>
                        @if(isset($childs['child']))
                            @foreach($childs['child'] as $lastChilds)
                                <option value="{{$lastChilds['id']}}" {{ isset($permission->id) && $lastChilds['id'] == $permission->pid ? 'selected' : '' }} >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{$lastChilds['display_name']}}</option>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @empty
            @endforelse
        </select>
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">名称</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{$permission->name ?? old('name')}}" lay-verify="required" class="layui-input" placeholder="如：system.index">
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">显示名称</label>
    <div class="layui-input-block">
        <input type="text" name="display_name" value="{{$permission->display_name ?? old('display_name')}}" lay-verify="required" class="layui-input" placeholder="如：系统管理">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">路由</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="route" value="{{$permission->route ?? old('route')}}" placeholder="如：admin.member" >
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">图标</label>
    <div class="layui-input-block">
        <input type="text" id="iconPicker" name="icon" value="{{$permission->icon ?? old('icon')}}" lay-filter="iconPicker" class="hide">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">排序</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="sort" value="{{$permission->sort ?? 0 }}" >
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">菜单</label>
    <div class="layui-input-block">
		<input type="hidden" name="status" value="{{$permission->status ?? 1}}">
		<input type="checkbox" {{ isset($permission->id) ? '' : 'checked' }}{{ isset($permission->id) && $permission->status == 1 ? 'checked' : '' }} name="switch" lay-skin="switch" lay-filter="switch" lay-text="ON|OFF" >
    </div>
</div>
<div class="layui-form-item layui-hide">
	<div class="layui-input-block">
		<input class="layui-btn layui-btn-normal" id="addsaveBtn" lay-submit lay-filter="addsaveBtn" value="确认添加">
		<input class="layui-btn layui-btn-normal" id="editsaveBtn" lay-submit lay-filter="editsaveBtn" value="确认编辑">
	</div>
</div>
