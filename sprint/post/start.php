<?php
require_once '../setting_drewsprint.php';

if(isset($_POST['id']) && !empty($_POST['id'])){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_POST['id']);

	if($board){
		$setting_db = $board_db->getSettings($board['id']);
		$weeks_db = $board_db->getWeeks($board['id']);
		if(!$weeks_db->isActive()){
			$cache = file_get_contents('../cache/'.$board['id'].'_sprint');
			if($cache !== false and !empty($cache)){
				$current_setting = $setting_db->requestCurrent();
				$additions = $setting_db->requestAdditions($current_setting['id']);
				$snapshot = unserialize($cache);
				include_once __DIR__.'/summary.php';
				
				  $board_db->getConnexion()->beginTransaction();
					$weeks_db->create($current_setting['id']);
					$active_week = $weeks_db->requestActiveWeek();
					$sprint_db = $weeks_db->getSprints($active_week['id']);
					$sprint_db->create($total_week,$total_achieved,$total_remaining,$percentage_achieved,$percentage_remaining);
					$current_sprint = $sprint_db->requestCurrent();
					foreach($additions as $id=>$data){
						$sprint_db->insertPointsAddition($data['total'],$data['percentage'],$data['addition_id'],$current_sprint['id']);
					}
				$board_db->getConnexion()->commit();

			}else{
				$message = 'A problem occured. Reload the page or refresh the sprint';
			}
		}
	}
}

	

	
