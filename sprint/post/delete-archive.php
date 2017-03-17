<?php

require_once '../setting_drewsprint.php';

if(isset($_POST['week'],$_POST['board'])){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_POST['board']);

	if($board){
		$board_db->getWeeks($board['id'])->delete($_POST['week']);
	}

}