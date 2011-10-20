<?php echo CHtml::form(null, 'POST');?>
<ul class="create-form">
	<li><?php echo CHtml::activeTextArea($model, 'content');?></li>
    <li>
        <label>标签：</label>
        <?php echo CHtml::textField('tags', '', array('class'=>'txt'));?>
        <span class="cgray f12px">（每个糗事最多5个标签，用空格分隔）</span>
    </li>
    <li>
        <?php echo CHtml::submitButton('马上发布', array('class'=>'button'));?>
        <?php if (user()->hasFlash('createPostResult')) echo user()->getFlash('createPostResult');?>
    </li>
</ul>
<div class="space20px"></div>

<?php if ($model->hasErrors()):?>
<div class="fail-note"><?php echo CHtml::errorSummary($model);?></div>
<div class="space20px"></div>
<?php endif;?>

<ul class="create-form">
    <li>发贴前请阅读 审核标准 和 发贴规范</li>
    <li>内容版权为糗事百科所有</li>
</ul>
<?php echo CHtml::endForm();?>