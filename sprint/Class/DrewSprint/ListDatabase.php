<?php

namespace DrewSprint;
use \PDO;
class ListDatabase extends BoardComponentDatabase{

	public function create($id,$name){
		$sql_insert_list = "INSERT INTO List(id,name,board) VALUES (?,?,?)";
		$this->query($sql_insert_list,[$id,$name,$this->getBoard()]);
	}

	public function delete($id_list){
		$sql_delete_list = 'DELETE FROM List WHERE id = ? AND board = ?';
		$this->query($sql_delete_list,[$id_list,$this->getBoard()]);
	}

	public function update($id_list,$new_id){
		$sql_update = "UPDATE List SET id = ? WHERE id = ? AND board = ?";
		$this->query($sql_update,[$new_id,$id_list,$this->getBoard()]);
	}

	public function updateName($id_list,$new_name){
		$sql_update_name = "UPDATE List SET name = ? WHERE id = ? AND board = ?";
		$this->query($sql_update_name,[$new_name,$id_list,$this->getBoard()]);
	}

	public function request($id_list){
		$sql_request_by_board = "SELECT l.id,l.name FROM List l , Board b WHERE l.board = b.id AND l.id = ? AND b.id = ?";
		$list = $this->query($sql_request_by_board,[$id_list,$this->getBoard()])->fetch(PDO::FETCH_ASSOC);
		return $list; 
	}

	public function requestAll(){
		$sql_request_by_board = "SELECT l.id,l.name FROM List l , Board b WHERE l.board = b.id AND b.id = ?";
		$lists = $this->query($sql_request_by_board,[$id_board])->fetchAll(PDO::FETCH_ASSOC);
		return $lists;
	}
	


}