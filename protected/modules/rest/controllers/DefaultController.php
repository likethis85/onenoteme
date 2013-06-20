<?php
class DefaultController extends RestController
{
    public function actionError()
    {
        $error = app()->errorHandler->error;
        if ($error) {
            $data = array(
                'code' => $error['code'],
                'errcode' => $error['errorCode'],
                'message' => $error['message'],
            );
            
            if (REST_DEBUG) {
                $data['type'] = $error['type'];
                $data['trace'] = $error['trace'];
            }
            
            echo CJSON::encode($data);
        	exit(0);
        }
    }
}