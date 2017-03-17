<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');
$json_hooks = file_get_contents('php://input');
$idBoard = json_decode($json_hooks)->model->id;

$board_db = new DrewSprint\BoardDatabase($database);
$board = $board_db->request($idBoard);

if($board){
	$parameters = array(
			'fields'=>array('name','shortLink'),
			'labels'=>'all',
			'label_fields'=>array('name','color'),
			'lists'=>'open',
			'list_fields'=>'name',
			'cards'=>'open',
			'card_fields'=>array('name','idList','idLabels')
	);
	$board_data = $trello->getBoard($board['id'],$parameters);
	file_put_contents('../cache/'.$board['id'].'_setting', serialize($board_data));
}



