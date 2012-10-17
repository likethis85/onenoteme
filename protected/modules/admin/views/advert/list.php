<?php if (user()->hasFlash('clear_advert_all_cache_result')):?>
<div class="alert alert-success fade in">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <?php echo user()->getFlash('clear_advert_all_cache_result');?>
</div>
<?php endif;?>
<h4><?php echo user()->getFlash('table_caption', t('advert_list_table', 'admin'));?></h4>
<div class="btn-toolbar">
    <a class="btn btn-danger btn-small" href="<?php echo url('admin/advert/clearAllCache');?>"><?php echo t('clear_all_caceh', 'admin');?></a>
    <a class="btn btn-success btn-small" href="<?php echo url('admin/advert/create');?>"><?php echo t('create_advert', 'admin');?></a>
    <a href="" class="btn btn-small"><?php echo t('reload_data', 'admin');?></a>
</div>
<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span3"><?php echo $sort->link('name');?></th>
            <th class="span3"><?php echo $sort->link('solt');?></th>
            <th class="span1 align-center"><?php echo $sort->link('state');?></th>
            <th><a class="btn btn-mini btn-success" href="<?php echo url('admin/advert/create');?>"><?php echo t('create_advert', 'admin');?></a></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="align-center"><?php echo $model->id;?></td>
            <td><?php echo $model->nameLink;?></td>
            <td><?php echo $model->solt;?></td>
            <td class="align-center"><?php echo $model->stateLink;?></td>
            <td>
                <?php echo $model->editLink;?>
                <?php echo $model->deleteLink;?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="beta-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'htmlOptions'=>array('class'=>'pagination')));?></div>
<?php endif;?>

<div class="alert alert-block alert-info">
    <a href="javascript:void(0);" data-dismiss="alert" class="close">&times;</a>
    <ul>
        <li><?php echo t('clear_all_cache_tip', 'admin');?></li>
        <li><?php echo t('delete_advert_tip', 'admin');?></li>
    </ul>
</div>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.row-state', BetaAdmin.ajaxSetBooleanColumn);
	$(document).on('click', '.set-delete', {confirmText:confirmAlertText}, BetaAdmin.deleteRow);
	$(document).on('hover', '#clear-cache', function(event){
		$('#clear-cache').popover('toggle');
	});
});
</script>