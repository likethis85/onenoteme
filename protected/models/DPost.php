<?php
class DPost extends DModel
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_TOP = 2;
    
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{post}}';
    }
    
    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'category_id', 'topic_id', 'title', 'content', 'create_time', 'comment_nums', 'state');
    }
}