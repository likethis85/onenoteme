<?php

/**
 * This is the model class for table "{{topic}}".
 *
 * The followings are the available columns in table '{{topic}}':
 * @property integer $id
 * @property string $name
 * @property integer $create_time
 * @property integer $post_nums
 * @property integer $orderid
 */
class DTopic extends DModel
{
    /**
     * Returns the static model of the specified DModel class.
	 * @return DTopic the static model class
     */
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