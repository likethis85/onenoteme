<h4><?php echo user()->getFlash('table_caption', sprintf('广告位：%s - %s', $advert->name, $advert->solt));?></h4>
<div class="btn-toolbar">
    <a class="btn btn-small btn-success" href="<?php echo url('admin/adcode/create', array('adid'=>$advert->id));?>">新建广告</a>
    <a href="" class="btn btn-small">刷新</a>
    <a href="<?php echo url('admin/advert/list');?>" class="btn btn-small">返回列表</a>
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
            <td>
                <div><label class="label label-info">权重：<?php echo $model->weight;?></label></div>
                <?php echo $model->intro;?>
            </td>
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
        <li>如果一个广告位有多个有效的广告，则启用随机显示其中</li>
    </ul>
</div>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.row-state', BetaAdmin.ajaxSetBooleanColumn);
	$(document).on('click', '.set-delete', BetaAdmin.deleteRow);
});
</script>