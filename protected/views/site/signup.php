<div class="fl cd-container">
	<?php echo CHtml::form('', 'POST', array('class'=>'create-form user-form'));?>
	<ul>
		<li>
			<?php echo CHtml::activeLabel($model, 'email');?>
			<?php echo CHtml::activeTextField($model, 'email', array('class'=>'txt'));?>
			<span>请输入有效的电子邮箱</span>
		</li>
		<li>
			<?php echo CHtml::activeLabel($model, 'password');?>
			<?php echo CHtml::activePasswordField($model, 'password', array('class'=>'txt'));?>
			<span>密码最少为5位</span>
		</li>
		<li>
			<?php echo CHtml::activeLabel($model, 'name');?>
			<?php echo CHtml::activeTextField($model, 'name', array('class'=>'txt'));?>
			<span>给自己起一个名字吧</span>
		</li>
		<li>
			<?php echo CHtml::activeLabel($model, 'captcha');?>
			<?php echo CHtml::activeTextField($model, 'captcha', array('class'=>'txt captcha'));?>
			<?php $this->widget('CCaptcha', array(
			    'captchaAction' => 'bigCaptcha',
            	'buttonLabel' => '看不清，换一张',
            	'clickableImage' => true,
                'imageOptions' => array('alt'=>'验证码', 'align'=>'top'),
            ));?>
		</li>
		<li><?php echo CHtml::submitButton('注册', array('class'=>'button'));?></li>
	</ul>
	<?php echo CHtml::endForm();?>
	<?php echo CHtml::errorSummary($model);?>
</div>

<div class="fr cd-sidebar">
	<div class="login-signup-tip">已经有账号？<a href="<?php echo aurl('site/login');?>">立即登录</a></div>
</div>