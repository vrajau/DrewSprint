<?php

require_once '../setting_drewsprint.php';
header('Content-Type: application/json');

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_GET['id']);
	if($board){
		$setting_db = $board_db->getSettings($board['id']);
		$current_setting = $setting_db->requestCurrent();
		$sprint = $setting_db->requestSprint($current_setting['id']);
		$included = $setting_db->requestIncluded($current_setting['id']);
		$additions = $setting_db->requestAdditions($current_setting['id']);
		

		//We only need to check those 3 things for integrity, a missing included would not change the result of the previous sprint
		//stopSprint is here to indicate if the sprint was stopped to preserve integrity
		$cache = file_get_contents('../cache/'.$board['id'].'_setting');
		if($cache !== false || !empty($cache)){
			$trello_data = unserialize($cache);
			$integrity = array('sprint'=>false,'completed'=>false,'stopSprint'=>false);
			require_once __DIR__.'/integrity_sprint.php';
			
			if($integrity['sprint'] && $integrity['completed']){
				if($integrity['stopSprint']){
					$message = 'We had to stop the sprint. Changing the labels directly in Trello will do that';
				}
				//In order to make the key of each value in the array as an Id
				$list_for_snapshot = array_merge(array_flip($sprint),$included);
				//To each addition, add a total = 0
				foreach($additions as $id_addition=>$data){
					$additions[$id_addition]['total'] = 0;
				}
				
				//Give each list the array containing the additions and other information
				foreach($list_for_snapshot as $idList=>$data){
					//Because sprint and completed only have a string not an array
					if(!is_array($data)){
						$list_for_snapshot[$idList] = array();
					}
					$list_for_snapshot[$idList]['total'] = 0;
					$list_for_snapshot[$idList]['total_wo_add'] = 0;
					$list_for_snapshot[$idList]['additions'] = $additions;
				}
				/*
				 * This the core of the application, it will give each list the points for the cards, and the points for the additions
				 */
				foreach($trello_data->cards as $card){
					if(array_key_exists($card->idList, $list_for_snapshot)){
						$points = getPointsCard($card->name);
						$list_for_snapshot[$card->idList]['total'] += $points;
						$list_for_snapshot[$card->idList]['total_wo_add'] += $points;
						foreach($card->idLabels as $labelId){
							if(array_key_exists($labelId, $list_for_snapshot[$card->idList]['additions'])){
								$list_for_snapshot[$card->idList]['additions'][$labelId]['total'] += $points;
								$list_for_snapshot[$card->idList]['total_wo_add'] -= $points;
							}
						}
					}
				}

				// Cleaning to have proper result to exploit
				$result['sprint'] = $list_for_snapshot[$sprint['sprint']];
				$result['completed'] = $list_for_snapshot[$sprint['completed']];
				unset($list_for_snapshot[$sprint['sprint']]);
				unset($list_for_snapshot[$sprint['completed']]);
				$result['included'] = $list_for_snapshot;

				//Cache it and display information
				file_put_contents('../cache/'.$board['id'].'_sprint', serialize($result));
				$result['time'] = date('j/n/Y H:i',time());
				//Those information are not important enough to save, they just serve the master View, All hail master View!!
				$result['additions'] = $additions;
				$result['board'] = $board['name'];

			}else{
				$message = (!$integrity['sprint'])? 'Error : The sprint list is missing from trello' : 'Error : The completed column is missing from';
			}

			$result['message'] = (!isset($message))? '' : $message;
			echo json_encode($result);	
		}
		
	}
}

function getPointsCard($name){
	$pattern = '/((?:^|\s?))\((\x3f|\d*\.?\d+)(\))\s?/';
	preg_match($pattern,$name,$matches);
	return (!empty($matches))? $matches[2] : 0;
}

