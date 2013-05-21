<?php
class AdminConfig extends Config
{
    const TYPE_SYSTEM = 0;
    const TYPE_CUSTOM = 1;
    
    const CATEGORY_CUSTOM = 1;
    
    const CATEGORY_SYSTEM = 10;
    const CATEGORY_SYSTEM_SITE = 11;
    const CATEGORY_SYSTEM_CACHE = 13;
    const CATEGORY_SYSTEM_ATTACHMENTS = 14;
    const CATEGORY_SYSTEM_EMAIL = 15;
    
    const CATEGORY_DISPLAY = 20;
    const CATEGORY_DISPLAY_TEMPLATE = 21;
    const CATEGORY_DISPLAY_UI = 22;
    
    const CATEGORY_SNS = 30;
    const CATEGORY_SNS_INTERFACE = 31;
    const CATEGORY_SNS_STATS = 32;
    const CATEGORY_SNS_TEMPLATE = 33;
    
    const CATEGORY_SEO = 40;
    const CATEGORY_SEO_KEYWORD_DESC = 41;
    
    
    /**
     * Returns the static model of the specified AR class.
     * @return AdminConfig the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public static function categoryLabels()
    {
        // @todo not complete
        return array(
            self::CATEGORY_CUSTOM => '自定义参数',
        
            self::CATEGORY_SYSTEM_SITE => '网站设置',
            self::CATEGORY_SYSTEM_CACHE => '缓存设置',
            self::CATEGORY_SYSTEM_ATTACHMENTS => '附件设置',
            self::CATEGORY_SYSTEM_EMAIL => '邮件设置',
        
            self::CATEGORY_DISPLAY_TEMPLATE => '模板配置',
            self::CATEGORY_DISPLAY_UI => '界面元素',
        
            self::CATEGORY_SNS_INTERFACE => 'SNS接口',
            self::CATEGORY_SNS_STATS => 'SNS统计',
            self::CATEGORY_SNS_TEMPLATE => 'SNS模板',
                
            self::CATEGORY_SEO_KEYWORD_DESC => '关键字与描述',
        );
    }
    
    public static function flushAllConfig()
    {
        $rows = app()->getDb()->createCommand()
            ->select(array('config_name', 'config_value'))
            ->from(TABLE_CONFIG)
            ->queryAll();
        
        if (empty($rows)) return false;
        
        $rows = CHtml::listData($rows, 'config_name', 'config_value');
        $data = "<?php\nreturn " . var_export($rows, true) . ';';
        $filename = dp('setting.config.php');
        return file_put_contents($filename, $data);
    }

    protected function beforeDelete()
    {
        throw new CException(t('system_config_is_not_allowed_deleted'));
    }
}