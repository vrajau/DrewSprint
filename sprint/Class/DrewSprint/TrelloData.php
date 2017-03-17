<?php
namespace DrewSprint;

class TrelloData{
	private $_app_key;
	private $_token;
	private $_curl;

	const STARTPOINT = "https://api.trello.com/1/";
	const ENDPOINT_BOARDS = "boards/";
	const ENDPOINT_LISTS = "lists/";
	const ENDPOINT_CARDS = "cards/";
	const ENDPOINT_LABELS = "labels/";

	public function __construct($app,$token){
		$this->_app_key = $app;
		$this->_token = $token;
		$this->_curl = curl_init();
	}


	public function generateParameters($parameters=array()){
		$request = '?';
		$parameters['key'] = $this->_app_key;
		$parameters['token'] = $this->_token;
		$request .= http_build_query($parameters);
		return $request;
	}


	private function get($request){
		$request = self::STARTPOINT.$request;
		curl_setopt_array($this->_curl, array(
			CURLOPT_RETURNTRANSFER=>1,
			CURLOPT_ENCODING=>'',
			CURLOPT_URL=>$request,
			CURLOPT_IPRESOLVE=>CURL_IPRESOLVE_V4 
			));
		$data = curl_exec($this->_curl);
		$data = json_decode($data);
		$result =  ($data !== NULL)? $data : false;
		return $result;
	}


	public function post($request,$parameters){
		curl_setopt_array($this->_curl, array(
				CURLOPT_RETURNTRANSFER=>1,
				CURLOPT_POST=>1,
				CURLOPT_HEADER=>1,
				CURLOPT_URL=>$request,
				CURLOPT_POSTFIELDS=>$parameters
			));
			$data = curl_exec($this->_curl);
			var_dump($data);
	}

	public function getBoard($id_board,$parameters=''){
		$parameters = $this->generateParameters($parameters);
		$request = self::ENDPOINT_BOARDS.$id_board.$parameters;
		return $this->get($request);
	}


	public function getLabel($id_board,$id_label){
		$request = self::ENDPOINT_BOARDS.$id_board.'/'.self::ENDPOINT_LABELS.$id_label.$this->generateParameters();
		return $this->get($request);
	}

	public function getList($id_list){
		$request = self::ENDPOINT_LISTS.$id_list.'/'.$this->generateParameters();
		return $this->get($request);
	}

	public function postWebhook($idModel,$name){
		$request = self::STARTPOINT.'tokens/'.$this->_token.'/webhooks/';
		$parameters = array(
			'key'=>$this->_app_key,
			'description'=>'DrewSprint Webhook for '.$name,
			'callbackURL'=>'http://stripe.drewsprint.ultrahook.com',
			'idModel'=>$idModel
		);
		$parameters = http_build_query($parameters);
		$this->post($request,$parameters);
	}


	public function __destruct(){
		curl_close($this->_curl);
	}
}