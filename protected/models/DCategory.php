<?php
class DCategory extends DModel
{
    public static function model($class = __CLASS__)
    {
        return parent::model($class);
    }
    
    public function table()
    {
        return '{{category}}';
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