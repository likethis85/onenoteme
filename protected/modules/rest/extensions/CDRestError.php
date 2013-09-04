<?php
class CDRestError
{
	/**
	 * 系统错误
	 * @var integer
	 */
	const SYSTEM_ERROR = 10001;
	
    /**
     * api 路径不存在
     * @var integer
     */
    const API_PATH_NO_EXIST = 11001;
    
    /**
     * http 请求方式错误
     * @var integer
     */
    const HTTP_METHOD_ERROR = 11002;
    
    /**
     * class 文件不存在
     * @var integer
     */
    const CLASS_FILE_NOT_EXIST = 11003;
    
    /**
     * class->method 不存在
     * @var integer
     */
    const CLASS_METHOD_NOT_EXIST = 11004;
    
    /**
     * class->method 执行错误
     * @var integer
     */
    const CLASS_METHOD_EXECUTE_ERROR = 11005;

    /**
     * 用户提交参数不完整
     * @var integer
     */
    const PARAM_NOT_COMPLETE = 11006;

    /**
     * apikey 不合法
     * @var integer
     */
    const APIKEY_INVALID = 11007;

    /*
     * format 不合法
     */
    const FORMAT_INVALID = 11008;
    
    /**
     * method 参数格式错误
     * @var integer
     */
    const METHOD_FORMAT_ERROR = 11009;

    /**
     * 签名错误
     * @var integer
     */
    const SIGNATURE_ERROR = 11010;
    
    
    // 以下为用户相关错误代码，20开头
    /**
     * 用户相关
     * @var integer
     */
    const USER_NOT_EXIST = 20001;
    const USER_NOT_AUTHENTICATED = 20002;
    const USER_LOGIN_ERROR = 20003;
    const USER_CREATE_ERROR = 20004;
    const USER_NAME_INVALID = 20010;
    const USER_NAME_EXIST = 20011;
    const USER_PASSWORD_INVALID = 20013;
    const USER_NICKNAME_EXIST = 20021;
    
    /**
     * 用户$token错误
     * @var integer
     */
    const USER_TOKEN_ERROR = 20002;
    
    // 以下为段子相关错误代码，以21开头
    const POST_SAVE_ERROR = 21000;
    
    // 以下为评论相关错误代码，以22开头
    const COMMENT_SAVE_ERROR = 22000;
    
    // 以下为设备相关蓑代码，以23开头
    
    
    // 以下为系统配置相关错误代码，以24开头
    const DEVICE_SAVE_ERROR = 24001;
    const DEVICE_NOT_EXIST = 24002;
    
    // 以下为用户配置相关错误代码，以25开头
    
    
    public static function codeMessages()
    {
        return array(
            self::SYSTEM_ERROR => '系统错误',
            self::API_PATH_NO_EXIST => 'API脚本路径不存在',
            self::PARAM_NOT_COMPLETE => '请求参数不完整',
            self::HTTP_METHOD_ERROR => '请求方法不正确',
            self::API_PATH_NO_EXIST => 'API脚本设置错误',
            self::CLASS_METHOD_EXECUTE_ERROR => '接口处理方法执行错误',
            self::PARAM_NOT_COMPLETE => '缺少必须的参数',
            self::APIKEY_INVALID => 'apikey 无效',
            self::FORMAT_INVALID => '数据输出格式错误',
            self::METHOD_FORMAT_ERROR => 'method参数格式不正确，格式: Api_Method',
            self::CLASS_FILE_NOT_EXIST => '接口对应类库未定义',
            self::CLASS_METHOD_NOT_EXIST => '接口对应的类方法未定义',
            self::CLASS_FILE_NOT_EXIST => '接口类定义文件不存在',
            self::SIGNATURE_ERROR => '签名错误',
                
            // device
            self::DEVICE_NOT_EXIST => '设备信息不存在',
            self::DEVICE_SAVE_ERROR => '设备信息保存出错',
                
            // user
            self::USER_TOKEN_ERROR => '用户验证出错',
            self::USER_NOT_AUTHENTICATED => '密码错误',
            self::USER_LOGIN_ERROR => '登录过程出错',
            self::USER_NOT_EXIST => '用户不存在',
            self::USER_CREATE_ERROR => '用户注册出错',
            self::USER_NICKNAME_EXIST => '昵称已经被人抢去啦',
            
            // post
            self::POST_SAVE_ERROR => '段子保存出错',
                
            // comment
            self::COMMENT_SAVE_ERROR => '评论保存出错',
        );
    }
    
    public static function messageByCode($code)
    {
        $message = '';
        $messages = self::codeMessages();
        if (array_key_exists($code, $messages))
            $message = $messages[$code];
        
        return $message;
    }
}


