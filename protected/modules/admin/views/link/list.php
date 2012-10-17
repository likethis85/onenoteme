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

<h4><?php echo user()->getFlash('table_caption', t('link_list_table', 'admin'));?></h4>
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
            <th><a class="label label-important" href="<?php echo url('admin/link/create');?>"><?php echo t('create_link', 'admin');?></a></th>
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
            <td>
                <?php echo l(t('edit', 'admin'), url('admin/link/create', array('id'=>$model->id)));?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="beta-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'htmlOptions'=>array('class'=>'pagination')));?></div>
<?php endif;?>

<?php if (count($models) > 0):?>
<fieldset>
    <div class="form-actions">
        <input type="submit" value="<?php echo t('submit', 'admin');?>" class="btn btn-primary" />
        <a class="btn" href="<?php echo url('admin/link/create');?>"><?php echo t('create_link', 'admin');?></a>
    </div>
</fieldset>
<?php endif;?>
<?php echo CHtml::endForm();?>

<div class="alert alert-danger">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo t('link_orderid_sort_tip', 'admin');?>
</div>

