<?php if (user()->hasFlash('save_app_adslot_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('save_app_adslot_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('', 'post', array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));?>
<fieldset>
    <legend><?php echo $this->adminTitle;?></legend>
    
    <div class="control-group <?php if($model->hasErrors('platform')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'platform', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeDropDownList($model, 'platform', AdminAppAdslot::platformLabesl(), array('class'=>'span4'));?>
            <?php if($model->hasErrors('platform')):?><p class="help-block"><?php echo $model->getError('platform');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('name')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'name', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'name', array('class'=>'span4'));?>
            <?php if($model->hasErrors('name')):?><p class="help-block"><?php echo $model->getError('name');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('slot')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'slot', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'slot', array('class'=>'span4'));?>
            <?php if($model->hasErrors('slot')):?><p class="help-block"><?php echo $model->getError('slot');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('state')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'state', array('class'=>'control-label'));?>
        <div class="controls">
            <label class="checkbox inline">
                <?php echo CHtml::activeCheckBox($model, 'state');?>启用
            </label>
            <?php if($model->hasErrors('state')):?><p class="help-block"><?php echo $model->getError('state');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group bottom10px <?php if ($model->hasErrors('intro')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'intro', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextArea($model, 'intro', array('class'=>'span7', 'rows'=>4));?>
            <?php if ($model->hasErrors('intro')):?><p class="help-block"><?php echo $model->getError('intro');?></p><?php endif;?>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="提交" class="btn btn-primary" />
        <a class="btn" href="<?php echo url('admin/appadslot/list');?>">返回列表</a>
    </div>
</fieldset>
<?php echo CHtml::endForm();?>
