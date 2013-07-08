<?php
class CDRestDataFormat
{
    public static function formatPost(RestPost $model, $trimUser = true, $trimComment = false)
    {
        $data = array(
            'post_id' => $model->id,
            'channel_id' => $model->channel_id,
            'title' => $model->getApiTitle(),
            'content' => $model->getApiContent(),
            'create_time' => $model->create_time,
            'create_time_at' => $model->getApiCreateTime(),
            'up_count' => $model->up_score,
            'down_count' => $model->down_score,
            'comment_count' => $model->comment_nums,
            'favorite_count' => $model->favorite_count,
            'author_id' => $model->user_id,
            'author_name' => $model->getAuthorName(),
            'tags' => $model->tags,
            'small_pic' => $model->getSquareThumb(),
            'middle_pic' => $model->getMiddlePic(),
            'large_pic' => $model->getLargePic(),
            'pic_frames' => $model->original_frames,
            'url' => $model->getUrl(),
        );
        
        if ($trimUser)
            $data['user'] = self::formatUser($model->user);
        
        if ($trimComment)
            $data['comments'] = self::formatComments($model->comments);
        
        return $data;
    }
   
    public static function formatUser($model, $token = '')
    {
        if (empty($model))
            $row = array(
                'user_id' => 0,
                'username' => 'Guest',
                'screen_name' => 'Guest',
                'create_time' => 0,
                'create_time_at' => '',
                'website' => '',
                'desc' => '',
                'mini_avatar' => sbu(param('default_mini_avatar')),
                'small_avatar' => sbu(param('default_small_avatar')),
                'large_avatar' => sbu(param('default_large_avatar')),
                'score' => 0,
            );
        else
            $row = array(
                'user_id' => $model->id,
                'username' => $model->username,
                'screen_name' => $model->getDisplayName(),
                'create_time' => $model->create_time,
                'create_time_at' => $model->getCreateTime(),
                'website' => $model->profile->website,
                'desc' => $model->profile->description,
                'mini_avatar' => $model->profile->getMiniAvatarUrl(),
                'small_avatar' => $model->profile->getSmallAvatarUrl(),
                'large_avatar' => $model->profile->getLargeAvatarUrl(),
                'score' => $model->score,
            );
        
        $row['token'] = $token;
        return $row;
    }
    
    public static function formatComment(RestComment $model, $includeUser = true)
    {
        $data = array(
            'comment_id' => $model->id,
            'post_id' => $model->post_id,
            'content' => $model->getApiContent(),
            'create_time' => $model->create_time,
            'create_time_at' => $model->getApiCreateTime(),
            'up_count' => $model->up_score,
            'down_count' => $model->down_score,
            'report_count' => $model->report_count,
            'author_id' => $model->user_id,
            'author_name' => $model->getAuthorName(),
            'recommend' => $model->recommend,
        );
        
        if ($includeUser)
            $data['user'] = self::formatUser($model->user);
        
        return $data;
    }
    
    public static function formatComments(array $models)
    {
        $data = array();
        
        foreach ((array)$models as $model)
            $data[] = self::formatComment($model);
        
        return $data;
    }
}




