<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 14-3-15
 * Time: 下午6:32
 */

class AdminWeiboAccount extends WeiboAccount
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AdminWeiboAccount the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'user' => array(self::BELONGS_TO, 'AdminUser', 'user_id'),
        ));
    }

    public function getEditUrl()
    {
        return aurl('admin/weibo/createaccount', array('id'=>$this->id));
    }

    public function getDeleteUrl()
    {
        return aurl('admin/weibo/deleteaccount', array('id'=>$this->id));
    }

    public function getEditLink()
    {
        return l('编辑', $this->getEditUrl(), array('class'=>'btn btn-small'));
    }

    public function getDeleteLink()
    {
        return l('删除', $this->getDeleteUrl(), array('class'=>'btn btn-small btn-danger set-delete'));
    }
} 