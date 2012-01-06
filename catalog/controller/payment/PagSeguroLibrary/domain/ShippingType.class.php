<?php
/**
* Defines a list of known shipping types.
* this class is not an enum to enable the introduction of new shipping types
* without breaking this version of the library.
*/	
class ShippingType {
	
	private static $typeList = array(
		'PAC' => 1,
		'SEDEX' => 2,
		'NONE' => 3
	);
	
	/**
	 * the shipping type value
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
	 * @return the value of the shipping type
	 */
	public function getValue(){
		return $this->value;
	}
	
	/**
	 * @param value
	 * @return the ShippingType corresponding to the informed value
	*/
	public function getTypeFromValue($value = null) {
		$value = ($value === null ? $this->value : $value);
		return array_search($value, self::$typeList);
	}
	
	/**
	 * @param type
	 * @return the code corresponding to the informed shipping type
	 */
	public static function getCodeByType($type){
		if (isset(self::$typeList[$type])) {
			return self::$typeList[$type];
		} else {
			return false;
		}
	}
	
}

?>