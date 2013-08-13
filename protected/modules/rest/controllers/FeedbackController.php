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
        if (empty($content)) {
            $data = array('success' => 0);
        }
        else {
            $feedback = new RestFeedback();
            $feedback->content = $content;
            $feedback->device_udid = $this->deviceUDID;
            $feedback->device_model = request()->getPost('device_model', '');
            $feedback->network_status = request()->getPost('network_status', '');
            $result = $feedback->save();
            $data = array(
                'success' => (int)$result,
                'content' => $content,
            );
        }
        $this->output($data);
    }
}