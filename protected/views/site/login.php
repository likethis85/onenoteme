<div class="fleft cd-container">
    <div class="panel panel25">
    	<?php echo CHtml::form('', 'POST', array('class'=>'create-form user-form'));?>
    	<ul>
    		<li>
    			<?php echo CHtml::activeLabel($model, 'username');?>
    			<?php echo CHtml::activeTextField($model, 'username', array('class'=>'txt'));?>
    		</li>
    		<li>
    			<?php echo CHtml::activeLabel($model, 'password');?>
    			<?php echo CHtml::activePasswordField($model, 'password', array('class'=>'txt'));?>
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
    		<li>
    			<label>&nbsp;</label>
    			<?php echo CHtml::activeCheckBox($model, 'rememberMe');?>
    			<span>下次自动登录</span>
    		    <div><?php echo CHtml::submitButton('登录', array('class'=>'button'));?></div>
    		</li>
    	</ul>
    	<?php echo CHtml::endForm();?>
    	<?php echo CHtml::errorSummary($model);?>
    </div>
</div>

<div class="fright cd-sidebar">
	<div class="login-signup-tip">&gt;还没有账号？<a href="<?php echo aurl('site/signup');?>">立即注册</a></div>
</div>