<?php

namespace DrewSprint;

abstract class BoardComponentDatabase extends AccessDatabase{

	protected $_board;

	public function __construct($id_board,$database){
		parent::__construct($database);
		$this->_board = $id_board;
	}

	public function getBoard(){
		return $this->_board;
	}

	abstract public function request($id);
	abstract public function requestAll();
}