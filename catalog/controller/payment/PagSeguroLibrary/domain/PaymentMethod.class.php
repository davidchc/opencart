<?php
/**
 * Payment method
 *
 */
class PaymentMethod{
	
	/**
	* Payment method type
	*/
    private $type;
    
    /**
    * Payment method code
    */
    private $code;

    /**
     * Initializes a new instance of the PaymentMethod class
     *  
     * @param PaymentMethodType $type
     * @param PaymentMethodCode $code
     */
    public function __construct($type = null, $code = null) {
    	if ($type) {
    		$this->setType($type);
    	}
    	if ($code) {
    		$this->setCode($code);
    	}
    }

    /**
     * @return the payment method type
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * Sets the payment method type
     * @param PaymentMethodType $type
     */
    public function setType($type) {
    	if ($type instanceof PaymentMethodType) {
    		$this->type = $type;
    	} else {
    		$this->type = new PaymentMethodType($type);
    	}
    }

    /**
     * @return the code
     */
    public function getCode() {
        return $this->code;
    }
    
    /**
     * Sets the payment method code
     * @param PaymentMethodCode $code
     */
    public function setCode($code) {
        if ($code instanceof PaymentMethodCode) {
    		$this->code = $code;
    	} else {
    		$this->code = new PaymentMethodCode($code);
    	}
    }
	
}
	
?>