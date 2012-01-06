<?php
/**
 * Defines a list of known payment method types.
 */
class PaymentMethodType {
	
	private static $typeList = array(
		/** Credit card */
		'CREDIT_CARD' => 1,
		
		/** Boleto - is a form of invoicing in Brazil */
		'BOLETO' => 2,
		
		/** Online transfer */
		'ONLINE_TRANSFER' => 3,
		
		/** PagSeguro account balance */
		'BALANCE' => 4,
		
		/** OiPaggo */
		'OI_PAGGO' => 5
	);
	
	/**
	 * Payment method type value
	 * Example: 1
	 */
	private $value;
	
	public function __construct($value){
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
	
	/**
	 * @return payment method type value
	 * Example: 1
	 */
	public function getValue(){
		return $this->value;
	}
	
	/**
	 * @param value
	 * @return the PaymentMethodType corresponding to the informed value
	 */
	public function getTypeFromValue($value = null) {
		$value = ($value == null ? $this->value : $value);
		return array_search($this->value, self::$typeList);
	}
	
	
}

?>