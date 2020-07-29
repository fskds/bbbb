{{csrf_field()}}

<div class="layui-form-item">
    <label for="" class="layui-form-label">父级</label>
    <div class="layui-input-block">
        <select name="parent_id" lay-search>
            <option value="0">顶级权限</option>
            @forelse($menus as $nav)
                <option value="{{$nav['id']}}" {{ isset($menu->id) && $nav['id'] == $menu->parent_id ? 'selected' : '' }} >{{$nav['display_name']}}</option>
                @if(isset($nav['_child']))
                    @foreach($nav['_child'] as $childs)
                        <option value="{{$childs['id']}}" {{ isset($menu->id) && $childs['id'] == $menu->parent_id ? 'selected' : '' }} >&nbsp;&nbsp;┗━━{{$childs['display_name']}}</option>
                        @if(isset($childs['_child']))
                            @foreach($childs['_child'] as $lastChilds)
                                <option value="{{$lastChilds['id']}}" {{ isset($menu->id) && $lastChilds['id'] == $menu->parent_id ? 'selected' : '' }} >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{$lastChilds['display_name']}}</option>
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
    <label for="" class="layui-form-label">名称</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{$menu->name ?? old('name')}}" lay-verify="required" class="layui-input" placeholder="如：首页">
    </div>
</div>

<div class="layui-form-item">
    <label for="" class="layui-form-label">显示名称</label>
    <div class="layui-input-block">
        <input type="text" name="display_name" value="{{$menu->display_name ?? old('display_name')}}" class="layui-input" placeholder="如：系统管理">
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">路由</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="route" value="{{$menu->route ?? old('route')}}" placeholder="如：admin.member" >
    </div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">排序</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="sort" value="{{$menu->sort ?? old('sort')}}" placeholder="如：1" >
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">状态</label>
    <div class="layui-input-block">
		<input type="hidden" name="state" value="{{$menu->state ?? 1}}">
		<input type="checkbox" {{ isset($menu->id) ? '' : 'checked' }}{{ isset($menu->id) && $menu->state == 1 ? 'checked' : '' }} name="switch" lay-skin="switch" lay-filter="switch" lay-text="ON|OFF" >
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button type="submit" class="layui-btn" lay-submit="">确 认</button>
        <a href="{{route('site.menu')}}" class="layui-btn">返 回</a>
    </div>
</div>
