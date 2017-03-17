<?php

namespace DrewSprint;
use \PDO;

class LabelDatabase extends BoardComponentDatabase{
	
	public function create($id,$name,$color){
		$color = (is_null($color))? 'grey' : $color;
		$sql_insert_label = "INSERT INTO Label(id,name,color,board) VALUES (?,?,?,?)";
		$this->query($sql_insert_label,[$id,$name,$color,$this->getBoard()]);
	}

	public function delete($id_label){
		$sql_delete_label = 'DELETE FROM Label WHERE id = ? AND board = ?';
		$this->query($sql_delete_label,[$id_label,$this->getBoard()]);
	
	}

	public function updateColor($id_label,$color){
		$color = (is_null($color))? 'grey' : $color;
		$sql_update_color = "UPDATE Label SET color = ? WHERE id = ? AND board = ?";
		$this->query($sql_update_color,[$color,$id_label,$this->getBoard()]);
	}

	public function request($id_label){
		$sql_request_by_board = "SELECT l.id,l.name FROM Label l , Board b WHERE l.board = b.id AND l.id = ? AND b.id = ?";
		$label = $this->query($sql_request_by_board,[$id_label,$this->getBoard()])->fetch(PDO::FETCH_ASSOC);
		return $label ;
	}

	public function requestAll(){
		$sql_request_by_board = "SELECT l.id,l.name FROM Label l , Board b WHERE l.board = b.id AND b.id = ?";
		$labels = $this->query($sql_request_by_board,[$this->getBoard()])->fetchAll(PDO::FETCH_ASSOC);
		return $labels;
	}	

}