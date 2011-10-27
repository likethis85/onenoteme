<div class="fl cd-container">
    <h2>有好段子？一块分享下吧。</h2>
    <?php echo CHtml::form('', 'POST');?>
    <ul class="create-form">
    	<li><?php echo CHtml::activeTextArea($model, 'content');?></li>
        <li>
            <label>标　签：</label>
            <?php echo CHtml::activeTextField($model, 'tags', array('class'=>'txt'));?>
            <span class="cgray f12px">（每个糗事最多5个标签，用空格分隔）</span>
        </li>
        <li>
            <label>验证码：</label>
            <?php echo CHtml::activeTextField($model, 'captcha', array('class'=>'txt captcha'));?>
            <?php $this->widget('CCaptcha', array(
            	'buttonLabel' => '看不清，换一张',
            	'clickableImage' => true,
                'imageOptions' => array('alt'=>'验证码', 'align'=>'top'),
            ));?>
        </li>
        <li>
            <?php echo CHtml::submitButton('马上发布', array('class'=>'button'));?>&nbsp;&nbsp;
            <?php if (user()->hasFlash('createPostResult')) echo user()->getFlash('createPostResult');?>
        </li>
    </ul>
    <div class="space20px"></div>
    
    <?php if ($model->hasErrors()):?>
    <div class="fail-note"><?php echo CHtml::errorSummary($model);?></div>
    <div class="space20px"></div>
    <?php endif;?>
    
    <ul class="create-form">
        <li>发贴前请阅读<?php echo CHtml::link('审核标准', aurl('site/page', array('view'=>'biaozhun')), array('target'=>'_blank'));?>和<?php echo CHtml::link('发贴规范', aurl('site/page', array('view'=>'guifan')), array('target'=>'_blank'));?></li>
        <li>内容版权为<?php echo CHtml::link(app()->name, app()->homeUrl, array('target'=>'_blank'));?>所有</li>
    </ul>
    <?php echo CHtml::endForm();?>
</div>

<div class="fr cd-sidebar">
	<div class="content-block">
		段子发表说明
	</div>
</div>