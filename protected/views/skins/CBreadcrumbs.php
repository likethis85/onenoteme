<?php
return array(
    'default' => array(
        'homeLink' => '<li>' . l('首页', CDBase::siteHomeUrl()) . '<span class="divider">/</span></li>',
        'separator' => '&nbsp;',
        'tagName' => 'ul',
        'htmlOptions' => array('class'=>'breadcrumb'),
        'activeLinkTemplate' => '<li><a href="{url}">{label}</a><span class="divider">/</span></li>',
        'inactiveLinkTemplate' => '<li>{label}</li>',
    ),
    'member' => array(
        'homeLink' => '<li>' . l('会员中心', CDBase::memberHomeUrl()) . '<span class="divider">/</span></li>',
        'separator' => '&nbsp;',
        'tagName' => 'ul',
        'htmlOptions' => array('class'=>'breadcrumb'),
        'activeLinkTemplate' => '<li><a href="{url}">{label}</a><span class="divider">/</span></li>',
        'inactiveLinkTemplate' => '<li>{label}</li>',
    ),
);