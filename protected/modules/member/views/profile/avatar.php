<?php if (user()->hasFlash('user_save_result')):?>
<div class="alert alert-success fade in" data-dismiss="alert">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('user_save_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('', 'post', array('class'=>'form-horizontal member-form', 'enctype'=>'multipart/form-data'));?>
<div class="control-group">
    <label class="control-label">原头像</label>
    <div class="controls ">
        <div class="user-avatar aleft">
            <?php echo $model->largeAvatar;?>
            <?php echo $model->smallAvatar;?>
        </div>
    </div>
</div>
<div class="control-group <?php if($model->hasErrors('original_avatar')) echo 'error';?>">
    <label class="control-label">上传新头像</label>
    <div class="controls">
        <?php echo CHtml::activeFileField($model, 'original_avatar');?>
        <?php if($model->hasErrors('original_avatar')):?><p class="help-block"><?php echo $model->getError('original_avatar');?></p><?php endif;?>
    </div>
</div>
<div class="form-actions">
    <input type="submit" value="提交" class="btn btn-primary" />
</div>
<?php echo CHtml::endForm();?>