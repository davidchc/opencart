<?php
/*
* Library autoloader - __autoload
*/
if (class_exists('PagSeguroLibrary')) {
	function __autoload($class) {
		
		$dirs = array(
			'domain',
			'exception',
			'parser',
			'service',
			'utils',
			'helper',
			'config',
			'resources',
			'log'
		);
		
		foreach ($dirs as $d) {
			$file = PagSeguroLibrary::getPath().DIRECTORY_SEPARATOR.$d.DIRECTORY_SEPARATOR.$class.'.class.php';
			if (file_exists($file) && is_file($file)) {
				require_once($file);
				return true;
			}
		}
		
		return false;
		
	}
}
?>