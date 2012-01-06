<?php
/**
 * Represents a phone number
 */	
class Phone {
	
	/**
	 * Area code
	 */
	private $areaCode;

	/**
	 * Phone number
	 */
	private $number;
	
	/**
	 * Initializes a new instance of the Phone class
	 * 
	 * @param String $areaCode
	 * @param String $number
	 */
	public function __construct($areaCode = null, $number = null) {
		$this->areaCode = ($areaCode == null ? null : $areaCode);
		$this->number   = ($number   == null ? null : $number);
	}
	
	/**
	 * @return the area code
	 */
	public function getAreaCode() {
		return $this->areaCode;
	}
	
	/**
	 * @return the number
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * Sets the area code
	 * @param String $areaCode
	 */
	public function setAreaCode($areaCode) {
		$this->areaCode = $areaCode;
	}

	/**
	 * Sets the number
	 * @param String $number
	 */
	public function setNumber($number) {
		$this->number = $number;
	}
	
}
	
?>