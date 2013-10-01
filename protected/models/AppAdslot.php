<?php

/**
 * This is the model class for table "{{app_adslot}}".
 *
 * The followings are the available columns in table '{{app_adslot}}':
 * @property integer $id
 * @property string $name
 * @property string $slot
 * @property integer $platform
 * @property string $intro
 * @property integer $state
 */
class AppAdslot extends CActiveRecord
{
    public static function platforms()
    {
        return array_keys(self::platformLabesl());
    }
    
    public static function platformLabesl($platform = null)
    {
        $labels = array(
            PLATFORM_IPHONE => 'iPhone',
            PLATFORM_ANDROID => '安卓',
            PLATFORM_IPAD => 'iPad',
        );
        
        return $platform === null ? $labels : $labels[$platform];
    }
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return TABLE_APP_ADSLOT;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
	        array('slot, name', 'required'),
	        array('slot, name', 'unique'),
			array('platform, state', 'numerical', 'integerOnly'=>true),
			array('name, slot', 'length', 'max'=>50),
	        array('platform', 'in', 'range'=>self::platforms(), 'allowEmpty'=>false),
			array('intro', 'length', 'max'=>250),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'slot' => '标识符',
	        'platform' => '手机平台',
			'intro' => '说明',
			'state' => '状态',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return AppAdslot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 清除当前广告位的广告代码缓存
	 * @return boolean 执行结果
	 */
	public function clearCache()
	{
	    return self::clearslotCache($this->slot);
	}
	
	/**
	 * 根据slot清除单个广告位的广告代码缓存
	 * @param string $slot
	 * @return boolean 清除结果
	 */
	public static function clearslotCache($slot)
	{
	    $cacheID = sprintf(param('cache_app_adverts_id') ,$slot);
	    if (app()->getCache()) {
	        $result = app()->getCache()->set($cacheID, null);
	        app()->getCache()->delete($cacheID);
	        return $result;
	    }
	    else
	        return true;
	}
	
	/**
	 * 清除所有广告代码的缓存
	 * @throws CException 如果清除失败，抛出错误
	 * @return boolean 清除结果，成功true, 失败false
	 */
	public static function clearAllCache()
	{
	    $slots = app()->getDb()->createCommand()
    	    ->select('slot')
    	    ->from(TABLE_APP_ADSLOT)
    	    ->queryColumn();
	
	    foreach ((array)$slots as $slot) {
	        $result = self::clearslotCache($slot);
	        if (!$result)
	            throw new CException('clear cache error, slot: ' .$slot);
	    }
	
	    return true;
	}
}

