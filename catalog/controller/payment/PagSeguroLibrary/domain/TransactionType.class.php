<?php
/**
 * Defines a list of known transaction types.
 * This class is not an enum to enable the introduction of new shipping types
 * without breaking this version of the library.
 */
class TransactionType {
	
	private static $typeList = array(
		'PAYMENT' => 1,
		'TRANSFER' => 2,
		'FUND_ADDITION' => 3,
		'WITHDRAW' => 4,
		'CHARGE' => 5,
		'DONATION' => 6,
		'BONUS' => 7,
		'BONUS_REPASS' => 8,
		'OPERATIONAL' => 9,
		'POLITICAL_DONATION' => 10
	);
	
	private $value;
	
	public function __construct($value) {
		$this->setValue($value);
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function setByType($type) {
		if (isset(self::$typeList[$type])) {
			$this->value = self::$typeList[$type];
		} else {
			throw new Exception("undefined index $type");
		}
	}	
	
	public function getValue(){
		return $this->value;
	}
	
	/**
	 * @param value
	 * @return the transaction type corresponding to the informed type value value
	 */
	public function getTypeFromValue($value = null) {
		$value = ($value == null ? $this->value : $value);
		return array_search($this->value, self::$typeList);
	}
	
	
}

?>