{{csrf_field()}}
<div class="layui-form-item">
    <label class="layui-form-label required">用户名</label>
    <div class="layui-input-block">
        <input type="text" name="username" value="{{ $user->username ?? old('username') }}" lay-verify="required|username" placeholder="请输入用户名" lay-reqtext="用户名不能为空" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">昵称</label>
    <div class="layui-input-block">
        <input type="text" name="name" value="{{ $user->name ?? old('name') }}" lay-verify="required|name" placeholder="请输入昵称" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label required">邮箱</label>
    <div class="layui-input-block">
        <input type="email" name="email" value="{{$user->email??old('email')}}" lay-verify="required|email" placeholder="请输入Email" class="layui-input" >
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">手机号</label>
    <div class="layui-input-block">
        <input type="text" name="phone" value="{{$user->phone??old('phone')}}" required="phone" lay-verify="required|phone" placeholder="请输入手机号" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">密码</label>
    <div class="layui-input-block">
        <input type="password" name="password" placeholder="请输入密码" class="layui-input">
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">确认密码</label>
    <div class="layui-input-block">
        <input type="password" name="password_confirmation" placeholder="请输入密码" class="layui-input">
    </div>
</div>
<div class="layui-form-item layui-hide">
	<div class="layui-input-block">
	
		<input class="layui-btn layui-btn-normal" id="addsaveBtn" lay-submit lay-filter="addsaveBtn" value="确认添加">
		<input class="layui-btn layui-btn-normal" id="editsaveBtn" lay-submit lay-filter="editsaveBtn" value="确认编辑">
	</div>
</div>