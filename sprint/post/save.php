<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');
if(isset($_POST['id']) && !empty($_POST['id'])){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_POST['id']);

	if($board){
		$setting_db = $board_db->getSettings($board['id']);
		$weeks_db = $board_db->getWeeks($board['id']);
		
		if($weeks_db->isActive()){
			$cache = file_get_contents('../cache/'.$board['id'].'_sprint');
			
			if($cache !== false and !empty($cache)){
				$current_setting = $setting_db->requestCurrent();
				$additions = $setting_db->requestAdditions($current_setting['id']);
				$active_week = $weeks_db->requestActiveWeek();
				$sprint_db = $weeks_db->getSprints($active_week['id']);
				$snapshot = unserialize($cache);
				$isError = false;
				include_once __DIR__.'/summary.php';
				
				$tmp_sprint = $sprint_db->requestByTimestamp(strtotime('today'),strtotime('tomorrow'));
				$numberOfSprint = count($sprint_db->requestAll());
				if(!empty($tmp_sprint) && $numberOfSprint > 1){
					//This case is only if we are on day 1, and it can exists 2 sprint (initial and day 1), we just want to replace the day 1 and not the initial one
					if(count($tmp_sprint) == 2){
						$sprint_db->delete($tmp_sprint[1]['id']);
					}else{
						foreach($tmp_sprint as $key=>$data){
							$sprint_db->delete($tmp_sprint[$key]['id']);
						}
					}
					
				}
				
				 
				if(empty($tmp_sprint) && $numberOfSprint == 5){
					$message = 'The sprint is full';
					$isError = true;
				}else{
					$board_db->getConnexion()->beginTransaction();
						$sprint_db->create($total_week,$total_achieved,$total_remaining,$percentage_achieved,$percentage_remaining);
						$current_sprint = $sprint_db->requestCurrent();
						foreach($additions as $id=>$data){
							$sprint_db->insertPointsAddition($data['total'],$data['percentage'],$data['addition_id'],$current_sprint['id']);
						}
					$board_db->getConnexion()->commit();
					$message  = 'Snapshot was successfully saved';
				}
  
			}else{
				$message = 'A problem occured. Reload the page or refresh the sprint';
				$isError = true;
			}

			echo json_encode(array('message'=>$message,'error'=>$isError));

		}
	}
}