<h4><?php echo user()->getFlash('table_caption', $this->adminTitle);?></h4>
<table class="table table-striped table-bordered post-info">
    <tbody>
        <tr>
            <td>ID</td>
            <td><span class="badge badge-success"><?php echo $model->id;?></span></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'title');?></td>
            <td><?php echo $model->titleLink;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'channel_id');?></td>
            <td><?php echo $model->channel_id . ' - ' . $model->getChannelLabel();?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'tags');?></td>
            <td><?php echo $model->tagText;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'create_time');?></td>
            <td><?php echo $model->createTime;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'create_ip');?></td>
            <td><?php echo $model->create_ip;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'thumbnail_pic');?></td>
            <td><?php echo l($model->getThumbnailImage(), $model->getBmiddlePic(), array('target'=>'_blank'));?></td>
        </tr>
    </tbody>
</table>

<table class="table table-striped table-bordered post-info">
    <tbody>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'comment_nums');?></td>
            <td>
                <span class="badge "><?php echo $model->comment_nums;?></span>&nbsp;&nbsp;
                <?php echo l('查看评论', $model->commentUrl);?>
            </td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'view_nums');?></td>
            <td><span class="badge "><?php echo $model->view_nums;?></span></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'up_score');?></td>
            <td><span class="badge "><?php echo $model->up_score;?></span></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'down_score');?></td>
            <td><span class="badge "><?php echo $model->down_score;?></span></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'favorite_count');?></td>
            <td><span class="badge "><?php echo $model->favorite_count;?></span></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'state');?></td>
            <td><?php echo AdminPost::stateLabels($model->state);?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'istop');?></td>
            <td><?php echo $model->istop;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'disable_comment');?></td>
            <td><?php echo $model->disable_comment;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'recommend');?></td>
            <td><?php echo $model->recommend;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'hottest');?></td>
            <td><?php echo $model->hottest;?></td>
        </tr>
        <tr>
            <td><?php echo CHtml::activeLabel($model, 'homeshow');?></td>
            <td><?php echo $model->homeshow;?></td>
        </tr>
    </tbody>
</table>
