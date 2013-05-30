<?php
class ApiPost extends Post
{
    /**
     * Returns the static model of the specified AR class.
     * @return ApiPost the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array_merge(parent::relations(), array(
            'user' => array(self::BELONGS_TO, 'ApiUser', 'user_id',
    		        'select' => array('id', 'username', 'screen_name', 'create_time', 'create_ip', 'state', 'token', 'token_time', 'source')),
        ));
    }
    
    public function getApiContent()
    {
        $content = str_replace(array('<p>', '<div>', '<br>', '<br />', '</div>', '</p>'), "\n", $this->content);
        $content = strip_tags($content);
        return $content;
    }
}