<h4><?php echo $this->adminTitle;?>&nbsp;&nbsp;&nbsp;<?php echo $post->titleLink;?></h4>
<div class="btn-toolbar">
    <button class="btn btn-small" id="select-all">全选</button>
    <button class="btn btn-small" id="reverse-select">反选</button>
    <button class="btn btn-small btn-primary" id="batch-verify" data-src="<?php echo url('admin/comment/multiVerify');?>">通过</button>
    <button class="btn btn-small btn-primary" id="batch-recommend" data-src="<?php echo url('admin/comment/multiRecommend');?>">推荐</button>
    <button class="btn btn-small btn-danger" id="batch-delete" data-src="<?php echo url('admin/comment/multiDelete');?>">删除</button>
    <a class="btn btn-small btn-success" href=''>刷新</a>
</div>
<table class="table table-striped table-bordered beta-list-table">
    <thead>
        <tr>
            <th class="item-checkbox align-center">#</th>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span8">内容</th>
            <th class="span1">来源</th>
            <th class="span2">评论人&nbsp;/&nbsp;<?php echo $sort->link('create_time');?></th>
            <th class="span2 align-center"><?php echo $sort->link('recommend');?>&nbsp;/&nbsp;<?php echo $sort->link('state');?></th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="item-checkbox"><input type="checkbox" name="itemids" value="<?php echo $model->id;?>" /></td>
            <td class="align-center"><?php echo $model->id;?></td>
            <td class="comment-content"><?php echo $model->content;?></td>
            <td><?php echo $model->sourceLabel;?></td>
            <td class="cgray">
                <?php echo $model->authorName;?><br />
                <span class="f12px"><?php echo $model->createTime;?></span>
            </td>
            <td class="align-cent2er">
                <?php echo $model->verifyUrl;?>
                <?php echo $model->recommendUrl;?><br />
                <?php echo $model->deleteUrl;?>
                <a href="<?php echo $model->post->getUrl();?>" target="_blank">段子</a>
            </td>
            <td></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php if ($pages):?>
<div class="pagination"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
<?php endif;?>

<script type="text/javascript">
$(function(){
	$(document).on('click', '.set-delete', {confirmText:confirmAlertText}, BetaAdmin.deleteRow);
	$(document).on('click', '.set-verify, .set-recommend', BetaAdmin.ajaxSetBooleanColumn);
	
	$(document).on('click', '#batch-delete', {confirmText:confirmAlertText}, BetaAdmin.deleteMultiRows);
	$(document).on('click', '#batch-verify', BetaAdmin.verifyMultiRows);
	$(document).on('click', '#batch-recommend', BetaAdmin.setMultiRowsMark);
	
	$(document).on('click', '#select-all', BetaAdmin.selectAll);
	$(document).on('click', '#reverse-select', BetaAdmin.reverseSelect);
	
	$(document).on('click', '.comment-content', function(event){
		$(this).find('fieldset').toggle();
	});
	
});
</script>

