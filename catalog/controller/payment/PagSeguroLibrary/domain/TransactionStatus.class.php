<?php
/**
 * Defines a list of known transaction statuses.
 * This class is not an enum to enable the introduction of new shipping types
 * without breaking this version of the library.
 */	
class TransactionStatus {
	
	private static $statusList = array(
		'INITIATED' => 0,
		'WAITING_PAYMENT' => 1,
		'IN_ANALYSIS' => 2,
		'PAID' => 3,
		'AVAILABLE' => 4,
		'IN_DISPUTE' => 5,
		'RETURNED' => 6,
		'CANCELLED' => 7,
		'RECOGNIZED_CHARGEBACK' => 8
	);
	
	/**
	 * the value of the transaction status
	 * Example: 3
	 */
	private $value;
	
	public function __construct($value){
		$this->setValue($value);
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function setByType($type) {
		if (isset(self::$statusList[$type])) {
			$this->value = self::$statusList[$type];
		} else {
			throw new Exception("undefined index $type");
		}
	}
	
	/**
	 * @return the status value.
	 */
	public function getValue(){
		return $this->value;
	}
	
	/**
	 * @param value
	 * @return the transaction status corresponding to the informed status value
	 */
	public function getTypeFromValue($value = null) {
		$value = ($value == null ? $this->value : $value);
		return array_search($this->value, self::$statusList);
	}
	
}

?>