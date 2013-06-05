<?php
class DefaultController extends RestController
{
    public function actionError()
    {
        $error = app()->errorHandler->error;
        if ($error) {
            $error = array(
                'errcode' => $error['code'],
                'message' => $error['message'],
            );
            
            if (REST_DEBUG) {
                $error['type'] = $error['type'];
                $error['trace'] = $error['trace'];
            }
            
            $data['error'] = $error;
            
            echo CJSON::encode($data);
        	exit(0);
        }
    }
}