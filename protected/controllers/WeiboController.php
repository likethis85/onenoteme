<?php
class WeiboController extends Controller
{
    public function actionAuthorize()
    {
        $appKey = '2981913360';
        $callback = aurl('weibo/callback');
        $url = sprintf('https://api.weibo.com/oauth2/authorize?client_id=%s&response_type=code&redirect_uri=%s', $appKey, $callback);
        $this->redirect($url);
        exit(0);
    }
    
    public function actionCallback($code)
    {
        $code = strip_tags(trim($code));
        print_r($_REQUEST);
    }
}