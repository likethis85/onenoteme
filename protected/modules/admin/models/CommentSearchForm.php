<?php
class CommentSearchForm extends CFormModel
{
    public $comment_id;
    public $post_id;
    public $user_id;
    public $user_name;
    public $keyword;
    public $start_create_time;
    public $end_create_time;
    public $create_ip;
    
    public function rules()
    {
        return array(
            array('comment_id, post_id, user_id, start_create_time, end_create_time', 'numerical', 'integerOnly'=>true),
            array('user_name, keyword, create_ip', 'filter', 'filter'=>'trim'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'comment_id' => '评论ID',
            'post_id' => '段子ID',
            'user_id' => '评论人ID',
            'user_name' => '评论人',
            'keyword' => '关键字',
            'start_create_time' => '起始时间',
            'end_create_time' => '结束时间',
            'create_ip' => '用户IP',
        );
    }
    
    public function search()
    {
        $criteria = new CDbCriteria();
        if ($this->comment_id) {
            if ($this->comment_id)
                $criteria->addColumnCondition(array('t.id'=>$this->comment_id));
        } else {
            if ($this->post_id)
                $criteria->addColumnCondition(array('t.post_id'=>$this->post_id));
            if ($this->user_id)
                $criteria->addColumnCondition(array('t.user_id'=>$this->user_id));
            if ($this->user_name)
                $criteria->addColumnCondition(array('t.user_name'=>$this->user_name));
            if ($this->keyword)
                $criteria->addSearchCondition('t.content', $this->keyword);
            if ($this->create_ip)
                $criteria->addSearchCondition('t.create_ip', $this->create_ip);
            
            if ($this->start_create_time || $this->end_create_time) {
                $criteria->addCondition(array('and', 't.create_time > :starttime', 't.create_time < :endtime'));
                $starttime = (int)$this->start_create_time ? strtotime($this->start_create_time) : 0;
                $endtime = (int)$this->end_create_time ? strtotime($this->end_create_time) : $_SERVER['REQUEST_TIME'];
                $params = array(':starttime' => $starttime, ':endtime' => $endtime);
                $criteria->params = array_merge($criteria->params, $params);
            }
        }
        $data = $criteria->condition ? AdminComment::fetchList($criteria) : null;
        return $data;
    }
}