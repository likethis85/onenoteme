<?php
class FeedbackController extends RestController
{
    public function filters()
    {
        return array(
            'postOnly + create',
        );
    }
    
    public function actionCreate()
    {
        $content = request()->getPost('content');
        $content = trim(strip_tags($content));
        $data = array(
            'success' => 1,
            'content' => $content,
            'device_id' => $this->deviceUDID,
        );
        $this->output($data);
    }
}