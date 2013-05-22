<?php

/**
 * This is the model class for table "{{adcode}}".
 *
 * The followings are the available columns in table '{{adcode}}':
 * @property string $id
 * @property string $ad_id
 * @property string $adcode
 * @property string $intro
 * @property integer $weight
 * @property integer $state
 *
 * @property Advert $advert
 */
class Adcode extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Adcode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return TABLE_ADCODE;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('ad_id, intro, adcode', 'required'),
			array('weight, state, ad_id', 'numerical', 'integerOnly'=>true),
			array('intro', 'length', 'max'=>250),
			array('adcode', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		    'advert' => array(self::BELONGS_TO, 'Advert', 'ad_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ad_id' => '广告位ID',
			'adcode' => '广告代码',
	        'weight' => '权重',
			'intro' => '说明',
			'state' => '状态',
		);
	}

	/**
	 * 获取一个广告位的广告代码数据
	 * @param integer $adid 广告位ID
	 * @return array
	 */
	public static function fetchAdcodes($adid)
	{
	    $data = array();
	    $cmd = app()->getDb()->createCommand()
	        ->from(TABLE_ADCODE)
	        ->where(array('and', 'ad_id = :adid', 'state = :enabled'), array(':adid'=>$adid, ':enabled'=>CD_YES))
	        ->order(array('weight desc', 'id asc'));
	     
	    $data = $cmd->queryAll();
	     
	    return $data;
	}

	protected function beforeSave()
	{
	    if ($this->weight < 1)
	        $this->weight = 1;
	    
	    return true;
	}
	
    protected function afterFind()
    {
        $this->intro = nl2br($this->intro);
    }
}

