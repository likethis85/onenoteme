<?php
/**
 * @property string $editUrl
 * @property string $deleteUrl
 * @property string $verifyUrl
 */
class AdminComment extends Comment
{
    /**
     * Returns the static model of the specified AR class.
     * @return AdminComment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function getInfoUrl()
    {
        return url('admin/comment/info', array('id'=>$this->id));
    }
    
    public function getAdminTitleLink($target = 'main')
    {
	    if ($this->istop == CD_YES)
	        $title = '<strong>[置顶]' . $this->title . '</strong>';
	    else
	        $title = $this->title;
	    
	    return l($title, $this->getInfoUrl(), array('class'=>'post-title', 'target'=>$target));
    }
    
    public function fetchList($criteria = null, $sort = true, $pages = true)
    {
        $criteria = ($criteria === null) ? new CDbCriteria() : $criteria;
        if ($criteria->limit < 0)
            $criteria->limit = param('adminCommentCountOfPage');
         
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
         
        $models = self::model()->with('post')->findAll($criteria);
         
        $data = array(
            'models' => $models,
            'sort' => $sort,
            'pages' => $pages,
        );
         
        return $data;
    }

    public function getEditUrl()
    {
        return l('编辑', url('admin/comment/create', array('id'=>$this->id)));
    }

    public function getDeleteUrl()
    {
        return l('删除', url('admin/comment/delete', array('id'=>$this->id)), array('class'=>'set-delete'));
    }

    public function getVerifyUrl()
    {
        $text = ($this->state == COMMENT_STATE_DISABLED) ? '隐藏' : '显示';
        $class = $this->state == COMMENT_STATE_DISABLED ? 'label label-important' : 'label label-success';
        return l($text, url('admin/comment/setVerify', array('id'=>$this->id)), array('class'=>'set-verify ' . $class));
    }

    public function getRecommendUrl()
    {
        $text = ($this->recommend == CD_NO) ? '未推荐' : '已推荐';

        $class = $this->recommend == COMMENT_STATE_DISABLED ? 'label label-important' : 'label label-success';
        return l($text, url('admin/comment/setRecommend', array('id'=>$this->id)), array('class'=>'set-recommend ' . $class));
    }
}