<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_GET['id']);

	if($board){
		$archives = [];
		$previous_weeks = $board_db->getWeeks($board['id'])->requestAllUnactive();

		for($i = 0; $i < count($previous_weeks); $i++){
			$results = [];
			$sprint_db = $board_db->getWeeks($board['id'])->getSprints($previous_weeks[$i]['id']);
			$additions = $board_db->getSettings($board['id'])->requestAdditions($previous_weeks[$i]['setting']);
			foreach($sprint_db->requestAll() as $key=>$data){
				$results['total_week'][] = $data['total_week'];
				$results['total_achieved'][] = $data['total_achieved'];
				$results['total_remaining'][] = $data['total_remaining'];
				$results['percentage_remaining'][] = $data['percentage_remaining'];
				$results['percentage_achieved'][]  = $data['percentage_achieved'];
			}
			$results['additions'] = $additions;

			foreach($sprint_db->requestAdditions() as $key=>$data){
				$results['additions'][$data['id']]['total'][] = $data['points'];
				$results['additions'][$data['id']]['percentage'][] = $data['percentage'];
			}
			$archives[$i + 1]['results'] = $results;
			$archives[$i + 1]['idWeek'] = $previous_weeks[$i]['id'];
		}

		echo json_encode($archives);
	}
}