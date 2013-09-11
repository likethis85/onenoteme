<?php if (user()->hasFlash('save_post_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('save_post_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('',  'post', array('class'=>'form-horizontal post-form'));?>
<input type="hidden" name="returnurl" value="<?php echo request()->getUrlReferrer();?>" />
<fieldset>
    <legend><?php echo $this->adminTitle;?></legend>
    <div class="control-group bottom10px">
        <?php echo CHtml::activeLabel($model, 'media_type', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeDropDownList($model, 'media_type', CDBase::mediaTypeLabels());?>
            <?php if ($model->hasErrors('media_type')):?><p class="help-block"><?php echo $model->getError('media_type');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group bottom10px">
        <?php echo CHtml::activeLabel($model, 'channel_id', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeDropDownList($model, 'channel_id', CDBase::channelLabels());?>
            <?php if ($model->hasErrors('channel_id')):?><p class="help-block"><?php echo $model->getError('channel_id');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group bottom10px <?php if ($model->hasErrors('title')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'title', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'title', array('class'=>'span6', 'id'=>'post-title'));?>
            <?php if ($model->hasErrors('title')):?><p class="help-block"><?php echo $model->getError('title');?></p><?php endif;?>
        </div>
    </div>
    
    
    <!-- 缩略图 start -->
    <?php if (!$model->getIsTextType()):?>
    <div class="control-group bottom10px <?php if ($model->hasErrors('original_pic')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'original_pic', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'original_pic', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('original_pic')):?><p class="help-block"><?php echo $model->getError('original_pic');?></p><?php endif;?>
        </div>
    </div>
    <?php endif;?>
    <!-- 缩略图 end -->
    
    <div class="control-group bottom10px <?php if ($model->hasErrors('content')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'content', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextArea($model, 'content', array('id'=>'content'));?>
            <?php if ($model->hasErrors('content')):?><p class="help-block"><?php echo $model->getError('content');?></p><?php endif;?>
        </div>
    </div>
    
    <div class="form-actions">
        <?php echo CHtml::submitButton('保存段子', array('class'=>'btn btn-primary'));?>
    </div>
    
    <div class="control-group bottom10px">
        <?php echo CHtml::activeLabel($model, 'state', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeDropDownList($model, 'state', AdminPost::stateLabels());?>
            <?php if ($model->hasErrors('state')):?><p class="help-block"><?php echo $model->getError('state');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group bottom10px <?php if ($model->hasErrors('tags')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'tags', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'tags', array('class'=>'span5'));?>
            <span class="help-inline">标签之间用逗号(,)分隔</span>
            <?php if ($model->hasErrors('tags')):?><p class="help-block"><?php echo $model->getError('tags');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group bottom10px">
        <label class="control-label">选项</label>
        <div class="controls">
            <label class="checkbox inline">
                <?php echo CHtml::activeCheckBox($model, 'homeshow');?><?php echo CHtml::activeLabel($model, 'homeshow');?>
            </label>
            <label class="checkbox inline">
                <?php echo CHtml::activeCheckBox($model, 'hottest');?><?php echo CHtml::activeLabel($model, 'hottest');?>
            </label>
            <label class="checkbox inline">
                <?php echo CHtml::activeCheckBox($model, 'recommend');?><?php echo CHtml::activeLabel($model, 'recommend');?>
            </label>
            <label class="checkbox inline">
                <?php echo CHtml::activeCheckBox($model, 'istop');?><?php echo CHtml::activeLabel($model, 'istop');?>
            </label>
            <label class="checkbox inline">
                <?php echo CHtml::activeCheckBox($model, 'disable_comment');?><?php echo CHtml::activeLabel($model, 'disable_comment');?>
            </label>
        </div>
    </div>
    <div class="control-group bottom10px <?php if ($model->hasErrors('view_nums')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'view_nums', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'view_nums', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('view_nums')):?><p class="help-block"><?php echo $model->getError('view_nums');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group bottom10px <?php if ($model->hasErrors('up_score')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'up_score', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'up_score', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('up_score')):?><p class="help-block"><?php echo $model->getError('up_score');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group bottom10px <?php if ($model->hasErrors('down_score')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'down_score', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'down_score', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('down_score')):?><p class="help-block"><?php echo $model->getError('down_score');?></p><?php endif;?>
        </div>
    </div>
    <div class="form-actions">
        <?php echo CHtml::submitButton('保存段子', array('class'=>'btn btn-primary'));?>
    </div>
</fieldset>
<?php echo CHtml::endForm();?>

<?php cs()->registerScriptFile(sbu('libs/kindeditor/kindeditor-min.js'), CClientScript::POS_END);?>
<?php cs()->registerScriptFile(sbu('libs/kindeditor/config.js'), CClientScript::POS_END);?>

<script type="text/javascript">
$(function(){
	$(':text:first').focus();
	
    KindEditor.ready(function(K) {
    	var cssPath = ['<?php echo sbu('libs/bootstrap/css/bootstrap.min.css');?>', '<?php echo sbu('styles/cd-basic.css');?>', '<?php echo sbu('styles/cd-main.css');?>'];
    	var imageUploadUrl = '<?php echo aurl('upload/image');?>';
        KEConfig.adminfull.cssPath = cssPath;
    	KEConfig.adminfull.uploadJson = imageUploadUrl;
    	
    	var contentEditor = K.create('#content', KEConfig.adminfull);
        $(document).on('click', '.post-pictures li', function(event){
            var html = $(this).html();
            contentEditor.insertHtml(html);
            
        });
    });
});
</script>

