<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_GET['id']);
	
	if($board){
		$weekly_result = array();
		
		if($board_db->getWeeks($board['id'])->isActive()){
			$active_week = $board_db->getWeeks($board['id'])->requestActiveWeek();
			$additions = $board_db->getSettings($board['id'])->requestAdditions($board_db->getSettings($board['id'])->requestCurrent()['id']);
			$snapshots = $board_db->getWeeks($board['id'])->getSprints($active_week['id'])->requestAll();
			$snapshots_additions = $board_db->getWeeks($board['id'])->getSprints($active_week['id'])->requestAdditions();


			foreach($snapshots as $key=>$data){
				$weekly_result['total_week'][] = $data['total_week'];
				$weekly_result['total_achieved'][] = $data['total_achieved'];
				$weekly_result['total_remaining'][] = $data['total_remaining'];
				$weekly_result['percentage_remaining'][] = $data['percentage_remaining'];
				$weekly_result['percentage_achieved'][]  = $data['percentage_achieved'];
			}
			
			foreach($snapshots_additions as $key=>$data){
				$additions[$data['id']]['total'][] = $data['points'];
				$additions[$data['id']]['percentage'][] = $data['percentage'];
			}

			$weekly_result['additions'] = $additions;
		}
		echo json_encode($weekly_result);
	}
}