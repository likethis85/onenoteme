<?php
class MemberUserProfile extends UserProfile
{
    /**
     * Returns the static model of the specified AR class.
     * @return MemberUserProfile the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function uploadAvatar()
    {
        $upload = $this->original_avatar;
        $tempName = $upload->getTempName();
        if ($upload === null || $upload->error != UPLOAD_ERR_OK || !file_exists($tempName) || !is_readable($tempName))
            return false;
        
        try {
            $avatar = CDUploadedFile::saveImage(upyunEnabled(), $tempName, 'avatars', '', array());
            $this->original_avatar = $avatar['url'];
            return true;
        }
        catch (Exception $e) {
        }
        
        return false;
    }
}


