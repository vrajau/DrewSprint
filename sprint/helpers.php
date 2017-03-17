<?php

function feedback($message,$type='',$result=''){
	$type_default = ['warning','danger','success'];
	$type = (in_array($type,$type_default))? $type : 'notype';
	return json_encode(array('message'=>$message,'type'=>$type,'result'=>$result)); 
}


function displayBoards($database,$page='board'){
	$boards = new DrewSprint\BoardDatabase($database);
	foreach($boards->requestAll() as $key=>$data){
		echo '<li><a href="'.$page.'.php?id='.$data['id'].'">'.$data['name'].'</a></li>';
	}
}
