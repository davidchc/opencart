<?php
/**
* HTTP status that PagSeguro webservices can return.
*/
class HttpStatus {
	
	private $typeList = array(
		200 => 'OK',
		400 => 'BAD_REQUEST',
		401 => 'UNAUTHORIZED',
		403 => 'FORBIDDEN',
		404 => 'NOT_FOUND',
		500 => 'INTERNAL_SERVER_ERROR',
		502 => 'BAD_GATEWAY'
	);
	private $status;
	private $type;
	
	public function __construct($status) {
		if ($status) {
			$this->status  = (int)$status;
			$this->type    = $this->getTypeByStatus($this->status);
		}
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	private function getTypeByStatus($status) {
		if (isset($this->typeList[(int)$status])) {
			return $this->typeList[(int)$status];
		} else {
			return false;
		}
	}
	
}
	
?>
