<?php
require_once '../setting_drewsprint.php';

if(isset($_POST['id']) && !empty($_POST['id'])){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_POST['id']);

	if($board){
		$weeks_db = $board_db->getWeeks($board['id']);
		if($weeks_db->isActive()){
			$weeks_db->desactivate();
		} 
	}

}