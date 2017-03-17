<?php
/**
 * Am i missing something, because why can you do /board/[idboard]/label/[idLabel] but you cant do /board/[idBoard]/list/[idList], I sent a email to the trello team, here's the answer :
 * Hi Vincent, Great question. The reason that that is the case is because we have a whole separate List API (https://developers.trello.com/advanced-reference/list) that can be used to 
 * aggregate all that additional information. We try to not have multiple methods that do the same thing to avoid complication in our API. 
 */
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');
if(isset($_POST['included']) && is_array($_POST['included'])){


	$board_db = new DrewSprint\BoardDatabase($database);
	$board = $board_db->request($_POST['included']['board']);

	if($board){
		$cache = file_get_contents('../cache/'.$board['id'].'_setting');
		if($cache !== false || !empty($cache)){
			$trello_data = unserialize($cache);
			$this_list= false;
			foreach($trello_data->lists as $list){
				if($list->id == $_POST['included']['list']){
					$this_list = $list;
				}
			}
			if($this_list && !$board_db->getWeeks($board['id'])->isActive()){
				$list_db = $board_db->getLists($board['id']);
				if(!empty($list_db->request($this_list->id))){
					$list_db->delete($this_list->id);
					echo feedback('Removed','success');
				}else{
					$setting_db = $board_db->getSettings($board['id']);
					$list_db->getConnexion()->beginTransaction();
						$list_db->create($this_list->id,$this_list->name);
						$setting_db->createInclude($setting_db->requestCurrent()['id'],$this_list->id);
					$list_db->getConnexion()->commit();
					echo feedback('Added','success');
				}
			}else{
		 		echo feedback('Error. Not updated.','danger');
		 	}
		}			
	}
}



		

