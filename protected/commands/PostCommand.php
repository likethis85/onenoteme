<?php
class PostCommand extends CConsoleCommand
{
    public function actionUpdateStateEnable($count)
    {
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->order('create_time desc, id desc')
            ->limit($count);
        
        $conditions = array('and', 'channel_id = :channelID', 'state = :disable_state');
        
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_DUANZI);
        $duanziIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_LENGTU);
        $lengtuIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_GIRL);
        $fuliIDs = $cmd->where($conditions, $params)->queryColumn();

        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_GHOSTSTORY);
        $ghostIDs = $cmd->where($conditions, $params)->queryColumn();

        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_VIDEO);
        $videoIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $ids = array_merge($duanziIDs, $lengtuIDs, $fuliIDs, $ghostIDs, $videoIDs);
        
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

    public function actionMakeThumbnail($page = 1, $count = 500)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $count;
        $criteria->offset = ($page - 1) * $count;
        $criteria->order = 'id asc';
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_LENGTU));
        $models = Post::model()->findAll($criteria);
        
        foreach ($models as $model) {
            echo $model->id . "\n";
            $wdzUrl = stripos($model->original_pic, fbu()) === 0;
            if ($wdzUrl) {
                $originalPicPath = str_replace(fbu(), '', $model->original_pic);
                $originalFilename = realpath(fbp($originalPicPath));
                if ($model->thumbnail_pic) {
                    $thumbnailPicPath = str_replace(fbu(), '', $model->thumbnail_pic);
                    $realpath = fbp($thumbnailPicPath);
                    unlink($realpath);
                    if (stripos($thumbnailPicPath, 'thumbnail_') === false && stripos($thumbnailPicPath, 'thubmnail_') === false)
                        $thumbnailPicPath = substr_replace($thumbnailPicPath, 'thumbnail_', 16, 0);
                    
                    $extension = pathinfo($thumbnailPicPath, PATHINFO_EXTENSION);
                    if ($extension)
                        $thumbnailPicPath = substr($thumbnailPicPath, 0, stripos($thumbnailPicPath, '.'));
                    $thumbnailFileName = fbp($thumbnailPicPath);
                }
                else {
                    $newPaths = CDUploadFile::makeUploadFilePath('', 'pics');
                    $thumbnailPicPath = $newPaths['url'];
                    $thumbnailFileName = $newPaths['path'];
                }
                
            }
            else
                continue;
            
//             echo $originalFilename . "\n";
//             echo $thumbnailFileName . "\n------------\n";
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
            
            $model->original_width = $im->width();
            $model->original_height = $im->height();
            
            $thumbWidth = IMAGE_THUMBNAIL_WIDTH;
            $thumbHeight = IMAGE_THUMBNAIL_HEIGHT;
            if ($model->channel_id == CHANNEL_GIRL)
                $thumbWidth = $thumbHeight = IMAGE_THUMBNAIL_SQUARE_SIZE;
            
            if ($im->width()/$im->height() > $thumbWidth/$thumbHeight)
                $im->resizeToHeight($thumbHeight);
            else
                $im->resizeToWidth($thumbWidth);
            $im->crop($thumbWidth, $thumbHeight, $model->channel_id == CHANNEL_GIRL)
                ->saveAsJpeg($thumbnailFileName);
            $model->thumbnail_width = $im->width();
            $model->thumbnail_height = $im->height();
            $thumbUrl = dirname($thumbnailPicPath) . '/' . $im->filename();
            
            $model->thumbnail_pic = fbu(ltrim($thumbUrl, './'));
            $result = $model->save(true, array('thumbnail_width', 'thumbnail_height', 'thumbnail_pic', 'original_width', 'original_height'));
            var_dump($result);
        }
    }

    public function actionMakeBmiddle($page = 1, $count = 500)
    {
        $criteria = new CDbCriteria();
        $criteria->limit = $count;
        $criteria->offset = ($page - 1) * $count;
        $criteria->order = 'id asc';
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_GIRL));
        $criteria->addColumnCondition(array('channel_id'=>CHANNEL_LENGTU), 'and', 'or');
        $models = Post::model()->findAll($criteria);
        
        foreach ($models as $model) {
            echo $model->id . "\n";
            $wdzUrl = stripos($model->original_pic, fbu()) === 0;
            if ($wdzUrl) {
                $originalPicPath = str_replace(fbu(), '', $model->original_pic);
                $originalFilename = realpath(fbp($originalPicPath));
                if ($model->bmiddle_pic) {
                    $thumbnailPicPath = str_replace(fbu(), '', $model->bmiddle_pic);
                    $realpath = fbp($thumbnailPicPath);
                    unlink($realpath);
                    if (stripos($thumbnailPicPath, 'bmiddle_') === false)
                        $thumbnailPicPath = substr_replace($thumbnailPicPath, 'bmiddle_', 16, 0);
                    
                    $extension = pathinfo($thumbnailPicPath, PATHINFO_EXTENSION);
                    if ($extension)
                        $thumbnailPicPath = substr($thumbnailPicPath, 0, stripos($thumbnailPicPath, '.'));
                    $thumbnailFileName = fbp($thumbnailPicPath);
                }
                else {
                    $newPaths = CDUploadFile::makeUploadFilePath('', 'pics');
                    $thumbnailPicPath = $newPaths['url'];
                    $thumbnailFileName = $newPaths['path'];
                }
                
            }
            else
                continue;
            
//             echo $originalFilename . "\n";
//             echo $thumbnailFileName . "\n------------\n";
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
            
            if ($im->width() > IMAGE_MIDDLE_WIDTH)
                $im->resizeToWidth(IMAGE_MIDDLE_WIDTH);
            $im->saveAsJpeg($thumbnailFileName, 75);
            $model->bmiddle_width = $im->width();
            $model->bmiddle_height = $im->height();
            $thumbUrl = dirname($thumbnailPicPath) . '/' . $im->filename();
            $model->bmiddle_pic = fbu(ltrim($thumbUrl, './'));
            $result = $model->save(true, array('bmiddle_width', 'bmiddle_height', 'bmiddle_pic'));
            var_dump($result);
        }
    }
}
