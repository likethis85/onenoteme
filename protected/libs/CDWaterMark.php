<?php
class CDWaterMark
{
    const POS_TOP_LEFT = 1;
    const POS_TOP_CENTER = 2;
    const POS_TOP_RIGHT = 3;
    const POS_RIGHT_MIDDLE = 4;
    const POS_BOTTOM_RIGHT = 5;
    const POS_BOTTOM_CENTER = 6;
    const POS_BOTTOM_LEFT = 7;
    const POS_LEFT_MIDDLE = 8;
    
    public $position = self::POS_BOTTOM_LEFT;
    public $image;
    public $opacity = 1;
    public $text;
    public $textColor;
    public $textSize;
    public $textFont;
    
    public function __construct()
    {
        
    }
    
    public function apply($im)
    {
        
    }
}
