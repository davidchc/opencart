<?php
	
class TransactionParser extends ServiceParser {
	
	public static function readSearchResult($str_xml) {
		
		$parser = new xmlParser($str_xml);
		$data = $parser->getResult('transactionSearchResult');
		
		$searchResutlt = new TransactionSearchResult();
		
		if (isset($data['totalPages'])){
			$searchResutlt->setTotalPages($data['totalPages']);
		}
		
		if (isset($data['date'])){
			$searchResutlt->setDate($data['date']);
		}
		
		if (isset($data['resultsInThisPage'])){
			$searchResutlt->setResultsInThisPage($data['resultsInThisPage']);
		}
		
		if (isset($data['currentPage'])){
			$searchResutlt->setCurrentPage($data['currentPage']);
		}		
		
		if (isset($data['transactions']) && is_array($data['transactions'])) {
			
			$transactions = array();
			$i = 0;
			
			foreach ($data['transactions']['transaction'] as $key => $value) {
				
				$transactionSummary = new TransactionSummary();
				
				if (isset($value['type'])){
					$transactionSummary->setType(new TransactionType($value['type']));
				}
				
				if (isset($value['code'])){
					$transactionSummary->setCode($value['code']);
				}
				
				if (isset($value['reference'])){
					$transactionSummary->setReference($value['reference']);
				}
				
				if (isset($value['date'])){
					$transactionSummary->setDate($value['date']);
				}
				
				if (isset($value['lastEventDate'])){
					$transactionSummary->setLastEventDate($value['lastEventDate']);
				}
				
				if (isset($value['grossAmount'])){
					$transactionSummary->setGrossAmount($value['grossAmount']);
				}
				
				if (isset($value['status'])){
					$transactionSummary->setStatus(new TransactionStatus($value['status']));
				}
				
				if (isset($value['netAmount'])){
					$transactionSummary->setNetAmount($value['netAmount']);
				}
				
				if (isset($value['discountAmount'])){
					$transactionSummary->setDiscountAmount($value['discountAmount']);
				}
				
				if (isset($value['feeAmount'])){
					$transactionSummary->setFeeAmount($value['feeAmount']);
				}
				
				if (isset($value['extraAmount'])){
					$transactionSummary->setExtraAmount($value['extraAmount']);
				}
				
				if (isset($value['lastEvent'])){
					$transactionSummary->setLastEvent($value['lastEvent']);
				}
				
				if (isset($value['paymentMethod'])){
					
					$paymentMethod = new PaymentMethod();
					
					if (isset($value['paymentMethod']['type'])){
						$paymentMethod->setType(new PaymentMethodType($value['paymentMethod']['type']));
					}
					
					if (isset($value['paymentMethod']['code'])) {
						$paymentMethod->setCode(new PaymentMethodCode($value['paymentMethod']['code']));
					}
					
					$transactionSummary->setPaymentMethod($paymentMethod);
					
				}
				
				$transactions[$i++] = $transactionSummary;
				
			}
			
			$searchResutlt->setTransactions($transactions);
			
		}
		
		return $searchResutlt;
		
	}
	
