<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
	
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_GET['id']);	
	if($board){
		$status = array('status'=>$board_db->getWeeks($board['id'])->isActive());
		echo json_encode($status);
	}
}