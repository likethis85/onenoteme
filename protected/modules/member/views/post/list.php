<table class="table table-striped table-bordered member-table mypost-table">
    <thead>
        <tr>
            <th class="span1 acenter">ID</th>
            <th>标题</th>
            <th class="span2"><span id="ajax-tip"></span></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $model):?>
        <tr>
            <td class="acenter"><?php echo $model->id;?></td>
            <td><?php echo $model->stateHtml . $model->titleLink;?></td>
            <td>
                <?php //echo $model->editLink;?>
                <?php echo $model->deleteLink;?>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if (count($posts) == 0):?>
        <tr><td class="acenter" colspan="4">看了这么多了，还没有一个段子？抓紧行动吧～～～</td></tr>
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