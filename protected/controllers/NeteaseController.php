<?php
class NeteaseController extends Controller
{
    
    public function actionLogin()
    {
        $callback = aurl('netease/callback');
        $url = sprintf('https://api.t.163.com/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s', NETEASE_APP_KEY, $callback);
        $this->redirect($url);
        exit(0);
    }
    
    
    public function actionCallback($code)
    {
        $code = strip_tags(trim($code));
        $callback = aurl('netease/callback');
        $url = sprintf('https://api.t.163.com/oauth2/access_token?client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s&code=%s', NETEASE_APP_KEY, NETEASE_APP_SECRET, $callback, $code);
        $curl = new CdCurl();
        $curl->ssl()->post($url);
        if ($curl->errno() != 0)
            throw new CException(503, '获取access_token出错');
        else {
            $data = json_decode($curl->rawdata(), true);
            if (empty($data))
                throw new CException('获取access_token错误');
    
            $expires_in = 3600*24; //$data['expires_in'];
            $cacheTokenKey = 'netease_mm_access_tokens';
            $tokensStr = app()->cache->set($cacheTokenKey);
            if ($tokensStr !== false)
                $tokens = unserialize($tokensStr);
            else
                $tokens = array();
            $tokens[] = $data['access_token'];
            $tokens = array_unique($tokens);
            $tokensText = serialize($tokens);
            $result = app()->cache->set($cacheTokenKey, $tokensText, $expires_in);
            echo $result ? '授权登录成功' : '授权登录失败';
        }
    }
    
    public function actionTokens()
    {
        $cacheTokenKey = 'netease_mm_access_tokens';
        $tokensStr = app()->cache->get($cacheTokenKey);
        $tokens = unserialize($tokensStr);
        echo '<pre>';
        print_r($tokens);
    }
}

