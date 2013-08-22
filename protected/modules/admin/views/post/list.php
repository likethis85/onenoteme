<h4><?php echo user()->getFlash('table_caption', $this->adminTitle);?></h4>
<div class="btn-toolbar">
    <button class="btn btn-small" id="select-all">全选</button>
    <button class="btn btn-small" id="reverse-select">反选</button>
    <?php if (strtolower($this->action->id) == 'verify'):?>
    <button class="btn btn-small btn-primary" id="batch-verify" data-src="<?php echo url('admin/post/multiVerify');?>">通过</button>
    <?php elseif (strtolower($this->action->id) != 'trash'):?>
    <button class="btn btn-small btn-primary" id="batch-recommend" data-src="<?php echo url('admin/post/multiRecommend');?>">推荐</button>
    <button class="btn btn-small btn-primary" id="batch-hottest" data-src="<?php echo url('admin/post/multiHottest');?>">热门</button>
    <div class="btn-group">
        <a class="btn btn-small btn-danger dropdown-toggle" data-toggle="dropdown" href="#">
            更改状态&nbsp;<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php foreach (AdminPost::updateStateLabels() as $state => $label):?>
            <li><a href="javascript:void(0);" class="batch-state" data-src="<?php echo url('admin/post/multiState', array('state'=>$state));?>"><?php echo $label;?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
    <?php if (strtolower($this->action->id) == 'trash'):?>
    <button class="btn btn-small btn-danger" id="batch-delete" data-src="<?php echo url('admin/post/multiDelete');?>">永久删除</button>
    <?php else:?>
    <button class="btn btn-small btn-danger" id="batch-trash" data-src="<?php echo url('admin/post/multiTrash');?>">放入回收站</button>
    <?php endif;?>
    <div class="btn-group">
        <a class="btn btn-small btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
            频道过滤&nbsp;<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo $this->channelUrl();?>">所有频道</a>
            <?php foreach (CDBase::channelLabels() as $channelID => $label):?>
            <li><a href="<?php echo $this->channelUrl($channelID);?>"><?php echo $label;?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-small btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
            类型过滤&nbsp;<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo $this->mediatypeUrl();?>">所有频道</a>
            <?php foreach (CDBase::mediaTypeLabels() as $mediatype => $label):?>
            <li><a href="<?php echo $this->mediatypeUrl($mediatype);?>"><?php echo $label;?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <div class="btn-group">
        <a class="btn btn-small btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
            状态过滤&nbsp;<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo $this->stateUrl();?>">所有状态</a>
            <?php foreach (AdminPost::updateStateLabels() as $state => $label):?>
            <li><a href="<?php echo $this->stateUrl($state);?>"><?php echo $label;?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <a class="btn btn-small btn-success" href="">刷新</a>
    
</div>

<table class="table table-striped table-bordered beta-list-table table-post-list">
    <thead>
        <tr>
            <th class="item-checkbox align-center">#</th>
            <th class="span1 align-center"><?php echo $sort->link('id');?></th>
            <th class="span6"><?php echo $sort->link('title');?></th>
            <th class="span1">频道</th>
            <th class="span1 align-center"><?php echo $sort->link('comment_nums');?></th>
            <th class="span1 align-center"><?php echo $sort->link('view_nums');?></th>
            <th class="span2 align-center"><?php echo $sort->link('create_time');?></th>
            <th class="span1 align-center">#</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model):?>
        <tr>
            <td class="item-checkbox"><input type="checkbox" name="itemid[]" value="<?php echo $model->id;?>" /></td>
            <td class="align-center post-preivew-link">
                <?php echo $model->id;?>
                <div class="hide quick-links"><?php echo $model->previewLink;?></div>
            </td>
            <td class="post-quick-edit">
                <?php echo $model->getStateLabel() . $model->editLink . $model->getExtraStateLabels();?>
                <form class="form-inline invisible state-update-block" method="post" action="<?php echo url('admin/post/quickUpdate', array('id'=>$model->id));?>">
                    <label class="checkbox">
                        <?php echo CHtml::activeCheckBox($model, 'homeshow');?>首页显示
                    </label>
                    <label class="checkbox">
                        <?php echo CHtml::activeCheckBox($model, 'hottest');?>热门
                    </label>
                    <label class="checkbox">
                        <?php echo CHtml::activeCheckBox($model, 'recommend');?>推荐
                    </label>
                    <label class="checkbox">
                        <?php echo CHtml::activeCheckBox($model, 'istop');?>置顶
                    </label>
                    <label class="checkbox">
                        <?php echo CHtml::activeCheckBox($model, 'disable_comment');?>禁止评论
                    </label>
                    <label class="checkbox">
                        <?php echo CHtml::activeDropDownList($model, 'state', AdminPost::updateStateLabels(), array('class'=>'select-mini'));?>
                    </label>
                    <button data-toggle="button" data-loading-text="更新中..." data-error-text="更新出错" data-complete-text="更新完成" class="btn-update-state btn btn-mini">更新</button>
                </form>
            </td>
            <td><?php echo l($model->getChannelLabel(), $this->channelUrl($model->channel_id));?></td>
            <td class="align-center"><?php echo $model->commentNumsBadgeHtml;?><br /></td>
            <td class="align-center"><?php echo $model->viewNumsBadgeHtml;?><br /></td>
            <td class="cgray">
                <?php echo $model->authorName;?><br />
                <span class="f12px"><?php echo $model->createTime;?></span>
            </td>
            <td class="align-center">
                <?php if (strtolower($this->action->id) != 'trash'):?>
                <?php echo $model->trashLink;?><br />
                <?php endif;?>
                <?php echo $model->infoLink;?>
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
	$(document).on('click', '.set-trash, .set-delete', {confirmText:confirmAlertText}, BetaAdmin.deleteRow);
	$(document).on('click', '#batch-delete, #batch-trash', {confirmText:confirmAlertText}, BetaAdmin.deleteMultiRows);
	$(document).on('click', '.batch-state', {confirmText:confirmAlertText}, function(event){
		event.preventDefault();
		BetaAdmin.setMultiRowsState(event, function(data){
			window.location.reload();
		});
	});
	$(document).on('click', '#batch-recommend, #batch-hottest', BetaAdmin.setMultiRowsMark);
	$(document).on('click', '#batch-verify', BetaAdmin.verifyMultiRows);
	$(document).on('click', '#batch-reject', {confirmText:confirmAlertText}, BetaAdmin.rejectMultiPosts);
	
	$(document).on('click', '#select-all', BetaAdmin.selectAll);
	$(document).on('click', '#reverse-select', BetaAdmin.reverseSelect);

	$(document).on('click', '.btn-update-state', BetaAdmin.quickUpdate);

	$('table td.post-quick-edit').mouseenter(function(event){
		$(this).find('.state-update-block').animate({'visibility':'hidden'},200, function(){
			$(this).css('visibility', 'visible');
		});
	});
	$('table td.post-quick-edit').mouseleave(function(event){
		$(this).find('.state-update-block').stop(true, true).css('visibility', 'hidden');
	});
	$('table td.post-preivew-link').mouseenter(function(event){
		$(this).find('.quick-links').hide().delay(150).show(1);
	});
	$('table td.post-preivew-link').mouseleave(function(event){
		$(this).find('.quick-links').stop(true, true).hide();
	});
});
</script>

