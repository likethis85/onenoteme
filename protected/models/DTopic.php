<?php
class DTopic extends DModel
{
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{topic}}';
    }

    public function pk()
    {
        return 'id';
    }
    
    public function columns()
    {
        return array('id', 'name', 'create_time', 'post_nums', 'orderid');
    }
}