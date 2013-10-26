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
        
        $jokeConditions = array('and', 'channel_id = :channelID', 'media_type = :mediatype', 'state = :disable_state');
        
        // 文字
        $textParams = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_FUNNY, ':mediatype'=>MEDIA_TYPE_TEXT);
        $duanziIDs = $cmd->where($jokeConditions, $textParams)->queryColumn();
        var_dump($cmd->text);
        // 图片
        $lengtuParams = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_FUNNY, ':mediatype'=>MEDIA_TYPE_IMAGE);
        $lengtuIDs = $cmd->where($jokeConditions, $lengtuParams)->queryColumn();
        var_dump($cmd->text);
        // 挖热点
        $focusConditions = array('and', 'channel_id = :channelID', 'state = :disable_state');
        $foucsParams = array(':disable_state'=>POST_STATE_DISABLED, ':channelID'=>CHANNEL_FOCUS);
        $focusIDs = $cmd->where($focusConditions, $lengtuParams)->queryColumn();
        var_dump($cmd->text);
        $ids = array_merge($duanziIDs, $lengtuIDs, $focusIDs);
        var_dump($ids);
        $ids = array_unique($ids);
        var_dump($ids);
        $nums = 0;
        foreach ($ids as $index => $id) {
            $rowCount = app()->getDb()->createCommand()
                ->update(TABLE_POST,
                    array('state'=>POST_STATE_ENABLED, 'create_time'=>time() - $index*60),
                    'id = :pid',
                    array(':pid' => $id)
                );
            
            if ($rowCount > 0) $nums++;
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
