<?php
class DefaultController extends RestController
{
    public function actionError()
    {
        $error = app()->errorHandler->error;
        if ($error) {
            $data = array(
                'errcode' => $error['code'],
                'message' => $error['message'],
                'type' => $error['type'],
            );
            
            if (REST_DEBUG) {
                $data['trace'] = $error['trace'];
            }
            
            echo CJSON::encode($data);
        	exit(0);
        }
    }
}