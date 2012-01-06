<?php
	class PagSeguroServiceException extends Exception{
	
		private $httpStatus;
		private $httpMessage;
		private $errors = Array();
		
		public function __construct(HttpStatus $httpStatus, Array $errors = null) {
			$this->httpStatus  = $httpStatus;
			if ($errors) {
				$this->errors = $errors;
			}
			$this->message = $this->getFormatedMessage();
		}
		
		public function getErrors($errors){
			return $this->errors;
		}		
		public function setErrors(Array $errors){
			$this->errors = errors;
		}
		
		public function getHttpStatus(){
			return $this->httpStatus;
		}		
		public function setHttpStatus(HttpStatus $httpStatus){
			$this->httpStatus = $httpStatus;
		}
		
		private function getHttpMessage() {
			switch($this->httpStatus->getType()){
				
				case 'BAD_REQUEST':
					$message = "BAD_REQUEST";
					break;
				
				case 'UNAUTHORIZED':
					$message = "UNAUTHORIZED";
					break;
				
				case 'FORBIDDEN':
					$message = "FORBIDDEN";
					break;
				
				case 'NOT_FOUND':
					$message = "NOT_FOUND";
					break;
				
				case 'INTERNAL_SERVER_ERROR':
					$message = "INTERNAL_SERVER_ERROR";
					break;
					
				case 'BAD_GATEWAY':
					$message = "BAD_GATEWAY";
					break;
				
				default:
					$message = "UNDEFINED";
					break;
					
			}
			return $message;
		}
		
		public function getFormatedMessage(){
			$message  = "";
			$message .= "[HTTP " . $this->httpStatus->getStatus() . "] - " . $this->getHttpMessage(). "\n";
			foreach ($this->errors as $key => $value) {
				if ($value instanceof PagSeguroError) {
					$message .= "[" .$value->getCode() . "] - " . $value->getMessage();
				}
			}
			return $message;
		}
		
		public function getOneLineMessage() {
			return str_replace("\n", " ", $this->getFormatedMessage());
		}
		
	}
?>