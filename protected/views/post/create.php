<h2>有好段子？一块分享下吧。</h2>
<div class="fl cd-container">
    <?php echo CHtml::form('', 'POST', array('class'=>'create-form post-form'));?>
    <ul>
    	<li><?php echo CHtml::activeTextArea($model, 'content', array('tabindex'=>1));?></li>
        <li>
            <label>标　签：</label>
            <?php echo CHtml::activeTextField($model, 'tags', array('class'=>'txt', 'tabindex'=>5));?>
            <span class="cgray f12px">（每个糗事最多5个标签，用空格分隔）</span>
        </li>
        <li>
            <label>验证码：</label>
            <?php echo CHtml::activeTextField($model, 'captcha', array('class'=>'txt captcha', 'tabindex'=>10));?>
            <?php $this->widget('CCaptcha', array(
            	'buttonLabel' => '看不清，换一张',
            	'clickableImage' => true,
                'imageOptions' => array('alt'=>'验证码', 'align'=>'top'),
            ));?>
        </li>
        <li>
            <?php echo CHtml::submitButton('马上发布', array('class'=>'button', 'tabindex'=>15));?>&nbsp;&nbsp;
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
		<h2 class="content-title">发贴说明</h2>
		<ul class="site-notice">
			<li>禁止发广告贴。文章不得出现广告倾向；文章不得包含网站链接。</li>
			<li>不推荐低俗内容。过于恶心或者私密可能会引起人反感的内容请慎重考虑后再决定发表与否，此类内容的通过率可能会非常低。此类内容包括但不仅限于：过于低俗的粗口、和排泄物有关、和生殖器官有关、和性有关的内容等。</li>
			<li>不推荐敏感内容：与政治、时政、政府、国家制度、国家领导人相关的内容请慎重考虑后再决定发表与否，避免出现对于敏感内容的描述，避免出现对于此类内容的不恰当评论和隐射。</li>
		</ul>
	</div>
</div>