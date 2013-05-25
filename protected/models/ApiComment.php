<?php
class ApiComment extends Comment
{
    /**
     * Returns the static model of the specified AR class.
     * @return ApiComment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    

    public function getApiContent()
    {
        $content = str_replace(array('<p>', '<div>', '<br>', '<br />', '</div>', '</p>'), "\n", $this->content);
        $content = strip_tags($content);
        return $content;
    }
}