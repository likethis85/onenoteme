<?php
class ExportController extends AdminController
{
    public function actionJoke()
    {
        if (request()->getIsPostRequest()) {
            $date = request()->getPost('date');
            if (empty($date))
                $info = '请输入日期，格式：20130507';
            else {
                $year = substr($date, 0, 4);
                $month = substr($date, 4, 2);
                $day = substr($date, 6, 2);
                $start = mktime(0, 0, 0, $month, $day, $year);
                $end = $start + 3600 * 24;
                $criteria = new CDbCriteria();
                $criteria->select = array('title', 'content', 'create_time', 'state', 'channel_id');
                $criteria->order = 'create_time asc';
                $criteria->addColumnCondition(array('state'=>POST_STATE_ENABLED, 'channel_id'=>CHANNEL_DUANZI));
                $criteria->addBetweenCondition('create_time', $start, $end);
                $models = Post::model()->findAll($criteria);
                
                if (count($models) > 0) {
                    $content = "\n挖段子网 - http://www.waduanzi.com\n\n\n";
                    foreach ($models as $index => $model) {
                        $content .= ($index + 1) . ' ' . $model->getFilterContent() . "\n\n";
                    }
                    $content .= "\n\n\n挖段子网 - http://www.waduanzi.com\n\n";
                    
                    $filename = sprintf('挖段子网冷笑话精选 - 第2%s期', $date);
                    request()->sendFile($filename, $content);
                }
                else {
                    $info = $date . ' 该日期没有更新内容';
                }
            }
        }
        else
        $date = date('Ymd');
        $this->render('joke', array(
            'info'=>$info,
            'date' => $date,
        ));
        
    }
}