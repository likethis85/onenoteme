<?php
/**
 * AdminAdcode
 * @author Chris
 * @property string $editUrl
 * @property string $editLink
 * @property string $stateLink
 * @property string $deleteLink
 */
class AdminAdcode extends Adcode
{
    /**
     * Returns the static model of the specified AR class.
     * @return AdminAdcode the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getEditUrl()
    {
        return url('admin/adcode/create', array('id'=>$this->id));
    }
    
    public function getEditLink()
    {
        return l(t('edit', 'admin'), $this->getEditUrl());
    }
    
    public function getStateLink()
    {
        $text = t($this->state == ADCODE_STATE_ENABLED ? 'adcode_enabled' : 'adcode_disabled', 'admin');
        $class = $this->state == ADCODE_STATE_ENABLED ? 'row-state label label-success' : 'row-state label label-important';
        return l($text, url('admin/adcode/setstate', array('id'=>$this->id)), array('class'=>$class));
    }
    
    public function getDeleteLink()
    {
        return l(t('delete', 'admin'), url('admin/adcode/setdelete', array('id'=>$this->id)), array('class'=>'set-delete'));
    }
}