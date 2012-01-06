<?php
class PagSeguroResources{
	
	private static $resources;
	private static $data;
	const varName = 'PagSeguroResources';
	
	private function __construct() {
		define('ALLOW_PAGSEGURO_RESOURCES', TRUE);
		require_once PagSeguroLibrary::getPath().DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."PagSeguroResources.php";
		$varName = self::varName;
		if (isset($$varName)) {
			self::$data = $$varName;
			unset($$varName);
		} else {
			throw new Exception("Resources is undefined.");
		}
	}

	public static function init() {
		if (self::$resources == null) {
			self::$resources = new PagSeguroResources();
		}
		return self::$resources;
	}
	
	public static function getData($key1, $key2 = null) {
		if ($key2 != null) {
			if (isset(self::$data[$key1][$key2])) {
				return self::$data[$key1][$key2];
			} else {
				throw new Exception("Resources keys {$key1}, {$key2} not found.");
			}
		} else {
			if (isset(self::$data[$key1])) {
				return self::$data[$key1];
			} else {
				throw new Exception("Resources key {$key1} not found.");
			}
		}
	}
	
	public static function setData($key1, $key2, $value) {
		if (isset(self::$data[$key1][$key2])) {
			self::$data[$key1][$key2] = $value;
		} else {
			throw new Exception("Resources keys {$key1}, {$key2} not found.");
		}
	}
	
	public static function getWebserviceUrl($enviroment) {
		if (
				isset(self::$data['enviroment']) 
			&& 	isset(self::$data['enviroment'][$enviroment])
			&& 	isset(self::$data['enviroment'][$enviroment]['webserviceUrl'])
		) {
			return self::$data['enviroment'][$enviroment]['webserviceUrl'];
		} else {
			throw new Exception("WebService URL not set for $enviroment enviroment.");
		}	
	}
	
}
?>