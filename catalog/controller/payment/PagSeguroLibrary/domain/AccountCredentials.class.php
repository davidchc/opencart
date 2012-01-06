<?php
/**
* Identifies a PagSeguro account
*/
class AccountCredentials extends Credentials{

	/**
	* Primary email associated with this account
	*/
	private $email;
	
	/**
	* PagSeguro token
	*/
	private $token;
	
	/**
	* Initializes a new instance of the AccountCredentials class
	*
	* @param email
	* @param token
	*/
	public function __construct ($email, $token) {
		if ($email !== null && $token !== null) {
			$this->email = $email;
			$this->token = $token;
		} else {
			throw new Exception("Credentials not set.");			
		}
	}
	
	/**
	 * @return the e-mail from this account credential object 
	 */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Sets the e-mail from this account credential object
     */
    public function setEmail($email) {
        $this->email = $email;
    }
	
    /**
     * @return the token from this account credential object
     */
    public function getToken() {
        return $this->token;
    }
	
    /**
     * Sets the token in this account credential object
     */
    public function setToken($token) {
        $this->token = $token;
    }
    
	/**
	 * @return a map of name value pairs that compose this set of credentials
	 */
    public function getAttributesMap() {
    	return Array(
    		'email' => $this->email,
    		'token' => $this->token
    	);
    }
	
    /**
    * @return a string that represents the current object
    */
	public function toString() {
		return $this->email." - ".$this->token;
	}
	
}

?>