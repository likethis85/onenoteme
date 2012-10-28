<?php
class BetaCategoryNavbar extends CWidget
{
    public function run()
    {
        $channels = param('channels');
        $this->render('category_navbar', array(
            'channels' => $channels,
        ));
    }
}