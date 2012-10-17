<?php if (user()->hasFlash('save_link_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('save_link_result');?>
</div>
<?php endif;?>

<?php echo CHtml::form('', 'post', array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));?>
<fieldset>
    <legend><?php echo $this->adminTitle;?></legend>
    <div class="control-group <?php if($model->hasErrors('name')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'name', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'name');?>
            <?php if($model->hasErrors('name')):?><p class="help-block"><?php echo $model->getError('url');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('url')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'url', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'url', array('class'=>'span5'));?>
            <?php if($model->hasErrors('url')):?><p class="help-block"><?php echo $model->getError('url');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('logo')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'logo', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'logo', array('class'=>'span5'));?>
            <?php if($model->hasErrors('logo')):?><p class="help-block"><?php echo $model->getError('logo');?></p><?php endif;?>
        </div>
    </div>
    <div class="control-group <?php if($model->hasErrors('desc')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'desc', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'desc', array('class'=>'span5'));?>
            <?php if($model->hasErrors('desc')):?><p class="help-block"><?php echo $model->getError('desc');?></p><?php endif;?>
        </div>
    </div>
    <?php if ($model->logoValid):?>
    <div class="control-group">
        <div class="controls">
            <p><?php echo $model->getLogoImage();?></p>
        </div>
    </div>
    <?php endif;?>
    <div class="control-group <?php if($model->hasErrors('orderid')) echo 'error';?>">
        <?php echo CHtml::activeLabel($model, 'orderid', array('class'=>'control-label'));?>
        <div class="controls">
            <?php echo CHtml::activeTextField($model, 'orderid', array('class'=>'input-mini'));?>
            <span class="help-info"><?php echo t('link_orderid_sort_tip', 'admin');?></span>
            <?php if($model->hasErrors('orderid')):?><p class="help-block"><?php echo $model->getError('orderid');?></p><?php endif;?>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" value="<?php echo t('submit', 'admin');?>" class="btn btn-primary" />
        <a class="btn" href="<?php echo url('admin/link/list');?>"><?php echo t('return_list_page', 'admin');?></a>
    </div>
</fieldset>
<?php echo CHtml::endForm();?>

