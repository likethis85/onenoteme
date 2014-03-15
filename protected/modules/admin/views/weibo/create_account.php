<?php if (user()->hasFlash('user_create_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('user_create_weib_account_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('', 'post', array('class'=>'form-horizontal'));?>
<fieldset>
    <legend><?php echo $this->adminTitle;?></legend>
    <div class="control-group <?php if($model->hasErrors('display_name')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'display_name', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'display_name', array('class'=>'span3'));?>
            <?php if($model->hasErrors('display_name')):?><p class="help-block"><?php echo $model->getError('display_name');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('user_id')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'user_id', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'user_id', array('class'=>'span3'));?>
            <?php if($model->hasErrors('user_id')):?><p class="help-block"><?php echo $model->getError('user_id');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('user_name')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'user_name', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'user_name', array('class'=>'span3'));?>
            <?php if($model->hasErrors('user_name')):?><p class="help-block"><?php echo $model->getError('user_name');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('desc')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'desc', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextArea($model, 'desc', array('class'=>'span6', 'rows'=>3));?>
            <?php if($model->hasErrors('desc')):?><p class="help-block"><?php echo $model->getError('desc');?></p><?php endif;?>
        </div>
    </div>
    <div class="form-actions">
        <a href="<?php echo aurl('admin/weibo/accounts');?>" class="btn">返回列表</a>
        <input type="submit" name="submit_return" value="提交返回列表" class="btn" />
        <input type="submit" name="submit_continue" value="提交继续添加" class="btn btn-primary" />
    </div>
</fieldset>
<?php echo CHtml::endForm();?>