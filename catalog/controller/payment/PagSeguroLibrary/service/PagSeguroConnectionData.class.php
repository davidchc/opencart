<?php

class PagSeguroConnectionData{
	
	private $serviceName;
	private $credentials;
	private $resources;
	private $enviroment;
	private $webserviceUrl;
	private $servicePath;
	private $serviceTimeout;
	private $charset;
	
	public function __construct(Credentials $credentials, $serviceName) {
	
		$this->credentials = $credentials;
		$this->serviceName = $serviceName;
	
		$this->setEnviroment(PagSeguroConfig::getEnviroment());
		$this->setWebserviceUrl(PagSeguroResources::getWebserviceUrl($this->getEnviroment()));
		$this->setCharset(PagSeguroConfig::getApplicationCharset());
	
		$this->resources = PagSeguroResources::getData($this->serviceName);
		if (isset($this->resources['servicePath'])) {
			$this->setServicePath($this->resources['servicePath']);
		}
		if (isset($this->resources['serviceTimeout'])) {
			$this->setServiceTimeout($this->resources['serviceTimeout']);
		}
	
	}
	
	public function getCredentials() {
		return $this->credentials;
	}
	public function setCredentials($credentials) {
		$this->credentials = $credentials;
	}
	
	public function getCredentialsUrlQuery() {
		return http_build_query($this->credentials->getAttributesMap(), '', '&');
	}
	
	public function getEnviroment(){
		return $this->enviroment;
	}
	public function setEnviroment($enviroment){
		$this->enviroment = $enviroment;
	}
	
	public function getWebserviceUrl(){
		return $this->webserviceUrl;
	}
	public function setWebserviceUrl($webserviceUrl){
		$this->webserviceUrl = $webserviceUrl;
	}
	
	public function getServicePath(){
		return $this->servicePath;
	}
	public function setServicePath($servicePath){
		$this->servicePath = $servicePath;
	}
	
	public function getServiceTimeout(){
		return $this->serviceTimeout;
	}
	public function setServiceTimeout($serviceTimeout){
		$this->serviceTimeout = $serviceTimeout;
	}
	
	public function getServiceUrl(){
		return $this->getWebserviceUrl().$this->getServicePath();
	}
	
	public function getResource($resource) {
		return $this->resources[$resource];
	}
	
	public function getCharset(){
		return $this->charset;
	}
	public function setCharset($charset){
		$this->charset = $charset;
	}	
	
}

?>