<?php

class ServiceParser{
	
	public static function readErrors($str_xml) {
		$parser = new xmlParser($str_xml);
		$data = $parser->getResult('errors');
		$errors = array();
		if (isset($data['error']) && is_array($data['error'])) {
			if (isset($data['error']['code']) && isset($data['error']['message'])) {
				array_push($errors, new PagSeguroError($data['error']['code'], $data['error']['message']));
			} else {
				foreach($data['error'] as $key => $value) {
					if (isset($value['code']) && isset($value['message'])) {
						array_push($errors, new PagSeguroError($value['code'], $value['message']));
					}
				}				
			}
		}
		return $errors;
	}
	
}

?>