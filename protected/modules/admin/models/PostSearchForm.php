<?php
class PostSearchForm extends CFormModel
{
    public $postid;
    public $author;
    public $keyword;
    public $start_create_time;
    public $end_create_time;
    
    public function rules()
    {
        return array(
            array('postid, start_create_time, end_create_time', 'numerical', 'integerOnly'=>true),
            array('author, keyword', 'filter', 'filter'=>'trim'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'postid' => '段子ID',
            'author' => '作者',
            'keyword' => '关键词',
        );
    }
    
    public function search()
    {
        $criteria = new CDbCriteria();
        if ($this->postid)
            $criteria->addColumnCondition(array('t.id'=>$this->postid));
        else {
            if ($this->author) {
                if (is_numeric($this->author))
                    $criteria->addColumnCondition(array('t.user_id'=>$this->author));
                elseif (is_string($this->author))
                    $criteria->addColumnCondition(array('t.user_name'=>$this->author));
            }
            
            if ($this->keyword)
                $criteria->addSearchCondition('t.title', $this->keyword);
        }
        $data = $criteria->condition ? AdminPost::fetchList($criteria) : null;
        return $data;
    }
}