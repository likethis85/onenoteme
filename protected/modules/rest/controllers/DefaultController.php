<?php
class DefaultController extends RestController
{
    public function actionError()
    {
        $error = app()->errorHandler->error;
        if ($error) {
            $data = array(
                'errcode'=>$error['code'],
                'message'=>$error['message'],
            );
            
            if (REST_DEBUG) {
                $data['trace'] = $error['trace'];
            }
            
            echo CDRestBase::outputJson($data);
        	exit(0);
        }
    }
}