<?php
class PostCommand extends CConsoleCommand
{
    public function actionUpdateStateEnable($count)
    {
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->order('create_time asc, id asc')
            ->limit($count);
        
        $conditions = array('and', 'channel_id = :channelID', 'state = :disable_state');
        
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_DUANZI);
        $duanziIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_LENGTU);
        $lengtuIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_GIRL);
        $fuliIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $ids = array_merge($duanziIDs, $lengtuIDs, $fuliIDs);
        
        $nums = 0;
        foreach ($ids as $id) {
            $num = app()->getDb()->createCommand()
                ->update('{{post}}',
                    array('state'=>POST_STATE_ENABLED, 'create_time'=>(int)$_SERVER['REQUEST_TIME'] + $nums*2),
                    'id = :pid',
                    array(':pid' => $id)
                );
            
            if ($num > 0) $nums++;
        }
        printf("update %d rows\n", $nums);
    }

    public function actionMakeThumbnail($count = 10)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $count;
        $criteria->order = 'id desc';
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_GIRL));
        $models = Post::model()->findAll($criteria);
        
        foreach ($models as $model) {
            echo $model->id . "\n";
            $originalPicPath = str_replace(fbu(), '', $model->original_pic);
            $originalFilename = realpath(fbp($originalPicPath));
            $thumbnailPicPath = str_replace(fbu(), '', $model->thumbnail_pic);
            $thumbnailFileName = realpath(fbp($thumbnailPicPath));
            
            echo $originalFilename . "\n";
            echo $thumbnailFileName . "\n";
//             exit();
            
            $data = file_get_contents('http://f.waduanzi.com/pics/2012/10/23/bmiddle_20121023162341_5086540d52037.jpeg');
            $im = new CDImage();
            $im->load($data);
            unset($data);
            
            if ($im->width()/$im->height() > $thumbWidth/$thumbHeight)
                $im->resizeToHeight($thumbHeight);
            else
                $im->resizeToWidth($thumbWidth);
            $im->crop($thumbWidth, $thumbHeight, $cropFromTop, $cropFromLeft)
                ->saveAsJpeg($thumbnailFileName);
            $model->thumbnail_width = $im->width();
            $model->thumbnail_height = $im->height();
            $result = $model->save(true, array('thumbnail_width', 'thumbnail_height'));
            var_dump($result);
        }
    }
}