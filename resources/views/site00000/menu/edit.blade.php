@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>更新菜单</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form" action="{{route('site.menu.update',$menu->id)}}" method="post">
                {{method_field('put')}}
                <input type="hidden" name="id" value="{{ $menu->id }}">
                @include('site.menu._from')
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
// 状态监听开关
        layui.use(['form'], function () {
            var form = layui.form;
            //监听指定开关
            form.on('switch(switch)', function (data) {
                if (this.checked) {
                    $("input[name='state']").val('1');
                } else {
                    $("input[name='state']").val('0');
                }

            });
        });
</script>
@endsection
