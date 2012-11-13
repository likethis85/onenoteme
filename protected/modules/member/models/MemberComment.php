<?php
/**
 *
 * @author chendong
 * @property string $deleteUrl
 */
class MemberComment extends Comment
{
    /**
     * Returns the static model of the specified AR class.
     * @return MemberComment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function getStateHtml()
    {
        $classes = array(
            COMMENT_STATE_ENABLED => 'label label-success',
            COMMENT_STATE_DISABLED => 'label',
        );
        $class = $classes[$this->state];
    
        return sprintf('<span class="%s">%s</span>', $class, $this->getStateLabel());
    }
    
    public function getDeleteLink()
    {
        $html = '';
        if ($this->state == COMMENT_STATE_DISABLED) {
            $url = aurl('member/comment/delete', array('id'=>$this->id));
            $html = l('<i class="icon-trash icon-white"></i>', 'javascript:void(0);', array('class'=>'btn btn-mini btn-danger btn-delete', 'data-url'=>$url));
        }
        
        return $html;
    }
}