	public static function readTransaction($str_xml) {
		
		// Parser
		$parser = new xmlParser($str_xml);
		
		// <transaction>
		$data = $parser->getResult('transaction');
		$transaction = new Transaction();
		
		// <transaction> <lastEventDate>
		if (isset($data["lastEventDate"])) {
			$transaction->setLastEventDate($data["lastEventDate"]);
		}
		
		// <transaction> <date>
		if (isset($data["date"])) {
			$transaction->setDate($data["date"]);
		}
		
		// <transaction> <code>
		if (isset($data["code"])) {
			$transaction->setCode($data["code"]);
		}
		
		// <transaction> <reference>
		if (isset($data["reference"])) {
			$transaction->setReference($data["reference"]);
		}
		
		// <transaction> <type>
		if (isset($data["type"])) {
			$transaction->setType(new TransactionType($data["type"]));
		}
		
		// <transaction> <status>
		if (isset($data["status"])) {
			$transaction->setStatus(new TransactionStatus($data["status"]));
		}
		
		if (isset($data["paymentMethod"]) && is_array($data["paymentMethod"])) {
			
			// <transaction> <paymentMethod>
			$paymentMethod = new PaymentMethod();
			
			// <transaction> <paymentMethod> <type>
			if (isset($data["paymentMethod"]['type'])) {
				$paymentMethod->setType(new PaymentMethodType($data["paymentMethod"]['type']));
			}
			
			// <transaction> <paymentMethod> <code>
			if (isset($data["paymentMethod"]['code'])) {
				$paymentMethod->setCode(new PaymentMethodCode($data["paymentMethod"]['code']));
			}				
			
			$transaction->setPaymentMethod($paymentMethod);
			
		}
		
		// <transaction> <grossAmount>
		if (isset($data["grossAmount"])) {
			$transaction->setGrossAmount($data["grossAmount"]);
		}
		
		// <transaction> <discountAmount>
		if (isset($data["discountAmount"])) {
			$transaction->setDiscountAmount($data["discountAmount"]);
		}
		
		// <transaction> <feeAmount>
		if (isset($data["feeAmount"])) {
			$transaction->setFeeAmount($data["feeAmount"]);
		}
		
		// <transaction> <netAmount>
		if (isset($data["netAmount"])) {
			$transaction->setNetAmount($data["netAmount"]);
		}
		
		// <transaction> <extraAmount>
		if (isset($data["extraAmount"])) {
			$transaction->setExtraAmount($data["extraAmount"]);
		}
		
		// <transaction> <installmentCount>
		if (isset($data["installmentCount"])) {
			$transaction->setInstallmentCount($data["installmentCount"]);
		}
		
		if (isset($data["items"]['item']) && is_array($data["items"]['item'])) {
			
			$items = Array();
			$i = 0;
			
			foreach ($data["items"]['item'] as $key => $value) {
				
				// <transaction> <items> <item>
				$item = new Item();
				
				// <transaction> <items> <item> <id>
				if (isset($value["id"])) {
					$item->setId($value["id"]);
				}
				
				// <transaction> <items> <item> <description>
				if (isset($value["description"])) {
					$item->setDescription($value["description"]);
				}
				
				// <transaction> <items> <item> <quantity>
				if (isset($value["quantity"])) {
					$item->setQuantity($value["quantity"]);
				}
				
				// <transaction> <items> <item> <amount>
				if (isset($value["amount"])) {
					$item->setAmount($value["amount"]);
				}
				
				// <transaction> <items> <item> <weight>
				if (isset($value["weight"])) {
					$item->setWeight($value["weight"]);
				}
				
				$items[$i] = $item;
				$i++;
				
			}
			
			// <transaction> <items>
			$transaction->setItems($items);
			
		}
		
		if (isset($data["sender"])) {
			
			// <transaction> <sender>
			$sender = new Sender();
			
			// <transaction> <sender> <name>
			if (isset($data["sender"]["name"])) {
				$sender->setName($data["sender"]["name"]);
			}
			
			// <transaction> <sender> <email>
			if (isset($data["sender"]["email"])) {
				$sender->setEmail($data["sender"]["email"]);
			}
			
			if (isset($data["sender"]["phone"])) {
				
				// <transaction> <sender> <phone>
				$phone = new Phone();
				
				// <transaction> <sender> <phone> <areaCode>
				if (isset($data["sender"]["phone"]["areaCode"])) {
					$phone->setAreaCode($data["sender"]["phone"]["areaCode"]);
				}
				
				// <transaction> <sender> <phone> <number>
				if (isset($data["sender"]["phone"]["number"])) {
					$phone->setNumber($data["sender"]["phone"]["number"]);
				}
				
				$sender->setPhone($phone);	
				
			}
			
			$transaction->setSender($sender);
			
		}
		
		if (isset($data["shipping"]) && is_array($data["shipping"])) {
			
			// <transaction> <shipping>
			$shipping = new Shipping();
			
			// <transaction> <shipping> <type>
			if (isset($data["shipping"]["type"])) {
				$shipping->setType(new ShippingType($data["shipping"]["type"]));
			}
			
			// <transaction> <shipping> <cost>
			if (isset($data["shipping"]["cost"])) {
				$shipping->setCost($data["shipping"]["cost"]);
			}
			
			if (isset($data["shipping"]["address"]) && is_array($data["shipping"]["address"])) {
				
				// <transaction> <shipping> <address>
				$address = new Address();
				
				// <transaction> <shipping> <address> <street>
				if (isset($data["shipping"]["address"]["street"])) {
					$address->setStreet($data["shipping"]["address"]["street"]);
				}
				
				// <transaction> <shipping> <address> <number>
				if (isset($data["shipping"]["address"]["number"])) {
					$address->setNumber($data["shipping"]["address"]["number"]);
				}
				
				// <transaction> <shipping> <address> <complement>
				if (isset($data["shipping"]["address"]["complement"])) {
					$address->setComplement($data["shipping"]["address"]["complement"]);
				}
				
				// <transaction> <shipping> <address> <city>
				if (isset($data["shipping"]["address"]["city"])) {
					$address->setCity($data["shipping"]["address"]["city"]);
				}
				
				// <transaction> <shipping> <address> <state>
				if (isset($data["shipping"]["address"]["state"])) {
					$address->setState($data["shipping"]["address"]["state"]);
				}
				
				// <transaction> <shipping> <address> <district>
				if (isset($data["shipping"]["address"]["district"])) {
					$address->setDistrict($data["shipping"]["address"]["district"]);
				}
				
				// <transaction> <shipping> <address> <postalCode>
				if (isset($data["shipping"]["address"]["postalCode"])) {
					$address->setPostalCode($data["shipping"]["address"]["postalCode"]);
				}
				
				// <transaction> <shipping> <address> <country>
				if (isset($data["shipping"]["address"]["country"])) {
					$address->setCountry($data["shipping"]["address"]["country"]);
				}
				
				$shipping->setAddress($address);
				
			}
			
			// <transaction> <shipping>
			$transaction->setShipping($shipping);
			
		}
		
		return $transaction;
		
	}
	
}
	

?>