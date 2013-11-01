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
    
    // 直接输出广告代码
    public $onlyCode = false;
    
    /**
     * 如果一个广告位有多个广告启用，是否支持多个广告随机显示，默认为开启
     * @var boolean
     */
    public $multi = false;
    
    public $bizrule = true;
    
    public $boxClass = '';
    
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
        
        $adcode = $this->getAdcodeByWeight($data);
        
        if (empty($adcode)) return ;
        
        if ($this->onlyCode)
            echo $adcode['adcode'];
        else {
            $html = sprintf('<div class="cdc-block ad-block %s">', $this->boxClass);
            if ($this->title)
                $html .= '<h2>' . $this->title . '</h2>';
            
            $html .= $adcode['adcode'] . '</div>';
            
            echo $html;
        }
    }
    
    private function getAdcodeByWeight($data)
    {
        $newWeights = array();
        foreach ($data as $i => $row) {
            $weight = (int)$row['weight'];
            if ($weight < 1 || (!$this->bizrule && $row['check_bizrule'])) {
                unset($data[$i]);
                continue;
            }
            $newWeights = array_merge($newWeights, array_fill(0, $weight, $i));
        }
        
        if (empty($data))
            return null;
        elseif (!$this->multi)
            return current($data);
        else {
            $randKey = (int)array_rand($newWeights);
            $index = (int)$newWeights[$randKey];
            $newWeights = null;
            return $data[$index];
        }
        
    }
    
}



