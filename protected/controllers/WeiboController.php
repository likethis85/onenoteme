<?php
class WeiboController extends Controller
{
    const APP_KEY = '2981913360';
    const APP_SECRETE = 'f06fd0b530f3d9daa56db67e5e8610e1';
    
    public function actionAuthorize()
    {
        $callback = aurl('weibo/callback');
        $url = sprintf('https://api.weibo.com/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s', self::APP_KEY, $callback);
        $this->redirect($url);
        exit(0);
    }
    
    public function actionCallback($code)
    {
        $code = strip_tags(trim($code));
        $url = sprintf('https://api.weibo.com/oauth2/access_token?client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s&code=%s', self::APP_KEY, self::APP_SECRETE, $redirectUrl, $code);
        $curl = new CdCurl();
        $curl->post($url);
        if ($curl->errno() != 0)
            throw new CHttpException(503, '获取token出错');
        else
            print_r($curl->rawdata());
    }
    
    public function actionTest()
    {
        print_r($_REQUEST);
    }
}