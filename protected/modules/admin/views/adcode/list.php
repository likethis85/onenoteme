<h4><?php echo user()->getFlash('table_caption', t('advert_solt', 'admin', array('{name}'=>$advert->name, '{solt}'=>$advert->solt)));?></h4>
<div class="btn-toolbar">
    <a class="btn btn-small btn-success" href="<?php echo url('admin/adcode/create', array('adid'=>$advert->id));?>"><?php echo t('create_adcode', 'admin');?></a>
    <a href="" class="btn btn-small"><?php echo t('reload_data', 'admin');?></a>
    <a href="<?php echo url('admin/advert/list');?>" class="btn btn-small"><?php echo t('return_advert_list', 'admin');?></a>
</div>
<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span2"><?php echo $sort->link('intro');?></th>
            <th><?php echo $sort->link('adcode');?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="align-center">
                <?php echo $model->id;?><br /><br />
                <?php echo $model->stateLink;?><br /><br />
                <?php echo $model->editLink;?><br />
                <?php echo $model->deleteLink;?>
            </td>
            <td><?php echo $model->intro;?></td>
            <td><textarea name="adcode" class="span6" rows="10" readonly="readonly"><?php echo $model->adcode;?></textarea></td>
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
        <li><?php echo t('multi_adcode_tip', 'admin');?></li>
    </ul>
</div>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.row-state', BetaAdmin.ajaxSetBooleanColumn);
	$(document).on('click', '.set-delete', {confirmText:confirmAlertText}, BetaAdmin.deleteRow);
});
</script>