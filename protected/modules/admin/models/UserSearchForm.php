<?php
class UserSearchForm extends CFormModel
{
    public $userid;
    public $username;
    public $screen_name;
    public $createTime;
    public $createIp;
    public $usernameFuzzy;
    public $screenNameFuzzy;
    public $state;
    
    public function rules()
    {
        return array(
            array('userid, state', 'numerical', 'integerOnly'=>true),
            array('username, screen_name', 'filter', 'filter'=>'trim'),
            array('usernameFuzzy, screenNameFuzzy', 'in', 'range'=>array(CD_YES, CD_NO)),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'userid' => 'UID',
            'username' => '账号',
            'screen_name' => '名字',
            'createTime' => '创建时间',
            'createIp' => '创建IP',
            'state' => '状态',
        );
    }
    
    public function search()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('t.state' => $this->state));
        if ($this->userid)
            $criteria->addColumnCondition(array('t.id'=>$this->userid));
        
        if ($this->username) {
            if ($this->usernameFuzzy)
                $criteria->addSearchCondition('t.username', $this->username);
            else
                $criteria->addColumnCondition(array('t.username'=>$this->username));
        }
        if ($this->screen_name) {
            if ($this->screenNameFuzzy)
                $criteria->addSearchCondition('t.screen_name', $this->screen_name);
            else
                $criteria->addColumnCondition(array('t.screen_name'=>$this->screen_name));
        }
         
        $data = $criteria->condition ? AdminUser::fetchList($criteria) : null;
        return $data;
    }
}