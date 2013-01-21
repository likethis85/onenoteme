<?php echo CHtml::form(url('admin/comment/search'), 'get', array('class'=>'form-horizontal'));?>
<fieldset>
    <legend>搜索评论</legend>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'comment_id', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'comment_id', array('class'=>'input-small'));?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'post_id', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'post_id', array('class'=>'input-small'));?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'keyword', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'keyword');?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'user_id', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'user_id');?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'user_name', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'user_name');?>
        </div>
    </div>
    <div class="control-group">
        <?php echo CHtml::activeLabel($form, 'create_ip', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'create_ip');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">评论时间</label>
        <div class="controls">
            <?php echo CHtml::activeTextField($form, 'start_create_time', array('class'=>'span2'));?>&nbsp;-&nbsp;
            <?php echo CHtml::activeTextField($form, 'end_create_time', array('class'=>'span2'));?>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="搜索" class="btn btn-primary" />
    </div>
</fieldset>
<?php echo CHtml::endForm();?>

<?php if ($data !== null) $this->renderPartial('list', $data);?>