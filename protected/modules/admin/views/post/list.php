<table class="table table-striped table-bordered post-list-table">
    <thead>
        <tr>
            <th class="span1">#</th>
            <th class="span1"><?php echo $sort->link('id');?></th>
            <th class="span6">标题</th>
            <th class="span1"><?php echo $sort->link('up_score');?></th>
            <th class="span1"><?php echo $sort->link('down_score');?></th>
            <th class="span1"><?php echo $sort->link('comment_nums');?></th>
            <th class="span2">标签</th>
            <th class="span2"><?php echo $sort->link('create_time');?></th>
            <th>发表人</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ((array)$models as $model):?>
        <tr>
            <td><?php echo CHtml::checkBox('ids[' . $model->id . ']');?></td>
            <td><?php echo $model->id;?></td>
            <td><?php echo $model->titleLink;?></td>
            <td><?php echo $model->up_score;?></td>
            <td><?php echo $model->down_score;?></td>
            <td><?php echo $model->comment_nums;?></td>
            <td><?php echo $model->tags;?></td>
            <td><?php echo $model->createTime;?></td>
            <td><?php echo $model->postUserName;?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>