<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');

if(isset($_GET['id'])){
	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_GET['id']);

	if($board){
		if(!$board_db->getWeeks($board['id'])->isActive()){
			$cache = file_get_contents('../cache/'.$board['id'].'_setting');
			if($cache !== false || !empty($cache)){
				$board_data = unserialize($cache);
				//Cache the data
				$setting_db = $board_db->getSettings($board['id']);
				$current_setting = $setting_db->requestCurrent();

				$board_data->lists = array_filter($board_data->lists,function($list){
				 	$list_name = strtolower($list->name);
				 	return $list_name != 'sprint' && $list_name != 'completed'; 
				});

				$board_data->labels = array_filter($board_data->labels,function($label){
					 return !empty(trim($label->name));
				});

					$additions = $setting_db->requestAdditions($current_setting['id']);
					$included = $setting_db->requestIncluded($current_setting['id']);
			
					foreach($board_data->lists as $key=>$list){
						$list->set = false;
						if(array_key_exists($list->id,$included)){
							$list->set = true;
						}
					}	

					foreach($board_data->labels as $label){
						$label->set_additions = false;
						$label->set_stats = false;
						if(array_key_exists($label->id,$additions)){
							$label->set_additions = true;
						}
					}	
			
					echo json_encode(array('setting'=>$board_data,'time'=>date('j/n/Y H:i:s',time())));	
			
			}else{
				echo json_encode(array("error"=>'A problem occured! Reload the page'));
			}
				
		}else{
			echo json_encode(array("error"=>'Settings cannot be changed while the sprint is active'));
		}
	
	}


}