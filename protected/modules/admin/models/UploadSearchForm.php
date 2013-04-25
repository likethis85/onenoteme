<?php
class UploadSearchForm extends CFormModel
{
    public $postid;
    public $fileType;
    public $fileUrl;
    public $userid;
    public $createTime;
    public $createIp;
    public $keyword;
    
    public function rules()
    {
        return array(
            array('postid, userid, fileType', 'numerical', 'integerOnly'=>true),
            array('keyword', 'filter', 'filter'=>'trim'),
            array('keyword', 'length', 'max'=>50),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'userid' => '用户ID',
            'fileType' => '类型',
            'postid' => '文章ID',
            'createTime' => '时间',
            'createIp' => 'IP',
            'fileUrl' => 'URL',
            'keyword' => '关键字',
        );
    }
    
    public function search()
    {
        $criteria = new CDbCriteria();
        if ($this->postid)
            $criteria->addColumnCondition(array('post_id'=>$this->postid));
        
        if ($this->userid)
            $criteria->addColumnCondition(array('user_id'=>$this->userid));
        
        if ($this->fileType)
            $criteria->addColumnCondition(array('file_type'=>$this->fileType));
        
        if ($this->keyword)
            $criteria->addSearchCondition('desc', $this->keyword);
        
        if ($this->fileUrl)
            $criteria->addSearchCondition('url', $this->fileUrl);
         
        $data = $criteria->condition ? AdminUpload::fetchList($criteria) : null;
        return $data;
    }
}