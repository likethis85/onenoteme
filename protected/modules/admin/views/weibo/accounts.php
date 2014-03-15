<h4><?php echo $this->adminTitle;?></h4>
<div class="btn-toolbar">
    <button class="btn btn-small" onclick='javascript:location.reload();'>刷新</button>
    <a class="btn btn-small btn-primary" href='<?php echo aurl('admin/weibo/createaccount');?>'>添加账号</a>
</div>

<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span3"><?php echo $sort->link('display_name');?></th>
            <th class="span3"><?php echo $sort->link('user_name');?></th>
            <th class="span2 align-center"><?php echo $sort->link('post_nums');?></th>
            <th class="span3 align-center"><?php echo $sort->link('last_time');?></th>
            <th class="span2"><?php echo $sort->link('last_pid');?></th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="span1 align-center"><?php echo $model->id;?></td>
            <td class="span3"><?php echo l(h($model->display_name), $model->getEditUrl());?></td>
            <td class="span3"><?php echo l($model->user_name, $model->user->getInfoUrl());?></td>
            <td class="span2 align-center"><?php echo (int)$model->post_nums;?></td>
            <td class="span3 align-center"><?php echo $model->getLastTime();?></td>
            <td class="span2"><?php echo $model->last_pid;?></td>
            <td>
                <?php echo $model->getEditLink();?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="pagination"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

