<?php

namespace DrewSprint;
use \PDO;

abstract class AccessDatabase{
	protected $_database;

	public function __construct(PDO $database){
		$this->_database = $database;
		
	}

	public function query($sql,$params = []){
		$query  = $this->_database->prepare($sql);
		$query->execute($params);
		return $query;
	}

	public function getConnexion(){
		return $this->_database;
	}

}