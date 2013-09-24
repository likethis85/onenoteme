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
        
        $conditions = array('and', 'channel_id = :channelID', 'media_type = :mediatype', 'state = :disable_state');
        
        // 文字
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_FUNNY, ':mediatype'=>MEDIA_TYPE_TEXT);
        $duanziIDs = $cmd->where($conditions, $params)->queryColumn();
        
        // 图片
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_FUNNY, ':mediatype'=>MEDIA_TYPE_IMAGE);
        $lengtuIDs = $cmd->where($conditions, $params)->queryColumn();
        
        // 挖热点
        $conditions = array('and', 'channel_id = :channelID', 'state = :disable_state');
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_FOCUS);
        $focusIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $ids = array_merge($duanziIDs, $lengtuIDs, $focusIDs);
        $ids = array_unique($ids);
        
        $nums = 0;
        foreach ($ids as $index => $id) {
            $num = app()->getDb()->createCommand()
                ->update(TABLE_POST,
                    array('state'=>POST_STATE_ENABLED, 'create_time'=>time() - $index*60),
                    'id = :pid',
                    array(':pid' => $id)
                );
            
            if ($num > 0) $nums++;
        }
        printf("update %d rows\n", $nums);
    }
    
    public function actionUpdateVideoStateEnable($count)
    {
        $cmd = app()->getDb()->createCommand()
            ->select('id')
            ->from(TABLE_POST)
            ->order('create_time desc, id desc')
            ->limit($count);
        
        // 视频
        $conditions = array('and', 'channel_id = :channelID', 'media_type = :mediatype', 'state = :disable_state');
        $params = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_FUNNY, ':mediatype'=>MEDIA_TYPE_VIDEO);
        $videoIDs = $cmd->where($conditions, $params)->queryColumn();
        
        $nums = 0;
        foreach ($videoIDs as $index => $id) {
            $num = app()->getDb()->createCommand()
                ->update(TABLE_POST,
                    array('state'=>POST_STATE_ENABLED, 'create_time'=>time() - $index*60),
                    'id = :pid',
                    array(':pid' => $id)
                );
            
            if ($num > 0) $nums++;
        }
        printf("update %d rows\n", $nums);
    }
}
