<?php echo CHtml::form(url('admin/user/search'), 'get', array('class'=>'form-horizontal'));?>
<fieldset>
    <legend>搜索用户</legend>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'userid', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'userid', array('class'=>'input-mini'));?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'username', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'username');?>
            <div class="input-append">
                <label class="checkbox"><?php echo CHtml::activeCheckBox($form, 'usernameFuzzy');?>模糊查询</label>
            </div>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'screen_name', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'screen_name');?>
            <div class="input-append">
                <label class="checkbox"><?php echo CHtml::activeCheckBox($form, 'screenNameFuzzy');?>模糊查询</label>
            </div>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'state', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeDropDownList($form, 'state', AdminUser::stateLabels());?>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="搜索" class="btn btn-primary" />
    </div>
</fieldset>
<?php echo CHtml::endForm();?>

<?php if ($data !== null) $this->renderPartial('list', $data);?>