<table class="table table-striped table-bordered member-table">
    <thead>
        <tr>
            <th class="span1 acenter">ID</th>
            <th>内容</th>
            <th class="span1 acenter">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comments as $model):?>
        <tr>
            <td class="acenter"><?php echo $model->id;?></td>
            <td>
                <p><?php echo $model->post->getTitleLink(0);?></p>
                <?php echo $model->filterContent;?>
            </td>
            <td class="acenter">
                <a class="btn btn-mini" href="<?php echo $model->deleteUrl;?>" target="_blank"><i class="icon-trash"></i></a>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if (count($comments) == 0):?>
        <tr><td class="acenter" colspan="4">是男人就不要光看不顶啊，赶紧顶起来吧～～～</td></tr>
        <?php endif;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="beta-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'skin'=>'bootstrap', 'htmlOptions'=>array('class'=>'pagination')));?></div>
<?php endif;?>
