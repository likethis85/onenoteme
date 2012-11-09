<?php if (user()->hasFlash('user_save_result')):?>
<div class="alert alert-success fade in" data-dismiss="alert">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('user_save_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('', 'post', array('class'=>'form-horizontal member-form'));?>
<div class="control-group">
    <label class="control-label">账号</label>
    <div class="controls">
        <?php echo CHtml::textField('username', $this->username, array('readonly'=>'readonly', 'class'=>'uneditable-input'));?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">昵称</label>
    <div class="controls">
        <?php echo CHtml::textField('screen_name', $this->nickname, array('readonly'=>'readonly', 'class'=>'uneditable-input'));?>
    </div>
</div>
<div class="control-group <?php if($model->hasErrors('gender')) echo 'error';?>">
    <?php echo CHtml::activeLabel($model, 'gender', array('class'=>'control-label'));?>
    <div class="controls">
        <?php echo CHtml::activeDropDownList($model, 'gender', MemberUserProfile::genderLabel(), array('separator'=>'', 'template'=>'{label}{input}'));?>
        <?php if($model->hasErrors('gender')):?><span class="help-inline"><?php echo $model->getError('gender');?></span><?php endif;?>
    </div>
</div>
<div class="control-group <?php if($model->hasErrors('website')) echo 'error';?>">
    <?php echo CHtml::activeLabel($model, 'website', array('class'=>'control-label'));?>
    <div class="controls">
        <?php echo CHtml::activeTextField($model, 'website', array('class'=>'span6'));?>
        <?php if($model->hasErrors('website')):?><p class="help-block"><?php echo $model->getError('website');?></p><?php endif;?>
    </div>
</div>
<div class="control-group <?php if($model->hasErrors('description')) echo 'error';?>">
    <?php echo CHtml::activeLabel($model, 'description', array('class'=>'control-label'));?>
    <div class="controls">
        <?php echo CHtml::activeTextArea($model, 'description', array('class'=>'span6', 'rows'=>5));?>
        <?php if($model->hasErrors('description')):?><p class="help-block"><?php echo $model->getError('description');?></p><?php endif;?>
    </div>
</div>
<div class="form-actions">
    <input type="submit" value="提交" class="btn btn-primary" />
</div>
<?php echo CHtml::endForm();?>
