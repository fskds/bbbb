{{csrf_field()}}

<div class="layui-form-item">
    <label class="layui-form-label">父级</label>
    <div class="layui-input-block">
        <select name="pid" lay-search>
            <option value="0">顶级权限</option>
            @forelse($navs as $item1)
                <option value="{{$item1['id']}}" {{ isset($nav->id) && $item1['id'] == $nav->pid ? 'selected' : '' }} >{{$item1['name']}}</option>
                @if(isset($item1['child']))
                    @foreach($item1['child'] as $childs)
                        <option value="{{$childs['id']}}" {{ isset($nav->id) && $childs['id'] == $nav->pid ? 'selected' : '' }} >&nbsp;&nbsp;┗━━{{$childs['name']}}</option>
                        @if(isset($childs['child']))
                            @foreach($childs['child'] as $lastChilds)
                                <option value="{{$lastChilds['id']}}" {{ isset($nav->id) && $lastChilds['id'] == $nav->pid ? 'selected' : '' }} >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┗━━{{$lastChilds['name']}}</option>
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
        <input type="text" name="name" value="{{$nav->name ?? old('name')}}" lay-verify="required" class="layui-input" placeholder="如：system.index">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">地址</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="href" value="{{$nav->href ?? old('href')}}" placeholder="如：http://" >
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">打开窗口</label>
    <div class="layui-input-block">
        <select name="target" lay-filter="aihao">
            <option value=""></option>
            <option value="_self" selected="">本窗口</option>
            <option value="_parent">父窗口</option>
            <option value="_top">顶层窗口</option>
            <option value="_blank">新窗口</option>
        </select>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">排序</label>
    <div class="layui-input-block">
        <input class="layui-input" type="text" name="sort" value="{{$nav->sort ?? 0 }}" >
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
