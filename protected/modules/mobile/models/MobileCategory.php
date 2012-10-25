<?php
class MobileCategory extends Category
{
    /**
     * Returns the static model of the specified AR class.
     * @return MobileCategory the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getPostsUrl()
    {
        return aurl('mobile/category/posts', array('id'=>$this->id));
    }
    
    public function getPostsLink($target = '_self')
    {
        $optionsHtml = empty($target) ? null : array('target'=>$target);
        return l($this->name, $this->getPostsUrl(), $optionsHtml);
    }
}