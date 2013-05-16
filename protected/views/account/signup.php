<div class="panel panel20">
    <div class="cd-content fleft login-signup">
        <h2>欢迎加入<?php echo app()->name;?></h2>
        <?php echo CHtml::form('', 'post', array('class'=>'form-horizontal login-form'));?>
        <?php echo CHtml::activeHiddenField($form, 'returnUrl');?>
        <div class="control-group <?php echo $form->hasErrors('username') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($form, 'username');?></label>
            <div class="controls">
                <?php echo CHtml::activeTextField($form, 'username', array('class'=>'cd-text', 'tabindex'=>1));?>
                <?php if ($form->hasErrors('username')):?><span class="help-inline"><?php echo $form->getError('username');?></span><?php endif;?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="control-group <?php echo $form->hasErrors('screen_name') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($form, 'screen_name');?></label>
            <div class="controls">
                <?php echo CHtml::activeTextField($form, 'screen_name', array('class'=>'cd-text', 'tabindex'=>2));?>
                <?php if ($form->hasErrors('screen_name')):?><span class="help-inline"><?php echo $form->getError('screen_name');?></span><?php endif;?>
                <p class="suggestion">第一印象很重要，起个响亮的名号吧</p>
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
        <div class="control-group <?php echo $form->hasErrors('captcha') ? 'error' : '';?>">
            <label class="control-label"><?php echo CHtml::activeLabel($form, 'captcha');?></label>
            <div class="controls">
                <?php echo CHtml::activeTextField($form, 'captcha', array('class'=>'cd-captcha cd-text', 'tabindex'=>3));?>
                <?php $this->widget('CCaptcha');?>
                <?php if ($form->hasErrors('captcha')):?><span class="help-inline"><?php echo $form->getError('captcha');?></span><?php endif;?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="control-group <?php echo $form->hasErrors('agreement') ? 'error' : '';?>">
            <label class="control-label">&nbsp;</label>
            <div class="controls cd-agreement">
                <label class="checkbox">
                    <?php echo CHtml::activeCheckBox($form, 'agreement', array('id'=>'agreement', 'tabindex'=>5));?>
                    我已经认真阅读并同意《使用协议》
                    <?php if ($form->hasErrors('agreement')):?><span class="help-inline"><?php echo $form->getError('agreement');?></span><?php endif;?>
                </label>
            </div>
            <div class="clear"></div>
        </div>
        <div class="form-actions">
            <?php echo CHtml::submitButton('注册', array('class'=>'btn btn-primary', 'tabindex'=>6));?>
        </div>
        <?php echo chtml::endForm();?>
    </div>
    <div class="cd-sidebar fright">
        <p class="quick-login-signup">&gt;&nbsp;已经拥有<?php echo app()->name;?>账号？<a href="<?php echo url('site/login');?>">直接登录</a></p>
    </div>
    <div class="clear"></div>
</div>