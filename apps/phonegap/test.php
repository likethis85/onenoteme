<?php
$data = array(
	array('id'=>1, 'content'=>'Deng 1', 'create_time'=>'2011-11-11 11:11', 'up_socre'=>11, 'down_score'=>21),
	array('id'=>2, 'content'=>'Deng 2', 'create_time'=>'2011-11-22 11:11', 'up_socre'=>12, 'down_score'=>22),
	array('id'=>3, 'content'=>'Deng 3', 'create_time'=>'2011-11-01 11:11', 'up_socre'=>13, 'down_score'=>23),
	array('id'=>4, 'content'=>'Deng 4', 'create_time'=>'2011-11-02 11:11', 'up_socre'=>14, 'down_score'=>24),
	array('id'=>5, 'content'=>'Deng 5', 'create_time'=>'2011-11-03 11:11', 'up_socre'=>15, 'down_score'=>25),
	array('id'=>6, 'content'=>'Deng 6', 'create_time'=>'2011-11-04 11:11', 'up_socre'=>16, 'down_score'=>26),
	array('id'=>11, 'content'=>'Deng 1', 'create_time'=>'2011-11-11 11:11', 'up_socre'=>11, 'down_score'=>21),
	array('id'=>12, 'content'=>'Deng 2', 'create_time'=>'2011-11-22 11:11', 'up_socre'=>12, 'down_score'=>22),
	array('id'=>13, 'content'=>'Deng 3', 'create_time'=>'2011-11-01 11:11', 'up_socre'=>13, 'down_score'=>23),
	array('id'=>14, 'content'=>'Deng 4', 'create_time'=>'2011-11-02 11:11', 'up_socre'=>14, 'down_score'=>24),
	array('id'=>15, 'content'=>'Deng 5', 'create_time'=>'2011-11-03 11:11', 'up_socre'=>15, 'down_score'=>25),
	array('id'=>16, 'content'=>'Deng 6', 'create_time'=>'2011-11-04 11:11', 'up_socre'=>16, 'down_score'=>26),
	array('id'=>21, 'content'=>'Deng 1', 'create_time'=>'2011-11-11 11:11', 'up_socre'=>11, 'down_score'=>21),
	array('id'=>22, 'content'=>'Deng 2', 'create_time'=>'2011-11-22 11:11', 'up_socre'=>12, 'down_score'=>22),
	array('id'=>23, 'content'=>'Deng 3', 'create_time'=>'2011-11-01 11:11', 'up_socre'=>13, 'down_score'=>23),
	array('id'=>24, 'content'=>'Deng 4', 'create_time'=>'2011-11-02 11:11', 'up_socre'=>14, 'down_score'=>24),
	array('id'=>25, 'content'=>'Deng 5', 'create_time'=>'2011-11-03 11:11', 'up_socre'=>15, 'down_score'=>25),
	array('id'=>26, 'content'=>'Deng 6', 'create_time'=>'2011-11-04 11:11', 'up_socre'=>16, 'down_score'=>26),
	array('id'=>31, 'content'=>'Deng 1', 'create_time'=>'2011-11-11 11:11', 'up_socre'=>11, 'down_score'=>21),
	array('id'=>32, 'content'=>'Deng 2', 'create_time'=>'2011-11-22 11:11', 'up_socre'=>12, 'down_score'=>22),
	array('id'=>33, 'content'=>'Deng 3', 'create_time'=>'2011-11-01 11:11', 'up_socre'=>13, 'down_score'=>23),
	array('id'=>34, 'content'=>'Deng 4', 'create_time'=>'2011-11-02 11:11', 'up_socre'=>14, 'down_score'=>24),
	array('id'=>35, 'content'=>'Deng 5', 'create_time'=>'2011-11-03 11:11', 'up_socre'=>15, 'down_score'=>25),
	array('id'=>36, 'content'=>'Deng 6', 'create_time'=>'2011-11-04 11:11', 'up_socre'=>16, 'down_score'=>26),
);

$offset = (int)$_GET['start'];

$data = array_splice($data, $offset, (int)$_GET['limit']);
sleep(2);
echo json_encode($data);
