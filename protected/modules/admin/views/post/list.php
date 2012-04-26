<h3>段子列表</h3>
<div class="btn-toolbar">
    <button class="btn btn-small btn-success">通过</button>
    <button class="btn btn-small btn-danger">删除</button>
</div>
<table class="table table-striped table-bordered post-list-table">
    <thead>
        <tr>
            <th class="span1 align-center">#</th>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span5">标题</th>
            <th class="span2 align-center">
                <?php echo $sort->link('up_score');?>/
                <?php echo $sort->link('down_score');?>/
                <?php echo $sort->link('comment_nums');?>
            </th>
            <th class="span2">标签</th>
            <th class="span2 align-center"><?php echo $sort->link('create_time');?></th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ((array)$models as $model):?>
        <tr>
            <td class="align-center"><?php echo CHtml::checkBox('ids[' . $model->id . ']');?></td>
            <td class="align-center"><?php echo $model->id;?></td>
            <td><?php echo $model->titleLink;?></td>
            <td class="align-center"><?php echo $model->up_score . '&nbsp;/&nbsp;' . $model->down_score . '&nbsp;/&nbsp;' . $model->comment_nums;?></td>
            <td><?php echo $model->tags;?></td>
            <td>
                <?php echo $model->createTime;?><br />
                <?php echo $model->postUserName;?>
            </td>
            <td>&nbsp;</td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>