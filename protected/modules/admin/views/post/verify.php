<table class="zebra-striped bordered-table post-list-table ">
    <thead>
        <tr>
            <th class="span1">#</th>
            <th class="span2"><?php echo $sort->link('id');?></th>
            <th class="span11">标题</th>
            <th class="span4"><?php echo $sort->link('create_time');?></th>
            <th class="span3">发表人</th>
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
                <button class="btn primary" id="pass-post">通过</button>
                <button class="btn danger" id="refuse-post">删除</button>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="pages">
<?php $this->widget('CLinkPager', array('pages'=>$pages));?>
</div>