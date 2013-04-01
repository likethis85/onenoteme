<?php
class CDSwitchMobilFilter extends CFilter
{
    public function preFilter($filterChain)
    {
        $url = url('mobile/' . $filterChain->controller->id . '/' . $filterChain->action->id, $filterChain->controller->actionParams);
        $filterChain->controller->autoSwitchMobile($url);
        return true;
    }
}