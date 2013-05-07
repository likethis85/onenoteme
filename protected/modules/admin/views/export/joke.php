<?php echo CHtml::form('', 'post', array('class'=>'form-horizontal'));?>
<fieldset>
    <legend>导出内容</legend>
    <div class="control-group">
        <label class="control-label">日期</label>
        <div class="controls">
            <input type="text" name="date" value="<?php echo $date;?>" class="input-small" />
            <span class="help-line">格式：20130507</span>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="导出" class="btn btn-primary" />
    </div>
</fieldset>
<?php echo CHtml::endForm();?>

<?php if ($info):?>
<div class="alert alert-error"><?php echo $info;?></div>
<?php endif;?>