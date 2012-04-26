<?php
class AdminCategory extends Category
{
    /**
     * Returns the static model of the specified AR class.
     * @return AdminCategory the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public static function listData()
    {
        $rows = app()->getDb()->createCommand()
            ->select(array('id', 'name'))
            ->from(TABLE_NAME_CATEGORY)
            ->order(array('orderid desc', 'id'))
            ->queryAll();
        
        $data = CHtml::listData($rows, 'id', 'name');
        
        return $data;
    }
}