<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');


if(isset($_POST['addition']) && is_array($_POST['addition'])){

	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_POST['addition']['board']);
	if($board){
		$cache = file_get_contents('../cache/'.$board['id'].'_setting');
		if($cache !== false || !empty($cache)){
			$trello_data = unserialize($cache);
			$this_label = false;
			foreach($trello_data->labels as $label){
				if($label->id == $_POST['addition']['label']){
					$this_label = $label;
				}
			} 

			if($this_label && !$board_db->getWeeks($board['id'])->isActive()){
				$label_db = $board_db->getLabels($board['id']);
				$setting_db = $board_db->getSettings($board['id']);
				if(!empty($label_db->request($this_label->id))){
					//Do not remove the labels, simply delete from the additions
					//$setting_db->delete($this_label->id);
					echo feedback('Removed','success');
			 	}else{
					$label_db->getConnexion()->beginTransaction();
						$label_db->create($this_label->id,$this_label->name,$this_label->color);
						$setting_db->createAddition($setting_db->requestCurrent()['id'],$this_label->id);
					$label_db->getConnexion()->commit();
					echo feedback('Added','success');
			 	}
			}else{
				echo feedback('Error!','danger');
			}
		}
	}
}
		







