<table class="table table-striped table-bordered post-list-table">
    <thead>
        <tr>
            <th class="span1">#</th>
            <th class="span1"><?php echo $sort->link('id');?></th>
            <th class="span6">标题</th>
            <th class="span2"><?php echo $sort->link('create_time');?></th>
            <th class="span2">发表人</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ((array)$models as $model):?>
        <tr>
            <td><?php echo CHtml::checkBox('ids[' . $model->id . ']');?></td>
            <td><?php echo $model->id;?></td>
            <td><?php echo $model->titleLink;?></td>
            <td><?php echo $model->createTime;?></td>
            <td><?php echo $model->postUserName;?></td>
            <td>
                <a href="#" class="one-verify">通过</a>
                <a href="#" class="one-delete">删除</a>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="pages">
<?php $this->widget('CLinkPager', array('pages'=>$pages));?>
</div>