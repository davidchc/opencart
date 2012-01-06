<?php
/**
 * Encapsulates web service calls regarding PagSeguro payment requests
 */
class PaymentService {
	
	const serviceName = 'paymentService';
	
	private static function buildCheckoutRequestUrl(PagSeguroConnectionData $connectionData) {
		return $connectionData->getServiceUrl().'/?'.$connectionData->getCredentialsUrlQuery();
	}
	
	private static function buildCheckoutUrl(PagSeguroConnectionData $connectionData, $code) {
		return $connectionData->getResource('checkoutUrl')."?code=$code";
	}
	
	// createCheckoutRequest is the actual implementation of the Register method
	// This separation serves as test hook to validate the Uri
	// against the code returned by the service
	public static function createCheckoutRequest(Credentials $credentials, PaymentRequest $paymentRequest) {
		
		LogPagSeguro::info("PaymentService.Register(".$paymentRequest->toString().") - begin");
		
		$connectionData = new PagSeguroConnectionData($credentials, self::serviceName);
		
		try {
			
			$connection = new HttpConnection();
			$connection->post(
				self::buildCheckoutRequestUrl($connectionData),
				PaymentParser::getData($paymentRequest),
				$connectionData->getServiceTimeout(),
				$connectionData->getCharset()
			);
			
			$httpStatus = new HttpStatus($connection->getStatus());
			
			switch ($httpStatus->getType()) {
				
				case 'OK':
					$PaymentParserData = PaymentParser::readSuccessXml($connection->getResponse());
					$paymentUrl = self::buildCheckoutUrl($connectionData, $PaymentParserData->getCode());
					LogPagSeguro::info("PaymentService.Register(".$paymentRequest->toString().") - end {1}".$PaymentParserData->getCode());
					break;
				
				case 'BAD_REQUEST':
					$errors = PaymentParser::readErrors($connection->getResponse());
					$e = new PagSeguroServiceException($httpStatus, $errors);
					LogPagSeguro::error("PaymentService.Register(".$paymentRequest->toString().") - error ".$e->getOneLineMessage());
					throw $e;
					break;
				
				default:
					$e = new PagSeguroServiceException($httpStatus);
					LogPagSeguro::error("PaymentService.Register(".$paymentRequest->toString().") - error ".$e->getOneLineMessage());
					throw $e;
					break;
					
			}
			
			return ( isset($paymentUrl)? $paymentUrl : false );
			
		} catch (PagSeguroServiceException $e) {
			throw $e;
		} catch (Exception $e) {
			LogPagSeguro::error("Exception: ".$e->getMessage());
			throw $e;
		}
		
	}

}
	
?>