<?php
require_once '../setting_drewsprint.php';
header('Content-Type: application/json');

if(isset($_POST['url']) && !empty(trim($_POST['url']))){

	$parameters = array(
		'fields'=>array('name','shortLink','prefs'),
		'labels'=>'all',
		'label_fields'=>array('name','color'),
		'lists'=>'open',
		'list_fields'=>'name',
		'cards'=>'open',
		'card_fields'=>array('name','idList','idLabels')
		);

	$board = $trello->getBoard($_POST['url'],$parameters);
	if($board){
		$board_database = new DrewSprint\BoardDatabase($database);
		
		$sprint = array_filter($board->lists,function($list){
				$name = strtolower($list->name);
				return $name == 'sprint';
		});

		$completed = array_filter($board->lists,function($list){
				$name = strtolower($list->name);
				return $name == 'completed';
		});

		if(!empty($board_database->request($board->id))){
			$message = 'This board already exists';
		}else if(count($board->lists) < 2){
			$message = 'We require each board to have at least two lists in order to function';
		}else if(count($sprint) < 1){
			$message = 'You need to have a list named "Sprint" in your board';
		}else if(count($sprint) > 1){
			$message = 'It seems like you have more than one list called "Sprint" in your board';
		}else if(count($completed) < 1){
			$message = 'You need to have a list named "Completed" in your board';
		}else if(count($completed) > 1){
			$message = 'It seems like you have more than one list called "Completed" in your board';
		}else{
			$sprint = array_shift($sprint);
			$completed = array_shift($completed);

			if(is_null($board->prefs->backgroundImage)){
				$color = $board->prefs->backgroundColor;
			}

			//Start Transaction
			$database->beginTransaction();
			//Create Board
			$board_database->create($board->id,$board->name,$board->prefs->backgroundImageScaled[2]->url,$board->prefs->backgroundColor);
			$list = $board_database->getLists($board->id);
			$list->create($sprint->id,$sprint->name);
			$list->create($completed->id,$completed->name);
			$board_database->getSettings($board->id)->create($sprint->id,$completed->id);
			$trello_post = new DrewSprint\TrelloData(APP_KEY,TOKEN);
			$trello_post->postWebhook($board->id,$board->name);
			file_put_contents('../cache/'.$board->id.'_setting', serialize($board));
			//End Transaction
			$database->commit();
		}
	}else{
		$message = 'We could not find this board. The URL might be wrong';
	}
	echo json_encode($message);	
}