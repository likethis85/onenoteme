<?php
class CDAdvert extends CWidget
{
    /**
     * 广告位上方标题
     * @var string
     */
    public $title;
    
    /**
     * 广告位唯一标识字符串
     * @var string
     */
    public $solt;
    
    /**
     * 如果一个广告位有多个广告启用，是否支持多个广告随机显示，默认为开启
     * @var boolean
     */
    public $multi = true;
    
    public function init()
    {
        $this->title = strip_tags(trim($this->title));
        $this->solt = trim($this->solt);
        $this->multi = (bool)$this->multi;
    }
    
    public function run()
    {
        $data = Advert::fetchAdcodesWithSolt($this->solt);
        if (empty($data)) return;
        
        $index = 0;
        if ($this->multi && count($data) > 1)
            $index = mt_rand(0, count($data)-1);
        
        $adcode = $data[$index];
        if (empty($adcode)) return;
        
        $html = '<div class="beta-block beta-radius3px">';
        if ($this->title)
            $html .= '<h2>' . $this->title . '</h2>';
        
        $html .= $adcode['adcode'] . '</div>';
        
        echo $html;
    }
}