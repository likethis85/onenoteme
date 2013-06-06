<?php
class CDRestDataFormat
{
    public static function formatPost(ApiPost $model, $includeUser = true, $includeComment = false)
    {
        $data = array(
            'post_id' => $model->id,
            'channel_id' => $model->channel_id,
            'title' => $model->title,
            'content' => $model->getApiContent(),
            'create_time' => $model->create_time,
            'create_time_at' => $model->create_time,
            'up_count' => $model->up_score,
            'down_count' => $model->down_score,
            'comment_count' => $model->comment_nums,
            'favorite_count' => $model->favorite_count,
            'author_id' => $model->user_id,
            'author_name' => $model->getAuthorName(),
            'tags' => $model->tags,
            'small_pic' => $model->getSquareThumb(),
            'middle_pic' => $model->getMiddleImage(),
            'larget_pic' => $model->getLargeImage(),
            'user' => self::formatUser($model->user),
        );
        
        if ($includeComment)
            $data['comments'] = self::formatComments($model->comments);
        
        return $data;
    }
   
    public static function formatUser($model)
    {
        if (empty($model))
            $row = array(
                'user_id' => 0,
                'username' => 'Guest',
                'screen_name' => 'Guest',
                'create_time' => 0,
                'create_time_at' => '',
                'token' => '',
                'token_time' => 0,
                'website' => '',
                'desc' => '',
                'mini_avatar' => sbu(param('default_mini_avatar')),
                'small_avatar' => sbu(param('default_small_avatar')),
                'large_avatar' => sbu(param('default_large_avatar')),
            );
        else
            $row = array(
                'user_id' => $model->id,
                'username' => $model->username,
                'screen_name' => $model->getDisplayName(),
                'create_time' => $model->create_time,
                'create_time_at' => $model->getCreateTime(),
                'token' => $model->token,
                'token_time' => $model->token_time,
                'website' => $model->profile->website,
                'desc' => $model->profile->description,
                'mini_avatar' => $model->profile->getMiniAvatarUrl(),
                'small_avatar' => $model->profile->getSmallAvatarUrl(),
                'large_avatar' => $model->profile->getLargeAvatarUrl(),
            );
        
        return $row;
    }
    
    public static function formatComment(ApiComment $model, $includeUser = true)
    {
        $data = array(
            'comment_id' => $model->id,
            'post_id' => $model->post_id,
            'content' => $model->getApiContent(),
            'create_time' => $model->create_time,
            'create_time_at' => $model->getCreateTime(),
            'up_count' => $model->up_score,
            'down_count' => $model->down_score,
            'report_count' => $model->report_count,
            'author_id' => $model->user_id,
            'author_name' => $model->user_name,
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




