<div class="panel panel25">
    <ul class="post-list">
    <?php foreach ((array)$posts as $post):?>
        <li>
        <?php
            $text = empty($post['title']) ? mb_substr($post['content'], 0, 50, app()->charset) : $post['title'];
            $text .= '&nbsp;' . $post['tags'];
            echo l($text, aurl('post/show', array('id'=>(int)$post['id'])), array('target'=>'_blank'));
        ?>
        </li>
    <?php endforeach;?>
    </ul>
</div>
<?php if ($pages instanceof CPagination && $pages->pageCount > 1):?>
<div class="panel-rect panel-pages">
    <div class="cd-pages"><?php $this->widget('CLinkPager', array('pages'=>$pages));?></div>
</div>
<?php endif;?>