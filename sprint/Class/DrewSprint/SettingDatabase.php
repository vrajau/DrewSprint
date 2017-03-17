<?php
namespace DrewSprint;
use \PDO;


class SettingDatabase extends BoardComponentDatabase{

	public function create($sprint,$completed){
		$sql_create = "INSERT INTO Setting(board,sprint,completed) VALUES (?,?,?)";
		$this->query($sql_create,[$this->getBoard(),$sprint,$completed]);
	}

	public function createInclude($setting,$id_list){
		$sql_insert_include = "INSERT INTO Included(list,setting) VALUES (?,?)";
		$this->query($sql_insert_include,[$id_list,$setting]);
	}

	public function createAddition($setting,$id_label){
		$sql_insert_addition = "INSERT INTO Addition(label,setting) VALUES (?,?)";
		$this->query($sql_insert_addition,[$id_label,$setting]);
	}
 

	public function requestSprint($id_setting){
		$sql_sprint = "SELECT l.id as sprint FROM List l, Setting s WHERE s.sprint = l.id AND s.id = ? AND s.board = ?";
		$sql_completed = "SELECT l.id as completed FROM List l, Setting s WHERE s.completed = l.id AND s.id = ? AND s.board = ?";
		return array_merge($this->query($sql_sprint,[$id_setting,$this->getBoard()])->fetch(PDO::FETCH_ASSOC),$this->query($sql_completed,[$id_setting,$this->getBoard()])->fetch(PDO::FETCH_ASSOC));
	}

	public function requestAdditions($id_setting){
		$sql_request_additions = "SELECT l.id as label_id,l.color,l.name,a.id as addition_id FROM Addition a, Setting s,Label l WHERE a.setting = s.id AND l.id = a.label AND s.id = ?";
		$additions = $this->query($sql_request_additions,[$id_setting])->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
		return $additions;
	}

	public function requestIncluded($id_setting){
		$sql_request_included = "SELECT l.id as list_id,l.name,i.id as included_id FROM Included i, Setting s,List l WHERE i.setting = s.id AND i.list = l.id AND  s.id = ?";
		$included = $this->query($sql_request_included,[$id_setting])->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
		return $included;
	}

	public function requestAddition($id_setting,$id_label){
		$sql_request_addition = "SELECT a.id FROM Addition a, Setting s WHERE a.setting = s.id AND s.id = ? AND a.label = ? ";
		$addition = $additions = $this->query($sql_request_addition,[$id_setting,$id_label])->fetch(PDO::FETCH_ASSOC);
		return $addition;
	}	

	public function requestCurrent(){
		$sql_request_by_board = "SELECT s.id,s.board FROM Setting s, Board b WHERE s.board = b.id AND b.id = ? ORDER BY id DESC LIMIT 1";
		$last_setting = $this->query($sql_request_by_board,[$this->getBoard()])->fetch(PDO::FETCH_ASSOC);
		return  $last_setting; 
	}

	public function request($id_setting){
		$sql_request_by_board = "SELECT s.id,s.sprint,s.completed FROM Setting s, Board b WHERE s.board = b.id AND s.id = ? AND b.id = ?";
		$setting = $this->query($sql_request_by_board,[$id_setting,$this->getBoard()])->fetch(PDO::FETCH_ASSOC);
		return $setting;
	}

	public function requestAll(){
		$sql_request_by_board = "SELECT s.id,s.sprint,s.completed FROM Setting s, Board b WHERE s.board = b.id AND b.id = ?";
		$settings = $this->query($sql_request_by_board,[$this->getBoard()])->fetchAll(PDO::FETCH_ASSOC);
		return $settings;
	}
		
	

}
