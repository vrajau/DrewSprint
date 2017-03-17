<?php
namespace DrewSprint;
use \PDO;
class SprintDatabase extends BoardComponentDatabase {
		
	private $_week;

	public function __construct($week,$board,$db){
		parent::__construct($board,$db);
		$this->_week = $week;
	}

	public function create($tw,$ta,$tr,$pa,$pr){
		$sql_insert_sprint = 'INSERT INTO Sprint(timestamp,week,total_week,total_achieved,total_remaining,percentage_achieved,percentage_remaining) VALUES(?,?,?,?,?,?,?)';
		$this->query($sql_insert_sprint,[time(),$this->_week,$tw,$ta,$tr,$pa,$pr]);
	}

	public function insertPointsAddition($points,$percentage,$addition,$sprint){
		$sql_insert_addition = 'INSERT INTO PointsAddition(points,percentage,addition,sprint) VALUES(?,?,?,?)';
		$this->query($sql_insert_addition,[$points,$percentage,$addition,$sprint]);
	}

	public function delete($id_sprint){
		$sql_delete = 'DELETE FROM Sprint WHERE id = ?';
		$this->query($sql_delete,[$id_sprint]);
	}



	public function requestCurrent(){
		$sql_last_sprint = "SELECT s.id,s.timestamp,s.total_week FROM Sprint s, Week w,Board b  WHERE s.week = w.id AND w.id = ? AND w.board = b.id AND b.id = ?  ORDER BY id DESC LIMIT 1";
		$last_sprint = $this->query($sql_last_sprint,[$this->_week,$this->getBoard()])->fetch(PDO::FETCH_ASSOC);
		return  $last_sprint ; 
	}

	public function requestLast(){
		$sql_last_by_week = "SELECT s.id,s.total_week,s.total_remaining,s.total_achieved,s.percentage_achieved,s.percentage_remaining FROM Week w, Sprint s WHERE s.week = w.id AND w.board = ? AND s.week = ?  ORDER BY s.timestamp DESC ";
		$last_by_week = $this->query($sql_last_by_week,[$this->getBoard(),$this->_week])->fetch(PDO::FETCH_ASSOC);
		return $last_by_week;
	}

	public function requestByTimestamp($start,$end){
		$sql_request_tmpstp = 'SELECT * FROM Sprint s WHERE timestamp >= ? AND timestamp < ? AND week = ? ORDER BY s.timestamp';
		$bytimestamp = $this->query($sql_request_tmpstp,[$start,$end,$this->_week])->fetchAll(PDO::FETCH_ASSOC);
		return $bytimestamp;
	}

	public function requestAdditions(){
		$sql_addition_sprint = "SELECT p.points,p.percentage,p.sprint,l.name,l.color,l.id FROM PointsAddition p, Sprint sp, Week w,Setting st,Label l,Addition a WHERE w.id = sp.week AND sp.id = p.sprint AND w.setting = st.id AND a.id = p.addition AND a.label = l.id AND w.id =?";
		$additions_sprints = $this->query($sql_addition_sprint,[$this->_week])->fetchAll(PDO::FETCH_ASSOC);
		return $additions_sprints;
	}

	public function requestAddition($id_sprint){
		$sql_addition_sprint = "SELECT p.points,p.percentage,p.sprint,l.name,l.color,l.id FROM PointsAddition p, Sprint sp, Week w,Setting st,Label l,Addition a WHERE w.id = sp.week AND sp.id = p.sprint AND w.setting = st.id AND a.id = p.addition AND a.label = l.id AND w.id =? AND sp.id = ?";
		$additions_sprint = $this->query($sql_addition_sprint,[$this->_week,$id_sprint])->fetchAll(PDO::FETCH_ASSOC);
		return $additions_sprint;
	}

	public function request($id_sprint){
		
	}

	public function requestAll(){
		$sql_all_sprint = "SELECT s.id,s.total_week,s.total_remaining,s.total_achieved,s.percentage_achieved,s.percentage_remaining FROM Sprint s, Week w WHERE s.week = w.id AND w.id = ? ORDER BY s.timestamp";
		$snapshots = $this->query($sql_all_sprint,[$this->_week])->fetchAll(PDO::FETCH_ASSOC);
		return $snapshots;
	}
	
	

	
}