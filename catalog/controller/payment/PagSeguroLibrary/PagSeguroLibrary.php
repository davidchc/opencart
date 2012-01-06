<?php
class PagSeguroLibrary {
	
	const VERSION = "2.0.0";
	private static $library;
	private static $path;
	public static $resources;
	public static $config;
	public static $log;
	
	private function __construct() {
		self::$path = (dirname(__FILE__));
		if (function_exists('spl_autoload_register')) {
			require_once "loader".DIRECTORY_SEPARATOR."PagSeguroAutoLoader.class.php";
			PagSeguroAutoloader::init();
		} else {
			require_once "loader".DIRECTORY_SEPARATOR."PagSeguroAutoLoader.php";
		}
		self::$resources = PagSeguroResources::init();
		self::$config = PagSeguroConfig::init();
		self::$log = LogPagSeguro::init();
	}
	
	public static function init() {
		if (self::$library == null) {
			self::$library = new PagSeguroLibrary();
		}
		return self::$library;
	}
	
	public final static function getVersion(){
		return self::VERSION;
	}
	public final static function getPath(){
		return self::$path;
	}
	
}
PagSeguroLibrary::init();
?>