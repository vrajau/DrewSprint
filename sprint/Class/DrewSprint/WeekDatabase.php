<?php

namespace DrewSprint;
use \PDO;
class WeekDatabase extends BoardComponentDatabase{


	public function create($id_setting){
		$sql_create_week = "INSERT INTO Week(board,setting) VALUES (?,?)";
		$this->query($sql_create_week,[$this->getBoard(),$id_setting]);
	}

	public function delete($id_week){
		$sql_delete_list = 'DELETE FROM Week WHERE id = ? AND board = ?';
		$this->query($sql_delete_list,[$id_week,$this->getBoard()]);
	}

	public function requestActiveWeek(){
		$sql_active_week = "SELECT w.id,w.setting FROM Week w, Board b WHERE b.id = w.board AND w.active = 1 AND b.id = ?";
		$active_week = $this->query($sql_active_week,[$this->getBoard()])->fetch(PDO::FETCH_ASSOC);
		return $active_week;
	}


	public function isActive(){
		return (!empty($this->requestActiveWeek()))? true : false;
	}

	
	public function desactivate(){
		$sql_update_status = "UPDATE Week SET active = 2 WHERE board = ? AND active = 1 ";
		$this->query($sql_update_status,[$this->getBoard()]);
	}

	public function getSprints($id_week){
		return new SprintDatabase($id_week,$this->getBoard(),$this->_database);
	}

	public function requestAllUnactive(){
		$sql_request_all = "SELECT * FROM Week w WHERE w.board = ? AND w.active = 2";
		return $this->query($sql_request_all,[$this->getBoard()])->fetchAll(PDO::FETCH_ASSOC);
	
	}

	public function request($id_week){
	}

	public function requestAll(){
		$sql_request_all = "SELECT * FROM Week w WHERE w.board = ?";
		return $this->query($sql_request_all,[$this->getBoard()])->fetchAll(PDO::FETCH_ASSOC);
	}
	


}