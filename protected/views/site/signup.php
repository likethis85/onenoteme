<div class="fleft cd-container">
    <div class="panel panel25">
        <h2>注册成为会员</h2>
    	<?php echo CHtml::form('', 'POST', array('class'=>'create-form user-form'));?>
    	<ul>
    		<li>
    			<?php echo CHtml::activeLabel($model, 'username');?>
    			<?php echo CHtml::activeTextField($model, 'username', array('class'=>'txt'));?>
    			<span>请输入有效的电子邮箱</span>
    			<?php if ($model->hasErrors('username')):?><p><?php echo $model->getError('username');?></p><?php endif;?>
    		</li>
    		<li>
    			<?php echo CHtml::activeLabel($model, 'password');?>
    			<?php echo CHtml::activePasswordField($model, 'password', array('class'=>'txt'));?>
    			<span>密码最少为5位</span>
    			<?php if ($model->hasErrors('password')):?><p><?php echo $model->getError('password');?></p><?php endif;?>
    		</li>
    		<li>
    			<?php echo CHtml::activeLabel($model, 'screen_name');?>
    			<?php echo CHtml::activeTextField($model, 'screen_name', array('class'=>'txt'));?>
    			<span>给自己起一个名字吧</span>
    			<?php if ($model->hasErrors('screen_name')):?><p><?php echo $model->getError('screen_name');?></p><?php endif;?>
    		</li>
    		<li>
    			<?php echo CHtml::activeLabel($model, 'captcha');?>
    			<?php echo CHtml::activeTextField($model, 'captcha', array('class'=>'txt captcha'));?>
    			<?php $this->widget('CCaptcha', array(
    			    'captchaAction' => 'bigCaptcha',
                	'buttonLabel' => '换一张',
                	'clickableImage' => true,
                    'imageOptions' => array('alt'=>'验证码', 'align'=>'top'),
                ));?>
                <?php if ($model->hasErrors('captcha')):?><p><?php echo $model->getError('captcha');?></p><?php endif;?>
    		</li>
    		<li><?php echo CHtml::submitButton('注册', array('class'=>'beta-btn user-button'));?></li>
    	</ul>
    	<?php echo CHtml::endForm();?>
	</div>
</div>

<div class="fright cd-sidebar">
	<div class="login-signup-tip">已经有账号？<a href="<?php echo aurl('site/login');?>">立即登录</a></div>
</div>