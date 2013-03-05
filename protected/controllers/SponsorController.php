<?php
class SponsorController extends Controller
{
    public function actionIndex()
    {
        
        $this->pageTitle = '赞助我们';
        $this->setKeywords('赞助挖段子,赞助我们,支付宝收款主页,挖段子的收款主页');
        $this->setDescription('挖段子网的发展离不开各位段友的支持，目前我们的广告收入无法支持我们的日常开支和基础设施开支，所以在此借助广大段友的帮助，不限金额，不限形式。');
        $this->channel = NAV_SUPPORT;
        $this->render('index');
    }
}