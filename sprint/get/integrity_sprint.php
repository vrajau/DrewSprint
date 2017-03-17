<?php
/**
* This part check whether the sprint or the completed list are correct and update them if their id changed, if they exist it will also update the value of sprint_exist and
* completed_exist to true, meaning that the trello board still have those two mandatory list
*/
		
foreach($trello_data->lists as $list){
	if(strtolower($list->name) == 'sprint' ){
		$integrity['sprint'] = true;
		if($list->id != $sprint['sprint']){
			$board_db->getLists($board['id'])->update($sprint['sprint'],$list->id);	
		}
	}

	if(strtolower($list->name) == 'completed'){
		$integrity['completed'] = true;
		if($list->id != $sprint['completed']){
			$board_db->getLists($board['id'])->update($sprint['completed'],$list->id);
		}
	}
}
//update to new sprint
$sprint = $setting_db->requestSprint($current_setting['id']);






/*
 * This will check the included list integrity, it will either delete them if they do not exists anymore in Trello, or update their name if the name was changed
 * Missing included is low risk since the points for the included are directly added in the sprint each time,it means we do not need to keep track of those included list
 * over time.
 */


foreach($trello_data->lists as $list){
	$list_trello_id[$list->id] = $list->name;
}

foreach($included as $idList=>$data){
	if(!array_key_exists($idList,$list_trello_id)){
		$board_db->getLists($board['id'])->delete($idList);
	}else if(array_key_exists($idList,$list_trello_id) && $list_trello_id[$idList] != $data['name']){
		$board_db->getLists($board['id'])->updateName($idList,$list_trello_id[$idList]);
	}
}
$included = $setting_db->requestIncluded($current_setting['id']);



/*
 * Here's come the tricky part, additions are not like included, keeping them over time is important because they define the sprint itself, missing additions in between a sprint would screw up 
 * the entire sprint itself, so we need to stop the sprint before any damage is done. Also, for additions the name as a meaning, which mean we need to keep the labels, a label could have a 
 * different name, the same id and a complete different meaning. That is why design-wise, I decided to not change the name when this one change in trello.
 */

foreach($trello_data->labels as $label){
	$label_trello_id[$label->id] = $label->color;
}

foreach($additions as $idLabel=>$data){
	if(!array_key_exists($idLabel,$label_trello_id)){
		$corrupted[] = $idLabel;
	}else if(array_key_exists($idLabel, $label_trello_id) && $label_trello_id[$idLabel] != $data['color']){
		$board_db->getLabels($board['id'])->updateColor($idLabel,$label_trello_id[$idLabel]);
	}
}


if(!empty($corrupted) && $integrity['sprint'] && $integrity['completed']){
	//If the board is active, we need to finish the current sprint in order to avoid corrupting the data
	if($board_db->getWeeks($board['id'])->isActive()){
 		$board_db->getWeeks($board['id'])->desactivate();
 		$integrity['stopSprint'] = true;
	 }
	$setting_db->create($sprint['sprint'],$sprint['completed']);
	$current_setting = $setting_db->requestCurrent();
	//Add included
	foreach($included as $idList=>$data){
		$setting_db->createInclude($current_setting['id'],$idList);
	}
	//Add Additions
	foreach($additions as $idLabel=>$data){
 		if(!in_array($idLabel,$corrupted)){
 			$setting_db->createAddition($current_setting['id'],$idLabel);
		}
	}
}
$additions = $setting_db->requestAdditions($current_setting['id']);