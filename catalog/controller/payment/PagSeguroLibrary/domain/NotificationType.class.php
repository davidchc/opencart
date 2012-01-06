<?php
/**
 * Defines a list of known notification types.
 * This class is not an enum to enable the introduction of new shipping types
 * without breaking this version of the library.
 */	
class NotificationType {
	
	private static $typeList = array(
		'TRANSACTION' 	=> 'transaction',
	);
	
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
	
	public function getValue(){
		return $this->value;
	}
	
	/**
	 * @param value
	 * @return the NotificationType corresponding to the informed value
	 */
	public function getTypeFromValue($value = null) {
		$value = ($value == null ? $this->value : $value);
		return array_search($this->value, self::$typeList);
	}
	
}

?>