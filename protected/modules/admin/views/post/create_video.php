<?php if (user()->hasFlash('save_post_video_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('save_post_video_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('',  'post', array('class'=>'form-horizontal post-form'));?>
<input type="hidden" name="returnurl" value="<?php echo request()->getUrlReferrer();?>" />
<fieldset>
    <legend><?php echo $this->adminTitle;?></legend>
    <div class="control-group bottom10px">
        <label class="control-label">抓取地址</label>
        <div class="controls">
            <div class="input-append">
                <?php echo CHtml::textField('original_url', array('class'=>'span5', 'id'=>'original-url'));?>
                <input type="button" id="parse-url" value="抓取" />
            </div>
        </div>
    </div>
    <div class="control-group bottom10px">
        <?php echo CHtml::activeLabel($post, 'title', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($post, 'title', array('class'=>'span6', 'disabled'=>'disabled'));?>
        </div>
    </div>
    <div class="control-group bottom10px <?php if ($model->hasErrors('flash_url')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'flash_url', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'flash_url', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('flash_url')):?><p class="help-block"><?php echo $model->getError('flash_url');?></p><?php endif;?>
        </div>
    </div>
    
    <div class="control-group bottom10px <?php if ($model->hasErrors('source_url')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'source_url', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'source_url', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('source_url')):?><p class="help-block"><?php echo $model->getError('source_url');?></p><?php endif;?>
        </div>
    </div>
    
    <div class="control-group bottom10px <?php if ($model->hasErrors('iframe_url')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'iframe_url', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'iframe_url', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('iframe_url')):?><p class="help-block"><?php echo $model->getError('iframe_url');?></p><?php endif;?>
        </div>
    </div>
    
    <div class="control-group bottom10px <?php if ($model->hasErrors('html5_url')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'html5_url', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'html5_url', array('class'=>'span6'));?>
            <?php if ($model->hasErrors('html5_url')):?><p class="help-block"><?php echo $model->getError('html5_url');?></p><?php endif;?>
        </div>
    </div>
    
    <div class="control-group bottom10px <?php if ($model->hasErrors('desc')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'desc', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextArea($model, 'desc', array('class'=>'span6', 'rows'=>3));?>
            <?php if ($model->hasErrors('desc')):?><p class="help-block"><?php echo $model->getError('desc');?></p><?php endif;?>
        </div>
    </div>
    
    <div class="form-actions">
        <?php echo CHtml::submitButton('保存视频', array('class'=>'btn btn-primary'));?>
    </div>
</fieldset>
<?php echo CHtml::endForm();?>

<script type="text/javascript">
$(function(){
	$(':text:first').focus();
	$('#parse-url').on('click', function(){
	    var url = $.trim($('#original-url').val());
	    if (url.length == 0) return;

	    var xhr = $.ajax({
			url: '<?php echo aurl('admin/post/parseVideoUrl');?>',
			type: 'POST',
			dataType: 'jsonp',
			data: {url: url}
		});
		xhr.done(function(data){
			console.log(data);
		});
		xhr.fail(function(error){
			console.log(error);
		});
	});
});
</script>

