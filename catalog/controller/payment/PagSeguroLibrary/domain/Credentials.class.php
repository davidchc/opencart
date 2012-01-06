<?php
/**
* Abstract class that represents a PagSeguro credential
*/
abstract class Credentials {

	/**
	 * @return a map of name value pairs that compose this set of credentials
	 */
	abstract function getAttributesMap();
	
	/**
	 * @return a string that represents the current object
	 */
	abstract function toString();
	
}
	
?>