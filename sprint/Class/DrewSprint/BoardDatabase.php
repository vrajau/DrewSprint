<?php
namespace DrewSprint;
use \PDO;
class BoardDatabase extends AccessDatabase {
	
	public function create($id,$name,$urlbg,$color){
		$sql_insert_boards = 'INSERT INTO Board(id,name,bgrUrl,color) VALUES(?,?,?,?)';
		$this->query($sql_insert_boards,[$id,$name,$urlbg,$color]);
	}

	public function getLists($id_board){
		return new ListDatabase($id_board,$this->_database);
	}

	public function getSettings($id_board){
		return new SettingDatabase($id_board,$this->_database);
	}

	public function getLabels($id_board){
		return new LabelDatabase($id_board,$this->_database);
	}

	public function getWeeks($id_board){
		return new WeekDatabase($id_board,$this->_database);
	}

	public function request($id_board){
		$sql_board = "SELECT * FROM  Board b WHERE b.id = ?";
		$board = $this->query($sql_board,[$id_board])->fetch(PDO::FETCH_ASSOC);
		return $board ;
	}

	public function requestAll(){
		$sql_board = "SELECT * FROM  Board b";
		$boards = $this->query($sql_board)->fetchAll(PDO::FETCH_ASSOC);
		return $boards;
	}
}