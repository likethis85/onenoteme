<?php if (user()->hasFlash('save_config_success')):?>
<div class="alert alert-success">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('save_config_success');?>
</div>
<?php endif;?>
<?php if (count($errorNames) > 0):?>
<div class="alert alert-error">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <h4><?php echo t('the_following_names_error', 'admin');?></h4>
    <ul class="config-error-names">
        <?php foreach ($errorNames as $name):?>
        <li><?php echo $name;?></li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif;?>

<h4><?php echo $this->adminTitle;?></h4>
<form action='' method="post" class="form-horizontal">
<table class="table table-striped table-bordered beta-config-table">
    <thead>
        <tr>
            <th class="span1 align-center">ID</th>
            <th class="span2 align-right">参数名称/参数变量名</th>
            <th class="span6">参数值</th>
            <th class="span3">描述</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="align-center"><?php echo $model['id'];?></td>
            <td class="align-right config-name">
                <strong><?php echo h($model['name']);?></strong>
                <span class="cgray f12px"><?php echo $model['config_name'];?></span>
            </td>
            <td>
            <?php if (strlen($model['config_value']) < 50):?>
                <input class="span5" type="text" name="AdminConfig[<?php echo $model['config_name'];?>]" value="<?php echo h($model['config_value']);?>" />
            <?php else:?>
                <textarea class="span6" name="AdminConfig[<?php echo $model['config_name'];?>]"><?php echo h($model['config_value']);?></textarea>
            <?php endif;?>
            </td>
            <td><?php echo nl2br(h($model['desc']));?></td>
            <td>&nbsp;</td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="form-actions">
    <input type="submit" value="提交" class="btn btn-primary" />
</div>
</form>