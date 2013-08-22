<?php
/**
 * @property string $infoLink
 * @property string $editUrl
 * @property string $editLink
 * @property string $trashLink
 * @property string $deleteLink
 * @property string $verifyLink
 * @property string $adminTitleLink
 * @property string $hottestUrl
 * @property string $recommendUrl
 * @property string $homeshowUrl
 * @property string $commentUrl
 * @property string $topLink
 * @property string $commentNumsBadgeHtml
 * @property string $viewNumsBadgeHtml
 * @property string $previewLink
 */
class AdminPost extends Post
{
    /**
     * Returns the static model of the specified AR class.
     * @return AdminPost the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public static function stateLabels($state = null)
    {
        $labels = array(
            POST_STATE_ENABLED => '已上线',
            POST_STATE_DISABLED => '未显示',
            POST_STATE_UNVERIFY => '未审核',
            POST_STATE_TRASH => '回收站',
        );
        
        return $state === null ? $labels : $labels[$state];
    }
    
    public static function updateStateLabels()
    {
        $labels = array(
            POST_STATE_ENABLED => '上线',
            POST_STATE_DISABLED => '隐藏',
            POST_STATE_TRASH => '回收站',
        );
        
        return $labels;
    }
    
    public static function fetchList($criteria = null, $sort = true, $pages = true)
    {
        $criteria = ($criteria === null) ? new CDbCriteria() : $criteria;
        if ($criteria->limit < 0)
            $criteria->limit = param('adminPostCountOfPage');
        
        if ($sort) {
            $sort  = new CSort(__CLASS__);
            $sort->defaultOrder = 't.id desc';
            $sort->applyOrder($criteria);
        }
        else
            $criteria->order = 't.id desc';
         
        if ($pages) {
            $count = self::model()->count($criteria);
            $pages = new CPagination($count);
            $pages->setPageSize($criteria->limit);
            $pages->applyLimit($criteria);
        }

        $models = self::model()->findAll($criteria);

        $data = array(
            'models' => $models,
            'sort' => $sort,
            'pages' => $pages,
        );
         
        return $data;
    }

    public function getInfoLink()
    {
        return l('详情', url('admin/post/info', array('id'=>$this->id)));
    }
    
    public function getEditUrl()
    {
        return url('admin/post/create', array('id'=>$this->id));
    }
    
    public function getEditLink()
    {
        return l(h($this->title), $this->getEditUrl());
    }

    public function getTrashLink()
    {
        return l('删除', url('admin/post/settrash', array('id'=>$this->id)), array('class'=>'set-trash'));
    }

    public function getDeleteLink()
    {
        return l('删除', url('admin/post/setdelete', array('id'=>$this->id)), array('class'=>'set-delete'));
    }

    public function getVerifyLink()
    {
        $text = ($this->state == POST_STATE_DISABLED) ? '显示' : '隐藏';
        return l($text, url('admin/post/setVerify', array('id'=>$this->id)), array('class'=>'set-verify'));
    }

    public function getHottestUrl()
    {
        $text = ($this->hottest == CD_NO) ? '热门' : '取消热门';
        return l($text, url('admin/post/sethottest', array('id'=>$this->id)), array('class'=>'set-hottest'));
    }

    public function getRecommendUrl()
    {
        $text = ($this->recommend == CD_NO) ? '推荐' : '取消推荐';
        return l($text, url('admin/post/setrecommend', array('id'=>$this->id)), array('class'=>'set-recommend'));
    }
    
    public function getHomeshowUrl()
    {
        $text = ($this->homeshow == CD_YES) ? '非首页' : '首页显示';
        return l($text, url('admin/post/sethomeshow', array('id'=>$this->id)), array('class'=>'set-recommend'));
    }

    public function getCommentUrl()
    {
        return url('admin/comment/list', array('postid'=>$this->id));
    }

    public function getTopLink()
    {
        $text = ($this->istop == CD_NO) ? '置顶' : '取消置顶';
        return l($text, url('admin/post/settop', array('id'=>$this->id)), array('class'=>'set-top'));
    }

    public function getCommentNumsBadgeHtml()
    {
        $count = (int)$this->comment_nums;
        if ($count <= 10)
            $class = '';
        elseif ($count <= 50)
            $class = 'badge-warning';
        else
            $class = 'badge-error';
        
        $html = sprintf('<span class="badge beta-badge %s">%s</span>', $class, $count);
        $html = l($html, $this->commentUrl, array('title'=>'点击查看评论列表'));
        return $html;
    }

    public function getViewNumsBadgeHtml()
    {
        $count = (int)$this->view_nums;
        if ($count <= 2000)
            $class = '';
        elseif ($count <= 5000)
            $class = 'badge-warning';
        else
            $class = 'badge-error';
        
        $html = sprintf('<span class="badge beta-badge %s">%s</span>', $class, $count);
        return $html;
    }

    public function getPreviewLink()
    {
        return l('预览', $this->getUrl(), array('target'=>'_blank'));
    }

    public function getStateLabel()
    {
        $classes = array(
            POST_STATE_DISABLED => 'label-inverse',
            POST_STATE_ENABLED => 'label-success',
            POST_STATE_UNVERIFY => 'label-warning',
            POST_STATE_TRASH => '',
        );
        
        $labels = array(
            POST_STATE_DISABLED => '隐藏',
            POST_STATE_ENABLED => '上线',
            POST_STATE_UNVERIFY => '待审',
            POST_STATE_TRASH => '删除',
        );
        
        $html = '';
        if (array_key_exists($this->state, $classes))
            $html = '<span class="label label-small ' . $classes[$this->state] . '">' . $labels[$this->state] . '</span>';
        
        return $html;
    }
    
    public function getExtraStateLabels()
    {
        $html = '';
        if ($this->recommend == CD_YES)
            $html .= '<span class="label label-small label-success">推荐</span>';
        
        if ($this->hottest)
            $html .= '<span class="label label-small label-important">热门</span>';
        
        if ($this->homeshow)
            $html .= '<span class="label label-small label-info">首页</span>';
        
        if ($this->istop)
            $html .= '<span class="label label-small label-info">置顶</span>';
        
        return $html;
    }
}

