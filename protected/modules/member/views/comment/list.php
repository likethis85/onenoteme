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
            <td class="acenter"><p><?php echo $model->id;?></p><?php echo $model->stateHtml;?></td>
            <td>
                <p><?php echo $model->post->getTitleLink(0);?></p>
                <?php echo $model->filterContent;?>
            </td>
            <td class="acenter">
                <?php echo $model->deleteLink;?>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if (count($comments) == 0):?>
        <tr><td class="acenter" colspan="4">是男人就不要光看不顶啊，赶紧顶起来吧～～～</td></tr>
        <?php endif;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="pagination"><?php $this->widget('CLinkPager', array('pages'=>$pages, 'skin'=>'bootstrap'));?></div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$('table').on('click', '.btn-delete', function(event){
		event.preventDefault();
		CDMember.executeAjaxDelete(event);
	});
});
</script>