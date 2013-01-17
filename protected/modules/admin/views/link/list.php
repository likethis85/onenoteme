<?php if (user()->hasFlash('order_id_save_result_success')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('order_id_save_result_success');?>
</div>
<?php endif;?>
<?php if (user()->hasFlash('order_id_save_result_error')):?>
<div class="alert alert-error fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('order_id_save_result_error');?>
</div>
<?php endif;?>

<h4><?php echo user()->getFlash('table_caption', '链接列表');?></h4>
<?php echo CHtml::form(url('admin/link/updateOrderID'), 'post', array('class'=>'form-horizontal'));?>
<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="span1 align-center"><?php echo $sort->link('orderid');?></th>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span2"><?php echo $sort->link('name');?></th>
            <th class="span3 align-center"><?php echo $sort->link('url');?></th>
            <th class="span3 align-center"><?php echo $sort->link('desc');?></th>
            <th class="span2 align-center"><?php echo $sort->link('logo');?></th>
            <th class="span1 align-center"><?php echo $sort->link('ishome');?></th>
            <th><a class="label label-important" href="<?php echo url('admin/link/create');?>">新建链接</a></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="item-orderid"><input type="text" name="<?php echo sprintf('itemid[%d]', $model->id);?>" value="<?php echo $model->orderid;?>" class="input-mini" /></td>
            <td class="align-center"><?php echo $model->id;?></td>
            <td><?php echo $model->nameLink;?></td>
            <td><?php echo $model->url;?></td>
            <td><?php echo $model->desc;?></td>
            <td><?php echo $model->logoImage;?></td>
            <td><?php echo $model->ishomeLabel;?></td>
            <td>
                <?php echo l('编辑', url('admin/link/create', array('id'=>$model->id)));?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="pagination"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

<?php if (count($models) > 0):?>
<fieldset>
    <div class="form-actions">
        <a class="btn" href="<?php echo url('admin/link/create');?>">新建</a>
        <a class="btn" href="">刷新</a>
        <input type="submit" value="提交" class="btn btn-primary" />
    </div>
</fieldset>
<?php endif;?>
<?php echo CHtml::endForm();?>

<div class="alert alert-danger">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    排序提示：数字越小，排的越靠前
</div>

