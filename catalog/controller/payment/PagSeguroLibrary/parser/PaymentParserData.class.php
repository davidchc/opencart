<?php

class PaymentParserData{
	
	private $code;
	private $registrationDate;
	
	public function getCode(){
		return $this->code;
	}
	public function setCode($code){
		$this->code = $code;
	}
	
	public function getRegistrationDate(){
		return $this->registrationDate;
	}		
	public function setRegistrationDate($registrationDate){
		$this->registrationDate = $registrationDate;
	}		
	
}

?>