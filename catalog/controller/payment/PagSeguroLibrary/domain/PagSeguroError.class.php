<?php
/**
 * Represents a PagSeguro web service error
 * @see PagSeguroServiceException
 */
class PagSeguroError
 {
	
	/**
	 * Error code
	 */
    private $code;

    /**
     * Error description
     */
    private $message;

    /**
     * Initializes a new instance of the PagSeguroError class
     * 
     * @param String $code
     * @param String $message
     */
	public function __construct($code, $message){
		$this->code = $code;
		$this->message = $message;
	}
	
	/**
	 * @return the code
	 */
    public function getCode() {
        return $this->code;
    }

    /**
     * Sets the code
     * @param String $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * @return the error description
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Sets the error description
     * @param String $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

}
	
?>