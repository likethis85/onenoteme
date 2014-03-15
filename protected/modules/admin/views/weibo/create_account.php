<?php if (user()->hasFlash('user_create_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('user_create_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('', 'post', array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));?>
<fieldset>
    <legend><?php echo $this->adminTitle;?></legend>
    <div class="control-group <?php if($model->hasErrors('username')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'username', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'username');?>
            <?php if($model->hasErrors('username')):?><p class="help-block"><?php echo $model->getError('username');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('screen_name')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'screen_name', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'screen_name');?>
            <?php if($model->hasErrors('screen_name')):?><p class="help-block"><?php echo $model->getError('screen_name');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('password')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'password', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activePasswordField($model, 'password');?>
            <?php if($model->hasErrors('password')):?><p class="help-block"><?php echo $model->getError('password');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('state')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'state', array('class'=>'control-label'));?>
        <div class="controls">
            <label class="checkbox">
                <?php echo CHtml::activeCheckBox($model, 'state');?>启用
            </label>
            <?php if($model->hasErrors('state')):?><p class="help-block"><?php echo $model->getError('state');?></p><?php endif;?>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="提交" class="btn btn-primary" />
    </div>
</fieldset>
<?php echo CHtml::endForm();?>