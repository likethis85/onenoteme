<?php
/**
 * AdminAdcode
 * @author Chris
 * @property string $editUrl
 * @property string $editLink
 * @property string $stateLink
 * @property string $deleteLink
 */
class AdminAppAdvert extends AppAdvert
{
    /**
     * Returns the static model of the specified AR class.
     * @return AdminAppAdvert the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getEditUrl()
    {
        return url('admin/appadvert/create', array('id'=>$this->id));
    }
    
    public function getEditLink()
    {
        return l('编辑', $this->getEditUrl());
    }
    
    public function getStateLink()
    {
        $text = $this->state == APP_ADVERT_STATE_ENABLED ? '启用' : '禁用';
        $class = $this->state == APP_ADVERT_STATE_ENABLED ? 'row-state label label-success' : 'row-state label label-important';
        return l($text, url('admin/appadvert/setstate', array('id'=>$this->id)), array('class'=>$class));
    }
    
    public function getDeleteLink()
    {
        return l('删除', url('admin/appadvert/setdelete', array('id'=>$this->id)), array('class'=>'set-delete'));
    }
}