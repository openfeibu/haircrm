
<div class="login layui-anim layui-anim-up">
	<div class="login-con">
        <?php echo Theme::partial('message'); ?>

		<div class="login-con-title">
			<img src="/images/logo.png"/>
			<p><?php echo e(trans('app.site_name')); ?> -- 业务员</p>
		</div>
		<?php echo Form::vertical_open()->id('login')->method('POST')->class('layui-form')->action(guard_url('login')); ?>

		<div class="layui-block">
			<select class="layui-select search_key" lay-filter="role">
				<?php $__currentLoopData = trans('auth.roles'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e(url('/'.$key)); ?>" <?php if(guard_prefix() == $key): ?> selected <?php endif; ?>><?php echo e($role); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
		<input name="email" placeholder="邮箱"  type="text" lay-verify="required" class="layui-input" >
		<input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">

		<input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit" class="login_btn">
		<input id="rememberme" type="hidden" name="rememberme" value="1">
		<?php echo Form::Close(); ?>

	</div>
</div>
