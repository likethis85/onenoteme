<?php

/**
 * This is the model class for table "{{category}}".
 *
 * The followings are the available columns in table '{{category}}':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $token
 * @property integer $orderid
 * @property integer $navshow
 * @property string $desc
 *
 * @property string $url
 *
 */
class Category extends CActiveRecord
{
    const ROOT_PARENT_ID = 0;
    const CACHEN_ID_PREFIX = 'categorys_cache_';
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Category the static model class
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
		return TABLE_CATEGORY;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('name, token', 'required'),
	        array('name, token', 'unique'),
			array('parent_id, orderid, navshowid', 'numerical', 'integerOnly'=>true),
			array('name, token', 'length', 'max'=>50),
			array('desc', 'length', 'max'=>250),
	        array('parent_id, orderid, navshow', 'filter', 'filter'=>'intval'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
	        'postCount' => array(self::STAT, 'Post', 'category_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => '父类ID',
			'name' => '频道名',
	        'token' => '标识',
			'orderid' => '排序ID',
			'navshow' => '导航栏显示',
		    'desc' => '简介',
		);
	}
	
	public function getUrl()
	{
	    return aurl('category/' . $this->token);
	}
	
	public static function fetchCategories(CDbCriteria $criteria = null, $cache = true)
	{
	    static $data = array();
	    
        $key = ($criteria === null) ? 'all' : md5($criteria->condition);
	    
	    if (array_key_exists($key, $data))
	        return $data[$key];

	    if ($criteria === null)
            $criteria = new CDbCriteria();
	    
	    if ($cache) {
	        $models = self::fetchCacheCategories($criteria);
	        if ($models !== false) {
	            $data[$key] = $models;
	            return $models;
	        }
	    }
	    
        $models = self::model()->findAll($criteria);
	    $data[$key] = $models;
	    self::refreshCache($criteria);
	    
	    return $models;
	}
	
	public static function fetchSubCategories($parentID = null, $navshow = null, $order = 't.orderid desc, t.id asc', $cache = true)
	{
	    $criteria = new CDbCriteria();
	    $criteria->order = $order;
	    if ($parentID !== null)
    	    $criteria->addColumnCondition(array('t.parent_id' => (int)$parentID));
	    if ($navshow !== null)
    	    $criteria->addColumnCondition(array('t.navshow' => (int)$navshow));
	    
	    $models = self::fetchCategories($criteria, $cache);
	    return $models;
	}
	
	public static function generateCacheID(CDbCriteria $criteria = null)
	{
	    $id = self::CACHEN_ID_PREFIX . ($criteria === null ? 'all' : md5($criteria->condition));
	    return $id;
	}
	
	public static function fetchCacheCategories(CDbCriteria $criteria = null)
	{
	    if (cache()) {
	        $cacheID = self::generateCacheID($criteria);
	        return cache()->get($cacheID);
	    }
	    else
	        return false;
	}
	
	public static function refreshCache(CDbCriteria $criteria = null, $expire = 0)
	{
	    if (cache()) {
            $value = self::fetchCategories($criteria, false);
            $cacheID = self::generateCacheID($criteria);
	        return cache()->set($cacheID, $value, $expire);
	    }
	    else
	        throw new Exception('cache component is not set');
	}
	
	public static function removeCache(CDbCriteria $criteria = null)
	{
	    if (cache()) {
	        $cacheID = self::generateCacheID($criteria);
	        return cache()->delete($cacheID);
	    }
	    else
	        throw new Exception('cache component is not set');
	}
	
	public static function findByToken($token)
	{
	    return self::model()->findByAttributes(array('token'=>$token));
	}

	protected function beforeSave()
	{
	    $this->desc = trim(strip_tags($this->desc));
	    return true;
	}
	
	protected function afterSave()
	{
	    $this->refreshCache();
	}
	
	protected function afterDelete()
	{
	    $this->removeCache();
        // @todo 此处重新生成缓存并且要检查频道下是否有内容
	}
}


