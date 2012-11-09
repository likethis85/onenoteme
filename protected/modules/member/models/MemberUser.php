<?php
/**
 *
 * @author chendong
 *
 * @property array $getFavoritePosts
 * @property MemberUserProfile $profile
 * @property array $comments
 * @property integer $commentCount
 * @property integer $favoritePostsCount
 */
class MemberUser extends User
{
    /**
     * Returns the static model of the specified AR class.
     * @return MemberUser the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'profile' => array(self::HAS_ONE, 'MemberUserProfile', 'user_id'),
            'comments' => array(self::HAS_MANY, 'MemberComment', 'user_id'),
            'commentCount' => array(self::STAT, 'Comment', 'user_id'),
            'posts' => array(self::HAS_MANY, 'MemberPost', 'user_id'),
            'postCount' => array(self::STAT, 'Post', 'user_id'),
            'favoritePostsCount' => array(self::STAT, 'Post', '{{post_favorite}}(user_id, post_id)',
                'condition' => 'state = ' . POST_STATE_ENABLED,
            ),
        ));
    }
    
    public function getFavoritePosts($page = 1, $count = 15)
    {
        return MemberPost::fetchFavoritePosts($this->id, $page, $count);
    }
    
}