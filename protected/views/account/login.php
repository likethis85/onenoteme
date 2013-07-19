<div class="panel panel20">
    <div class="cd-content fleft login-signup">
        <?php if ($form->hasErrors('state')):?>
        <div class="alert alert-error"><?php echo $form->getError('state');?></div>
        <?php endif;?>
        <?php echo CHtml::errorSummary($form);?>
        <h2>欢迎加入<?php echo app()->name;?></h2>
        <?php echo CHtml::form('', 'post', array('class'=>'form-horizontal cd-form login-form'));?>
        <?php echo CHtml::activeHiddenField($form, 'returnUrl');?>
        <div class="control-group <?php echo $form->hasErrors('username') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($form, 'username');?></label>
            <div class="controls">
                <?php echo CHtml::activeTextField($form, 'username', array('class'=>'cd-text', 'tabindex'=>1));?>
                <?php if ($form->hasErrors('username')):?><span class="help-inline"><?php echo $form->getError('username');?></span><?php endif;?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="control-group <?php echo $form->hasErrors('password') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($form, 'password');?></label>
            <div class="controls">
                <?php echo CHtml::activePasswordField($form, 'password', array('class'=>'cd-text', 'tabindex'=>2));?>
                <?php if ($form->hasErrors('password')):?><span class="help-inline"><?php echo $form->getError('password');?></span><?php endif;?>
            </div>
            <div class="clear"></div>
        </div>
        <?php if ($form->getEnableCaptcha()):?>
        <div class="control-group <?php echo $form->hasErrors('captcha') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($form, 'captcha');?></label>
            <div class="controls">
                <?php echo CHtml::activeTextField($form, 'captcha', array('class'=>'cd-captcha cd-text', 'tabindex'=>3));?>
                <?php $this->widget('CCaptcha');?>
                <?php if ($form->hasErrors('captcha')):?><span class="help-inline"><?php echo $form->getError('captcha');?></span><?php endif;?>
            </div>
            <div class="clear"></div>
        </div>
        <?php endif;?>
        <div class="control-group">
            <label class="control-label">&nbsp;</label>
            <div class="controls rememberme">
                <label class="checkbox">
                    <?php echo CHtml::activeCheckBox($form, 'rememberMe', array('id'=>'rememberme', 'tabindex'=>4));?>
                    下次自动登录&nbsp;|&nbsp;
                    <a href="<?php echo url('site/resetpwd');?>">忘记密码了</a>
                    <?php if ($form->hasErrors('rememberMe')):?><span class="help-inline"><?php echo $form->getError('rememberMe');?></span><?php endif;?>
                </label>
            </div>
            <div class="clear"></div>
        </div>
        <div class="form-actions">
            <?php echo CHtml::submitButton('登录', array('class'=>'btn btn-primary', 'tabindex'=>6));?>
        </div>
        <?php echo chtml::endForm();?>
    </div>
    <div class="cd-sidebar fright">
        <p class="quick-login-signup">&gt;&nbsp;还没有<?php echo app()->name;?>账号？<a href="<?php echo CDBaseUrl::singupUrl();?>">立即注册</a></p>
    </div>
    <div class="clear"></div>
</div>