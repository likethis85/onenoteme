<?php echo CHtml::form(url('admin/post/search'), 'get', array('class'=>'form-horizontal'));?>
<fieldset>
    <legend>搜索段子</legend>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'postid', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'postid', array('class'=>'span2'));?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'keyword', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'keyword', array('class'=>'span4'));?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'author', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'author', array('class'=>'span4'));?>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="搜索" class="btn btn-primary" />
    </div>
</fieldset>
<?php echo CHtml::endForm();?>

<?php if ($data !== null) $this->renderPartial('list', $data);?>