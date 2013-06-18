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
        <span class="help-inline"><a href="<?php echo url('member/profile/nickname');?>">修改</a></span>
    </div>
</div>
<div class="control-group <?php if($model->hasErrors('gender')) echo 'error';?>">
    <?php echo CHtml::activeLabel($model, 'gender', array('class'=>'control-label'));?>
    <div class="controls">
        <?php echo CHtml::activeDropDownList($model, 'gender', MemberUserProfile::genderLabel());?>
        <?php if($model->hasErrors('gender')):?><span class="help-inline"><?php echo $model->getError('gender');?></span><?php endif;?>
    </div>
</div>
<div class="control-group <?php if($model->hasErrors('website')) echo 'error';?>">
    <?php echo CHtml::activeLabel($model, 'website', array('class'=>'control-label'));?>
    <div class="controls">
        <?php echo CHtml::activeTextField($model, 'website', array('class'=>'span5'));?>
        <?php if($model->hasErrors('website')):?><p class="help-block"><?php echo $model->getError('website');?></p><?php endif;?>
    </div>
</div>
<div class="control-group <?php if($model->hasErrors('description')) echo 'error';?>">
    <?php echo CHtml::activeLabel($model, 'description', array('class'=>'control-label'));?>
    <div class="controls">
        <?php echo CHtml::activeTextArea($model, 'description', array('class'=>'span5', 'rows'=>5));?>
        <?php if($model->hasErrors('description')):?><p class="help-block"><?php echo $model->getError('description');?></p><?php endif;?>
    </div>
</div>

<?php if ($this->user->getUnVerified()):?>
<div class="control-group">
    <div class="controls">
        <div class="alert alert-error">
        <?php if (param('user_required_admin_verfiy')):?>
        账号还未通过管理员审核，请耐心等待。
        <?php else:?>
        账号还未通过邮箱确认，如果您没有收到邮件，请点此“<a href="<?php echo aurl('member/profile/sendmail');?>">重新发送</a>”确认邮件
        <?php endif;?>
        </div>
    </div>
</div>
<?php endif;?>
<div class="form-actions">
    <input type="submit" value="提交" class="btn btn-primary" />
</div>
<?php echo CHtml::endForm();?>
