<?php

defined('DS') || define('DS', DIRECTORY_SEPARATOR);

require dirname(__FILE__) . DS . 'define.php';

class AppApi
{
    const FORMAT_XML = 'xml';
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';
    
    private static $_apiPath;
    private static $_format = 'json';
    private static $_formats = array(
        self::FORMAT_JSON,
        self::FORMAT_JSONP,
        self::FORMAT_XML,
    );
    
    private $_apikey;
    private $_secretKey;
    private $_method;
    private $_sig;
    private $_params;
    
    private $_class;
    private $_function;
    
    private $_debug = false;
    
    
    /**
     * 构造函数
     */
    public function __construct($apiPath = '')
    {
        $this->init();
    	
        if (empty($apiPath))
            $apiPath = dirname(__FILE__);
        $this->setApiPath($apiPath);
    }
    
    private function init()
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'get')
            $this->_params = $_GET;
        elseif (strtolower($_SERVER['REQUEST_METHOD']) === 'post')
            $this->_params = $_POST;
        
//        sleep(3);
        set_error_handler(array($this, 'errorHandler'), E_ERROR);
    	set_exception_handler(array($this, 'exceptionHandler'));
    }
    
    /**
     * 设置api类所在的路径
     * @param string $path
     * @throws CDApiException
     * @return AppApi
     */
    public function setApiPath($path)
    {
        $path = rtrim($path, '\/') . DIRECTORY_SEPARATOR;
        if (file_exists($path)) {
            self::$_apiPath = $path;
            set_include_path(get_include_path() . PATH_SEPARATOR . self::$_apiPath);
        }
        else
            throw new CDApiException(ApiError::API_PATH_NO_EXIST, "{$path}目录不存在");
            
        return $this;
    }
    
    /**
     * 运行AppApi
     */
    public function run()
    {
        $this->checkRequiredParams();
        $this->parseParams($this->_params);
        
        $this->checkParams()->execute();
        exit(0);
    }
    
    /**
     * 检查参数
     * @return AppApi
     */
    private function checkParams()
    {
        $this->checkFormat()
            ->checkApiKey();
        // @todo 暂时不启动签名检查
//         $this->checkSignature();
            
        return $this;
    }
    
    /**
     * 执行method对应的命令
     * @throws CDApiException
     */
    private function execute()
    {
        $result = call_user_func($this->parsekMethods());
        if (false === $result)
            throw new CDApiException(ApiError::CLASS_METHOD_EXECUTE_ERROR, '$class->$method 执行错误');
        else {
            $data = array('error'=>0, 'data'=>$result);
            self::output($data, self::$_format);
        }
    }
    
    /**
     * 分析用户提交的参数
     * @param array $params
     * @return AppApi
     */
    private function parseParams($params)
    {
        foreach ($params as $key => $value)
            $params[$key] = strip_tags(trim($value));
            
        $this->_apikey = $params['apikey'];
        $this->_method = $params['method'];
        $this->_sig = $params['sig'];
        
        return $this;
    }
    
    /**
     * 检查必需的参数
     * @throws CDApiException
     * @return AppApi
     */
    private function checkRequiredParams()
    {
        $params = array('apikey', 'sig', 'method', 'timestamp');
        $keys = array_keys($this->_params);
        if (array_diff($params, $keys)) {
            throw new CDApiException(ApiError::PARAM_NOT_COMPLETE);
        }
        return $this;
    }

    /**
     * 检查apikey
     * @throws CDApiException
     * @return AppApi
     */
    private function checkApiKey()
    {
        $keys = (array)require(dirname(__FILE__) . DS . 'keys.php');
        if (array_key_exists($this->_apikey, $keys)) {
            $this->_secretKey = $keys[$this->_apikey];
        }
        else
            throw new CDApiException(ApiError::APIKEY_INVALID, "apikey: {$this->_apikey}无效");
        return $this;
    }
    
    /**
     * 检查format参数
     * @throws CDApiException
     * @return AppApi
     */
    private function checkFormat()
    {
        $format = strtolower(trim(self::$_format));
        if (!in_array($format, self::$_formats)) {
            throw new CDApiException(ApiError::FORMAT_INVALID, '无效的数据输出格式: ' . $format);
        }
        else
            $this->_params['format'] = $format;
        
        return $this;
    }
    
    /**
     * 解析method参数
     * @throws CDApiException
     * @return array 0=>object, 1=>method
     */
    private function parsekMethods()
    {
        list($class, $method) = explode('.', $this->_method);
        if (empty($class) || empty($method)) {
            throw new CDApiException(ApiError::METHOD_FORMAT_ERROR);
        }
        
        $class = 'CDApi_' . ucfirst($class);
        if (!class_exists($class, false))
            self::importClass($class);

        if (!class_exists($class, false))
            throw new CDApiException(ApiError::CLASS_FILE_NOT_EXIST, "$class 类定义不存在");
            
        $object = new $class($this->_params);
        if (!method_exists($object, $method))
            throw new CDApiException(ApiError::CLASS_METHOD_NOT_EXIST, "{$class}->{$method}方法不存在");
        
        return array($object, $method);
    }
    
    /**
     * 导入api类
     * @param string $class
     * @throws CDApiException
     */
    private static function importClass($class)
    {
        $filename = self::$_apiPath . ucfirst($class) . '.php';
        if (file_exists($filename))
            require($filename);
        else
            throw new CDApiException(ApiError::CLASS_FILE_NOT_EXIST, "{$class} 类定义文件 $filename 不存在");
    }
    
    /**
     * 验证用户提交签名是否正确
     * @throws CDApiException
     * @return AppApi
     */
    private function checkSignature()
    {
        $sig1 = $this->_sig;
        $sig2 = $this->makeSignature();
        if ($sig1 != $sig2) {
            throw new CDApiException(ApiError::SIGNATURE_ERROR, "签名: {$sig1}");
        }
        return $this;
    }
    
    /**
     * 计算签名
     * @return string 签名
     */
    private function makeSignature()
    {
        $sig = '123';
        return $sig;
    }
    
    private static function output($data, $format = 'json')
    {
        $method = 'output' . ucfirst(strtolower($format));
        echo self::$method($data);
    }
    
    /**
     * 返回json编码数据
     * @param mixed $data
     * @return string json编码后的数据
     */
    private static function outputJson($data)
    {
        return CJSON::encode($data);
    }
    
    /**
     * 返回xml格式数据
     * @param mixed $data
     * @return string xml数据
     */
    private static function outputXml($data)
    {
        return 'xml';
    }
    
    private static function outputJsonp($data)
    {
        return $this->_params['callback'] . '(' . CJSON::encode($data) . ')';
    }
    
    public static function setDataFormat($format)
    {
        $format = strtolower(trim($format));
        if (in_array($format, self::$_formats)) {
            self::$_format = $format;
            return true;
        }
        else
            throw new CDApiException(ApiError::FORMAT_INVALID, '无效的数据输出格式: ' . $format);
    }
    
    public function debug($debug = true)
    {
        $this->_debug = (bool)$debug;
        return $this;
    }
    
    public function errorHandler($errno, $message, $file, $line)
    {
        $data = array(
            'error' => 1,
            'errno'=>$errno,
            'message'=>$message,
            'line'=>$line,
            'file'=>$file,
        );
    	echo self::outputJson($data);
    	exit(0);
    }
    
    public function exceptionHandler(Exception $e)
    {
        if ($e instanceof CDApiException) {
            $data = array(
                'error' => 1,
                'errno'=>$e->getCode(),
                'message'=>$e->getMessage(),
            );
            if ($this->_debug) {
                $data['file'] = $e->getFile();
                $data['line'] = $e->getLine();
            }
            
            echo self::outputJson($data);
        	exit(0);
        }
        else {
            $text[] = 'Message: ' . $e->getMessage();
            $text[] = 'Code: ' . $e->getCode();
            $text[] = 'Line: ' . $e->getLine();
            $text[] = 'File: ' . $e->getFile();
            echo join("\n", $text);
        }
    }
}


