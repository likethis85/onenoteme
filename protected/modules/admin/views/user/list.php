<table class="zebra-striped bordered-table user-list-table">
    <thead>
        <tr>
            <th class="span1">#</th>
            <th class="span2"><?php echo $sort->link('id');?></th>
            <th class="span4"><?php echo $sort->link('email');?></th>
            <th class="span4"><?php echo $sort->link('name');?></th>
            <th class="span4"><?php echo $sort->link('create_time');?></th>
            <th class="span3"><?php echo $sort->link('create_ip');?></th>
            <th class="span4">Token</th>
            <th class="span3">#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ((array)$models as $model):?>
        <tr>
            <td><?php echo CHtml::checkBox('ids[' . $model->id . ']');?></td>
            <td><?php echo $model->id;?></td>
            <td><?php echo $model->email;?></td>
            <td><?php echo $model->name;?></td>
            <td><?php echo $model->createTime;?></td>
            <td><?php echo $model->create_ip;?></td>
            <td><?php echo $model->token;?></td>
            <td>
            <?php
                if ($model->state == User::STATE_DISABLED)
                    echo CHtml::button('有效', array('class'=>'btn primary'));
                else
                    echo CHtml::button('禁用', array('class'=>'btn danger'));
            ?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>