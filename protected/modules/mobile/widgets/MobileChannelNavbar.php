<?php
class MobileChannelNavbar extends CWidget
{
    public function run()
    {
        $channels = param('channels');
        $this->render('channel_navbar', array(
            'channels' => $channels,
        ));
    }
}