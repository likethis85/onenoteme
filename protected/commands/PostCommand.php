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

    public function actionMakeThumbnail($page = 1, $count = 10)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $count;
        $criteria->offset = ($page - 1) * $count;
        $criteria->order = 'id asc';
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_GIRL));
        $models = Post::model()->findAll($criteria);
        
        foreach ($models as $model) {
            echo $model->id . "\n";
            $wdzUrl = stripos($model->original_pic, sbu()) == 0;
            if ($wdzUrl) {
                $originalPicPath = str_replace(fbu(), '', $model->original_pic);
                $originalFilename = realpath(fbp($originalPicPath));
                $thumbnailPicPath = str_replace(fbu(), '', $model->thumbnail_pic);
                if (stripos($thumbnailPicPath, 'thumbnail_') === false)
                    $thumbnailPicPath = substr_replace($thumbnailPicPath, 'thumbnail_', 16, 0);
                
                $extension = pathinfo($thumbnailPicPath, PATHINFO_EXTENSION);
                if ($extension)
                    $thumbnailPicPath = substr($thumbnailPicPath, 0, stripos($thumbnailPicPath, '.'));
                
                $thumbnailFileName = fbp($thumbnailPicPath);
            }
            
            echo $originalFilename . "\n";
            echo $thumbnailFileName . "\n------------\n";
//             continue;
//             exit();
            
            $data = file_get_contents($originalFilename);
            if ($data === false) {
                echo '$originalFilename read error.';
                continue;
            }
            
            $im = new CDImage();
            $im->load($data);
            unset($data);
            
            $thumbWidth = IMAGE_THUMBNAIL_WIDTH;
            $thumbHeight = IMAGE_THUMBNAIL_HEIGHT;
            if ($model->channel_id == CHANNEL_GIRL) {
                $thumbWidth = GIRL_THUMBNAIL_WIDTH;
                $thumbHeight = GIRL_THUMBNAIL_HEIGHT;
            }
            
            if ($im->width()/$im->height() > $thumbWidth/$thumbHeight)
                $im->resizeToHeight($thumbHeight);
            else
                $im->resizeToWidth($thumbWidth);
            $im->crop($thumbWidth, $thumbHeight, $model->channel_id == CHANNEL_GIRL)
                ->saveAsJpeg($thumbnailFileName);
            $model->thumbnail_width = $im->width();
            $model->thumbnail_height = $im->height();
            $model->thumbnail_pic = dirname($thumbnailPicPath) . '/' . $im->filename();
            $result = $model->save(true, array('thumbnail_width', 'thumbnail_height', 'thumbnail_pic'));
            var_dump($result);
        }
    }
}