<h4><?php echo $this->adminTitle;?></h4>
<div class="btn-toolbar">
    <a class="btn btn-small btn-success" href=''>刷新</a>
</div>
<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span2"><?php echo $sort->link('app_store_id');?></th>
            <th class="span3"><?php echo $sort->link('bundle_identifier');?></th>
            <th class="span2"><?php echo $sort->link('mac_address');?></th>
            <th class="span2"><?php echo $sort->link('promoter');?></th>
            <th class="span2 align-center"><?php echo $sort->link('create_time');?></th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="align-center"><?php echo $model->id;?></td>
            <td><?php echo $model->app_store_id;?></td>
            <td><?php echo $model->bundle_identifier;?></td>
            <td><?php echo $model->mac_address;?></td>
            <td><?php echo $model->promoter;?></td>
            <td class="align-center"><?php echo $model->createTime;?></td>
            <td>&nbsp;</td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="pagination"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

