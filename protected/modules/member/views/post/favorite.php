<table class="table table-striped table-bordered member-table">
    <thead>
        <tr>
            <th class="span1 acenter">ID</th>
            <th>标题</th>
            <th class="span1 acenter">操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $model):?>
        <tr>
            <td class="acenter"><?php echo $model->id;?></td>
            <td><?php echo $model->getTitleLink(0);?></td>
            <td class="acenter">
                <a class="btn btn-mini" href="<?php echo $model->deleteUrl;?>" target="_blank"><i class="icon-trash"></i></a>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if (count($posts) == 0):?>
        <tr><td class="acenter" colspan="4">看了这么多了，还没有收藏一个段子？抓紧行动吧～～～</td></tr>
        <?php endif;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="beta-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'skin'=>'bootstrap', 'htmlOptions'=>array('class'=>'pagination')));?></div>
<?php endif;?>
