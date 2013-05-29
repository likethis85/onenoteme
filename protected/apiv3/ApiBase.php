<?php

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

interface ICDApiBase
{
    public function fieldAttributeMap();
    public function fieldColumnMap();
}

/**
 * Api 基础类
 * @author Chris
 * @copyright cdcchen@gmail.com
 * @package api
 */

class ApiBase implements ICDApiBase
{
    protected $_apiparams;
    protected $_params;
    
    public function __construct($params)
    {
        $this->_apiparams['method'] = $params['method'];
        $this->_apiparams['sig'] = $params['sig'];
        $this->_apiparams['apikey'] = $params['apikey'];
        $this->_apiparams['format'] = $params['format'];
        $this->_apiparams['timestamp'] = $params['timestamp'];
        
        unset($params['method'], $params['sig'], $params['apikey'], $params['format'], $params['timestamp']);
        $this->_params = $params;
        
        $this->requiredParams(self::defaultRequiredAppParams());
        
        $this->init();
    }
    
    public function init()
    {
    }
    
    protected static function requirePost()
    {
        self::requireMethods('POST');
    }
    
    protected static function requireGet()
    {
        self::requireMethods('GET');
    }
    
    protected static function requireMethods($methods)
    {
        $methods = array($methods);

        $methods = array_map('strtoupper', $methods);
        if (!isset($_SERVER['REQUEST_METHOD']) || !in_array($_SERVER['REQUEST_METHOD'], $methods, true)) {
            $methodString = join('|', $methods);
            throw new CDApiException(ApiError::HTTP_METHOD_ERROR, "此方法必须使用{$methodString}请求");
        }
    }
    
    protected function requiredParams($params)
    {
        $params = (array)$params;
        
        $allParams = array_keys($this->_params);
        $diff = join(',', array_diff($params, $allParams));
        if ($diff) {
            throw new CDApiException(ApiError::PARAM_NOT_COMPLETE, "缺少参数：{$diff}");
        }
    }
    
    protected function filterParams(array $params = array())
    {
        $params = array_merge($params, self::defaultRequiredAppParams());
        $params = array_unique($params);
        
        $params[] = 'debug';
        foreach ($params as $key) {
            if (array_key_exists($key, $this->_params))
                $data[$key] = $this->_params[$key];
        }

        return (array)$data;
    }
    
    protected function requireLogin()
    {
    	if (!isset($this->_params['token']) || empty($this->_params['token']))
    		throw new CDApiException(ApiError::USER_TOKEN_ERROR, 'user token 无效');
    }

    protected static function joinModelErrors(CActiveRecord $model)
    {
        foreach ($model->getErrors() as $column => $errors) {
            $errors = array_filter($errors);
            $content .= "$column: " . join(';', $errors);
        }
        
        return $content;
    }

    protected function saveDeviceConnectHistory()
    {
        $history = new DeviceConnectHistory();
        $history->device_udid = $this->_params['device_udid'];
        $history->sys_version = $this->_params['sys_version'];
        $history->app_version = $this->_params['app_version'];
        $history->apikey = $this->_apiparams['apikey'];
        $history->method = $this->_apiparams['method'];
        $history->format = $this->_apiparams['format'];
        
        return $history->save() ? $history : false;
    }
    
    protected static function defaultRequiredAppParams()
    {
        return array('device_udid', 'sys_version', 'app_version');
    }

    protected static function dump($var, $exit = false)
    {
        var_dump($var);
        $exit && exit();
    }
    
    public function fieldAttributeMap()
    {
        throw new CDException('fieldAttributeMap method is required override.');
    }
    
    public function fieldColumnMap()
    {
        throw new CDException('fieldColumnMap method is required override.');
    }

    public function fields()
    {
        return array_keys($this->fieldColumnMap());
    }
    
    public function selectColumns()
    {
        return array_unique(array_values($this->fieldColumnMap()));
    }

    protected static function execRelation($model, $attribute)
    {
        if (is_array($attribute)) {
            return $attribute[2]
            ? $model->$attribute[0]->$attribute[1]()
            : self::execRelation($model->$attribute[0], $attribute[1]);
        }
        else
            return $model->$attribute;
    }
}



